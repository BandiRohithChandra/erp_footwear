<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Sole;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\ProductionProcess;
use App\Models\Batch;
use App\Models\Process;
use App\Models\StockMovement;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Employee;

class BatchFlowController extends Controller
{
    // Show list of batches
   public function index()
{
    // Load batches with their relations
    $batches = Batch::with(['product', 'workers', 'batchFlows', 'productionProcesses'])
        ->orderBy('created_at', 'desc')
        ->get();

    // ✅ Attach a readable summary of process statuses
    foreach ($batches as $batch) {
        $processSummaries = $batch->productionProcesses
            ->map(fn($p) => "{$p->name} - {$p->status}")
            ->implode(', ');

        $batch->process_summary = $processSummaries ?: 'No processes assigned';
    }

    return view('production.batch_flow_index', compact('batches'));
}


public function create(Request $request)
{
    $articles = Product::all();
    $workers = Employee::where('role', 'Labor')->get();
    $salesReps = Employee::where('employee_type', 'sales')->get();

    $comingFromOrder = $request->session()->has('quotation_products');

    if ($comingFromOrder) {

        $quotationProducts = $request->session()->get('quotation_products', []);
        $orders = $request->session()->get('orders', []);

        // Clear session
        $request->session()->forget(['quotation_products', 'orders']);

        // Extract unique clients with brand
        $clients = collect($quotationProducts)
    ->flatMap(fn($p) => $p['clients'] ?? [])
    ->unique('id')
    ->values()
    ->map(function ($c) {
    $user = \App\Models\User::find($c['id']);

    return (object)[
        'id'            => $c['id'],
        'name'          => $c['name'],
        'business_name' => $user->business_name ?? null,   // ⭐ FIX
        'category'      => $c['category'] ?? null,
        'brand'         => $c['brand'] ?? 'N/A',
    ];
});


        // Extract quotation brand
        $quotation = (object)[
            'brand_name' => $quotationProducts[0]['brand_name'] ?? ($clients[0]->brand ?? null),
        ];

    } else {

        $clients = User::whereIn('category', ['wholesale', 'retail'])
    ->select('id', 'name', 'business_name', 'category')
    ->get();


        $quotationProducts = [];
        $orders = [];

        $quotation = null; // no quotation available
    }

    $autoBatchNo = 'BATCH-' . date('YmdHis');
    $selectedOrderNo = $orders[0]['order_no'] ?? null;

    return view('production.batch_flow_create', compact(
        'articles',
        'workers',
        'autoBatchNo',
        'quotationProducts',
        'orders',
        'salesReps',
        'clients',
        'selectedOrderNo',
        'quotation' // <-- VERY IMPORTANT
    ));
}


// In your controller
public function suggestions(Request $request)
{
    $query = $request->q;

    $soles = Sole::where('name', 'LIKE', "%{$query}%")
        ->select('name', 'color')
        ->distinct()
        ->limit(10)
        ->get();

    return response()->json($soles);
}

public function store(Request $request)
{
    // 1️⃣ Validate request
    $request->validate([
        'article_no'       => 'required|exists:products,id',
        'po_no'            => 'nullable|string|max:255',
        'batch_start_date' => 'required|date',
        'batch_end_date'   => 'nullable|date|after_or_equal:batch_start_date',
        'variations'       => 'required|json',
        'client_id'        => 'required|array|min:1',
        'client_id.*'      => 'exists:users,id',
    ]);

    $product = Product::with(['processes', 'materials', 'soles'])
                      ->findOrFail($request->article_no);

    $variations = json_decode($request->variations, true);
    $clientIds  = $request->input('client_id');

    DB::beginTransaction();
    try {
        $totalQuantity = 0;
        $soleObjects = [];

        // 3️⃣ Update soles (identified by sole name only)
        foreach ($variations as $details) {
            $sizes = $details['sizes'] ?? [];
            $variationQty = array_sum($sizes);
            $totalQuantity += $variationQty;

            $soleName = $details['sole_name'] ?? $details['sole_color'] ?? $details['color'] ?? null;

            if (!$soleName) continue;

            // ✅ Find sole by name only
            $existingSole = Sole::where('name', $soleName)->first();

            if (!$existingSole) {
                \Log::warning("❌ Sole not found with name '{$soleName}'");
                continue;
            }

            $existingSole->updateQuietly([
                'quantity'     => $variationQty,
                'qty_per_unit' => $details['qty_per_unit'] ?? 1,
                'sizes_qty'    => $sizes,
            ]);

            $soleObjects[] = ['sole' => $existingSole, 'sizes' => $sizes];
        }

        // 4️⃣ Create batch
        $batch = Batch::create([
            'batch_no'       => $request->batch_no,
            'product_id'     => $product->id,
            'name'           => $product->name,
            'po_no'          => $request->po_no,
            'quantity'       => $totalQuantity,
            'priority'       => $request->priority ?? 'normal',
            'start_date'     => $request->batch_start_date,
            'end_date'       => $request->batch_end_date,
            'variations'     => json_encode($variations),
            'created_by'     => auth()->user()->name ?? 'System',
            'status'         => 'pending',
            'stock_deducted' => false,
        ]);

        $batch->clients()->sync($clientIds);

        // 5️⃣ Deduct stock by sole name
        foreach ($soleObjects as $item) {
            $sole  = $item['sole'];
            $sizes = $item['sizes'];

            foreach ($sizes as $size => $qty) {
                if ($qty <= 0) continue;

                $requiredQty = $qty * ($sole->qty_per_unit ?? 1);

                $stock = Stock::firstOrCreate([
                    'item_id' => $sole->id,
                    'type'    => 'sole',
                    'size'    => $size,
                ], ['qty_available' => 0]);

                $stock->decrement('qty_available', $requiredQty);

                // Update size-wise sole stock
                $sizesQty = is_string($sole->sizes_qty)
                    ? json_decode($sole->sizes_qty, true)
                    : ($sole->sizes_qty ?? []);
                $sizesQty[$size] = ($sizesQty[$size] ?? 0) - $requiredQty;
                $sole->updateQuietly(['sizes_qty' => json_encode($sizesQty)]);

                // Record stock movement
                StockMovement::create([
                    'batch_id'    => $batch->id,
                    'item_id'     => $sole->id,
                    'type'        => 'sole',
                    'size'        => $size,
                    'change'      => -$requiredQty,
                    'qty_after'   => $stock->qty_available,
                    'description' => "Deducted {$requiredQty} for batch {$batch->batch_no}",
                ]);
            }
        }

        $batch->update(['stock_deducted' => true]);

        // 6️⃣ Create production processes
        foreach ($product->processes as $process) {
            ProductionProcess::updateOrCreate(
                [
                    'batch_id'   => $batch->id,
                    'process_id' => $process->id,
                    'product_id' => $product->id,
                ],
                [
                    'name'               => $process->name,
                    'labor_rate'         => $process->pivot->labor_rate ?? 0,
                    'status'             => 'pending',
                    'stage'              => 'Pending',
                    'assigned_quantity'  => $totalQuantity,
                    'completed_quantity' => 0,
                ]
            );
        }

        DB::commit();
        return redirect()
            ->route('batch.flow.labor_assignment', $batch->id)
            ->with('success', 'Batch created successfully for ' . count($clientIds) . ' client(s)!');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("Batch creation failed: " . $e->getMessage(), $request->all());
        return back()->withInput()->with('error', 'Failed to create batch: ' . $e->getMessage());
    }
}


public function updateStockOnBatchCreation($batch, $variations = null)
{
    DB::transaction(function () use ($batch, $variations) {

        $product = Product::with(['processes'])->findOrFail($batch->product_id);
        $variations = $variations ?? json_decode($batch->variations, true) ?? [];

        \Log::info("🔹 Starting stock deduction for Batch {$batch->batch_no}", ['variations' => $variations]);

        $deductStock = function ($sole, $size, $requiredQty) use ($batch) {
            $sizeStr = (string)$size;

            $stock = Stock::firstOrCreate(
                ['item_id' => $sole->id, 'type' => 'sole', 'size' => $sizeStr],
                ['qty_available' => 0, 'in_transit_qty' => 0]
            );

            $stock->qty_available -= $requiredQty;
            $stock->in_transit_qty += $requiredQty;
            $stock->saveQuietly();

            $sizesQty = is_string($sole->sizes_qty)
                ? json_decode($sole->sizes_qty, true)
                : ($sole->sizes_qty ?? []);
            $sizesQty[$sizeStr] = max(0, ($sizesQty[$sizeStr] ?? 0) - $requiredQty);

            $sole->sizes_qty = json_encode($sizesQty);
            $sole->quantity = max(0, ($sole->quantity ?? 0) - $requiredQty);
            $sole->saveQuietly(['skip_stock_sync' => true]);

            StockMovement::create([
                'batch_id'   => $batch->id,
                'item_id'    => $sole->id,
                'type'       => 'sole',
                'size'       => $sizeStr,
                'change'     => -$requiredQty,
                'qty_after'  => $stock->qty_available,
                'description'=> "Reserved {$requiredQty} for batch {$batch->batch_no}",
            ]);

            \Log::info("✅ Deducted {$requiredQty} from sole '{$sole->name}' (Size: {$sizeStr})");
        };

        // Deduct stock based on sole name only
        foreach ($variations as $variation) {
            $soleName = $variation['sole_color'] ?? $variation['color'] ?? null;
            $sizes = $variation['sizes'] ?? [];

            if (!$soleName) continue;

            $sole = Sole::where('name', $soleName)->first();

            if (!$sole) {
                \Log::warning("❌ No sole found with name '{$soleName}'");
                continue;
            }

            foreach ($sizes as $size => $qty) {
                if ($qty <= 0) continue;

                $requiredQty = $qty * ($sole->qty_per_unit ?? 1);
                $deductStock($sole, $size, $requiredQty);
            }
        }

        \Log::info("✅ Stock reservation completed for Batch {$batch->batch_no}");
    });
}




// public function updateStockOnBatchCreation($batch, $variations = null)
// {
//     DB::transaction(function () use ($batch, $variations) {

//         $stockErrors = [];

//         // Load product with related materials, liquids, soles, and processes
//         $product = Product::with(['materials', 'liquidMaterials', 'soles', 'processes'])
//             ->findOrFail($batch->product_id);

//         // Decode batch variations if not passed
//         $variations = $variations ?? json_decode($batch->variations, true) ?? [];

//         \Log::info("Starting stock deduction for Batch {$batch->batch_no}", ['variations' => $variations]);

//         // Helper: Deduct stock
//         $deductStock = function ($itemId, $type, $requiredQty, $size = null, $itemName = 'Unknown Item') use ($batch, &$stockErrors) {

//             $stocks = Stock::where('item_id', $itemId)
//                 ->where('type', $type)
//                 ->when($size !== null, fn($q) => $q->where('size', $size))
//                 ->lockForUpdate()
//                 ->get();

//             $totalAvailable = $stocks->sum('qty_available');
//             $remaining = $requiredQty;

//             foreach ($stocks as $stock) {
//                 if ($remaining <= 0) break;

//                 // For soles: allow negative stock
//                 $deduct = min($stock->qty_available, $remaining);
//                 if ($type === 'sole' && $totalAvailable < $requiredQty) {
//                     $deduct = $remaining; // Deduct even if it causes negative
//                 }

//                 $stock->qty_available -= $deduct;

//                 // Mark in-transit if negative (for soles)
//                 if ($type === 'sole' && $stock->qty_available < 0) {
//                     $stock->status = 'in-transit';
//                 }

//                 $stock->save();

//                 StockMovement::create([
//                     'batch_id'    => $batch->id,
//                     'item_id'     => $itemId,
//                     'type'        => $type,
//                     'size'        => $size,
//                     'change'      => -$deduct,
//                     'qty_after'   => $stock->qty_available,
//                     'description' => "Deducted {$deduct} for batch {$batch->batch_no}" . ($size ? " (Size: $size)" : ''),
//                     'created_at'  => now(),
//                     'updated_at'  => now(),
//                 ]);

//                 $remaining -= $deduct;
//                 \Log::info("Deducted {$deduct} from {$type} '{$itemName}'" . ($size ? " (Size: $size)" : '') . ". Remaining to deduct: {$remaining}");
//             }

//             // Only throw error for non-soles
//             if ($type !== 'sole' && $remaining > 0) {
//                 $stockErrors[] = "Insufficient stock for {$type} '{$itemName}'" . ($size ? " (Size: $size)" : '') . ". Required: {$requiredQty}, Available: {$totalAvailable}";
//                 \Log::warning(end($stockErrors));
//                 return false;
//             }

//             return true;
//         };

//         // Helper: Calculate required quantity per item
//         $calculateRequiredQty = function ($item, $type, $totalShoesQty) {
//             if ($item->unit === 'piece') {
//                 $shoesPerUnit = match($type) {
//                     'material' => $item->per_unit_length ?? 1,
//                     'liquid'   => $item->per_unit_volume ?? 1,
//                     default    => 1,
//                 };
//                 return ceil($totalShoesQty / $shoesPerUnit);
//             } else {
//                 return $totalShoesQty * ($item->quantity_used ?? 1);
//             }
//         };

//         // Calculate total shoes in batch
//         $totalShoes = 0;
//         foreach ($variations as $variation) {
//             $sizes = $variation['sizes'] ?? [];
//             $totalShoes += is_array($sizes) ? array_sum($sizes) : count($sizes);
//         }

//         // Deduct Raw Materials
//         foreach ($product->materials as $material) {
//             $requiredQty = $calculateRequiredQty($material, 'material', $totalShoes);
//             if ($requiredQty > 0) {
//                 $deductStock($material->id, 'material', $requiredQty, null, $material->name);
//             }
//         }

//         // Deduct Liquid Materials
//         foreach ($product->liquidMaterials as $liquid) {
//             $requiredQty = $calculateRequiredQty($liquid, 'liquid', $totalShoes);
//             if ($requiredQty > 0) {
//                 $deductStock($liquid->id, 'liquid', $requiredQty, null, $liquid->name);
//             }
//         }

//         // Deduct Soles (size-specific, allow negative)
//         foreach ($product->soles as $sole) {
//             foreach ($variations as $variation) {
//                 $sizes = $variation['sizes'] ?? [];
//                 foreach ($sizes as $size => $qty) {
//                     if ($qty <= 0) continue;
//                     $requiredQty = $qty * ($sole->pivot->quantity_used ?? 1);
//                     $deductStock($sole->id, 'sole', $requiredQty, $size, $sole->name);
//                 }
//             }
//         }

//         // Record Labor Usage
//         foreach ($product->processes as $process) {
//             $assignedQty = $process->pivot->assigned_qty ?? $batch->quantity;
//             $laborRate = $process->pivot->labor_rate ?? 0;

//             DB::table('batch_labor_usage')->insert([
//                 'batch_id'   => $batch->id,
//                 'process_id' => $process->id,
//                 'quantity'   => $assignedQty,
//                 'labor_rate' => $laborRate,
//                 'total_cost' => $assignedQty * $laborRate,
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);

//             \Log::info("Labor usage recorded for process '{$process->name}': Quantity = {$assignedQty}, Rate = {$laborRate}");
//         }

//         // Check for non-sole stock errors
//         if (!empty($stockErrors)) {
//             throw new \Exception(implode('; ', $stockErrors));
//         }

//         \Log::info("Stock deduction and labor assignment completed for Batch {$batch->batch_no}");
//     });
// }



// Step 2: Show labor assignment page
public function laborAssignment($batchId)
{
    $batch = Batch::with('product.processes')->findOrFail($batchId);

    if (!$batch->product) {
        return redirect()->route('batch.flow.index')
                         ->with('error', 'Batch has no associated product.');
    }

    // Deduct stock once before showing labor assignment
    if (!$batch->stock_deducted) {
        try {
            $this->updateStockOnBatchCreation($batch);
            $batch->stock_deducted = true;
            $batch->save();
            \Log::info("✅ Stock deducted for Batch {$batch->batch_no} when entering labor assignment.");
        } catch (\Exception $e) {
            \Log::error("❌ Stock deduction failed for batch {$batch->batch_no}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Stock deduction failed: ' . $e->getMessage());
        }
    }

    // Deduplicate processes by name
    $batch->product->processes = $batch->product->processes
        ->unique('name')  // remove duplicates by process name
        ->values();

    // Attach latest labor_rate to each process
    $batch->product->processes->each(function ($process) use ($batch) {
        $lastProcess = \App\Models\ProductionProcess::where('product_id', $batch->product->id)
                        ->where('name', $process->name)
                        ->orderByDesc('created_at')
                        ->first();
        $process->labor_rate = $lastProcess->labor_rate ?? 0;

        // Ensure assigned quantity shows only if it exists
        $process->assigned_quantity = $lastProcess->assigned_quantity ?? 0;
        $process->completed_quantity = $lastProcess->completed_quantity ?? 0;
    });

    // Calculate total quantity from variations
    $variations = json_decode($batch->variations, true) ?? [];
    $calculatedQty = 0;
    foreach ($variations as $details) {
        $sizes = $details['sizes'] ?? [];
        $calculatedQty += array_sum($sizes);
    }
    $batch->product->calculated_total_qty = $calculatedQty ?: ($batch->quantity ?? 0);

    // Format dates
    $batch->start_date_formatted = $batch->start_date ? date('d-m-Y', strtotime($batch->start_date)) : null;
    $batch->end_date_formatted   = $batch->end_date ? date('d-m-Y', strtotime($batch->end_date)) : null;

    // Get all labors grouped by type
    // ✅ Fetch all active labors
$allLabors = Employee::where('role', 'Labor')
    ->whereNotNull('labor_type')
    ->where('status', 'active')
    ->get();

/** ✅ Normalize helper */
$normalize = fn($text) => strtolower(trim(preg_replace('/[^a-z]/i', '', $text)));

/** ✅ Dynamic fuzzy match logic */
$batch->product->processes->each(function ($process) use ($allLabors, $normalize) {
    $processName = strtolower(trim($process->name));
    $processWords = preg_split('/[\s_-]+/', $processName);
    $processFirst = $normalize($processWords[0] ?? '');

    $matchedLabors = collect();

    foreach ($allLabors as $labor) {
        $laborType = strtolower(trim($labor->labor_type ?? ''));
        $laborWords = preg_split('/[\s_-]+/', $laborType);
        $laborFirst = $normalize($laborWords[0] ?? '');

        // Fuzzy matching rules
        $similarity = 0;
        similar_text($processFirst, $laborFirst, $similarity);
        $distance = levenshtein($processFirst, $laborFirst);

        if (
            $similarity >= 70 ||            // 70% similar text
            $distance <= 2 ||               // or small spelling difference
            str_starts_with($laborFirst, $processFirst) ||
            str_starts_with($processFirst, $laborFirst)
        ) {
            $matchedLabors->push($labor);
        }
    }

    // Fallback to all if no match
    // if ($matchedLabors->isEmpty()) {
    //     $matchedLabors = $allLabors;
    // }

    $process->available_labors = $matchedLabors;
});

    return view('production.labor_assignment', compact('batch', 'variations'));
}



// Save labor assignments (Step 2)
// Save labor assignments (Step 2)
public function saveLaborAssignment(Request $request, $batchId)
{
    $batch = Batch::with('product.processes')->findOrFail($batchId);

    if (!$batch->product) {
        return redirect()->route('batch.flow.index')
                         ->with('error', 'Batch has no associated product. Please assign a valid product.');
    }

    $request->validate([
        'labors' => 'required|array',
        'labors.*' => 'array',
        'worker_qty' => 'required|array',
        // We validate qty per size dynamically later
    ]);

    $syncData = [];
    $assignments = [];

    foreach ($batch->product->processes as $process) {
        if (!isset($request->labors[$process->id])) continue;

        foreach ($request->labors[$process->id] as $workerId) {
            $worker = \App\Models\Employee::find($workerId);
            $rate = $worker->labor_amount ?? 0;
            $paymentType = $worker->salary_basis ?? 'daily';

            // Check if worker has qty assigned
            $totalQty = 0;
            $sizeQtys = $request->worker_qty[$process->id][$workerId] ?? [];

            foreach ($sizeQtys as $size => $qty) {
                $qty = (int) $qty;
                if ($qty > 0) {
                    $totalQty += $qty;
                }
            }

            if ($totalQty > 0) {
                $startDate = $request->start_date[$process->id][$workerId] ?? null;
                $endDate   = $request->end_date[$process->id][$workerId] ?? null;

                $syncData[$workerId] = [
                    'process_id'   => $process->id,
                    'quantity'     => $totalQty,
                    'size_qtys'    => json_encode($sizeQtys),
                    'labor_rate'   => $rate,
                    'payment_type' => $paymentType,
                    'labor_status' => 'pending',
                    'start_date'   => $startDate,
                    'end_date'     => $endDate,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];

                $assignments[] = [
                    'employee_id'   => $workerId,
                    'process_id'    => $process->id,
                    'quantity'      => $totalQty,
                    'size_qtys'     => $sizeQtys,
                    'start_date'    => $startDate,
                    'end_date'      => $endDate,
                    'payment_type'  => $paymentType,
                    'rate'          => $rate,
                ];
            }
        }
    }

    // Sync all workers once
    if (!empty($syncData)) {
        $batch->workers()->sync($syncData);
    }

    // Save labor assignments JSON for quick reference
    $batch->labor_assignments = json_encode($assignments);
    $batch->status = 'in_progress';
    $batch->save();

    return redirect()->route('batch.flow.index')->with('success', 'Labor assignments saved successfully.');
}

public function show($id)
{
    // ✅ Load batch with relationships
    $batch = Batch::with(['workers', 'product.processes', 'product.soles'])->findOrFail($id);

    // ✅ Ensure batch has product
    if (!$batch->product) {
        return redirect()
            ->route('batch.flow.index')
            ->with('error', 'Batch has no associated product. Please assign a valid product.');
    }

    // ✅ Decode batch variations safely
    $batch->variations = is_string($batch->variations)
        ? json_decode($batch->variations, true)
        : ($batch->variations ?? []);

    // ✅ Flatten available quantities by color + size
    $availableByColorSize = [];
    foreach ($batch->variations as $variation) {
        $color = $variation['color'] ?? 'unknown';
        foreach ($variation['sizes'] ?? [] as $size => $qty) {
            $availableByColorSize[$color][$size] = (int) $qty;
        }
    }

    // ✅ Decode labor assignments safely
    $laborAssignments = is_string($batch->labor_assignments)
        ? json_decode($batch->labor_assignments, true)
        : ($batch->labor_assignments ?? []);

    // ✅ Extract employee & process IDs
    $employeeIds = collect($laborAssignments)->pluck('employee_id')->unique()->toArray();
    $processIds = collect($laborAssignments)->pluck('process_id')->unique()->toArray();

    $employees = DB::table('employees')->whereIn('id', $employeeIds)->get()->keyBy('id');
    $processes = DB::table('production_processes')->whereIn('id', $processIds)->get()->keyBy('id');

    // ✅ Get live statuses from employee_batch
    $liveStatuses = DB::table('employee_batch')
        ->where('batch_id', $id)
        ->select('employee_id', 'process_id', 'labor_status')
        ->get()
        ->mapWithKeys(fn($r) => ["{$r->employee_id}_{$r->process_id}" => ucfirst($r->labor_status)]);

    // ✅ Map and merge everything
    $employeeAssignments = collect($laborAssignments)->map(function ($assignment) use ($employees, $processes, $batch, $liveStatuses) {
        $employee = $employees[$assignment['employee_id']] ?? null;
        $process = $processes[$assignment['process_id']] ?? null;

        $key = "{$assignment['employee_id']}_{$assignment['process_id']}";
        $status = $liveStatuses[$key] ?? (!empty($assignment['end_date']) ? 'Completed' : 'Pending');

        // ✅ Map variations from batch to employee
        $laborVariations = [];
        foreach ($assignment['variations'] as $index => $sizes) {
    $batchVar = $batch->variations[$index] ?? [];

    // Fetch sole name from DB using sole_color
    $soleColor = $batchVar['sole_color'] ?? null;
    $soleName = '-';

    if ($soleColor) {
        $soleRecord = \App\Models\Sole::where('color', $soleColor)->first();
        if ($soleRecord) {
            $soleName = $soleRecord->name;
        }
    }

    $laborVariations[] = [
    'color'      => $batchVar['color'] ?? '-',
    'sole_color' => $batchVar['sole_color'] ?? '-',
    'sole_name'  => $batchVar['sole_name'] ?? '-',   // ADD THIS
    'sizes'      => $sizes,
];

}


        return (object) [
            'employee_id' => $assignment['employee_id'],
            'employee_name' => $employee->name ?? 'Unknown',
            'labor_type' => $employee->labor_type ?? '-',
            'process_id' => $assignment['process_id'],
            'process_name' => $process->name ?? '-',
            'assigned_quantity' => $assignment['total_quantity'] ?? 0,
            'labor_rate' => $assignment['salary_per_day'] ?? '0.00',
            'labor_status' => $status,
            'start_date' => $assignment['start_date'] ?? null,
            'end_date' => $assignment['end_date'] ?? null,
            'variations' => $laborVariations,
        ];
    });

    // ✅ Group by employee_id
    $groupedAssignments = $employeeAssignments->groupBy('employee_id');

    // ✅ Fetch clients with their quotations that include this product
    // ✅ Fetch clients for this batch (even without quotations)
$clients = User::whereIn('id', function ($query) use ($batch) {
        $query->select('client_id')
              ->from('batch_client')
              ->where('batch_id', $batch->id);
    })
    ->whereIn('category', ['wholesale', 'retail'])
    ->with(['quotations' => function ($q) use ($batch) {
        $q->whereHas('products', function ($p) use ($batch) {
            $p->where('products.id', $batch->product_id);
        })
        ->with(['products' => function ($p) use ($batch) {
            $p->where('products.id', $batch->product_id)
              ->withPivot(['quantity', 'unit_price', 'variations']);
        }]);
    }])
    ->get()
    ->map(function ($client) use ($batch) {
        // ✅ If no quotations, create a "virtual quotation-like" entry for modal
        // ✅ Create a pseudo-quotation object for manually created batches
if ($client->quotations->isEmpty()) {
    $batchVariations = is_string($batch->variations)
        ? json_decode($batch->variations, true)
        : ($batch->variations ?? []);

    // Compute total qty
    $totalQty = collect($batchVariations)
        ->flatMap(fn($v) => $v['sizes'] ?? [])
        ->sum(fn($qty) => is_array($qty)
            ? ($qty['available'] ?? $qty['ordered'] ?? $qty['delivered'] ?? 0)
            : $qty);

    // ✅ Build as Collection-compatible structure
    $fakeProduct = (object)[
        'id' => $batch->product_id,
        'pivot' => (object)[
            'quantity' => $totalQty,
            'variations' => json_encode($batchVariations),
        ],
    ];

    $fakeQuotation = (object)[
        'quotation_no' => 'N/A',
        // 👇 wrap it as a Laravel collection
        'products' => collect([$fakeProduct]),
    ];

    // 👇 assign as a collection
    $client->quotations = collect([$fakeQuotation]);
}


        return $client;
    });

    // ✅ Fetch the quotation used to create this batch
$quotation = \App\Models\Quotation::where('client_id', $clients->first()->id ?? null)
    ->whereHas('products', function ($q) use ($batch) {
        $q->where('products.id', $batch->product_id);
    })
    ->latest()
    ->first();


    // ✅ Return data to the view
    return view('production.batch_flow_show', [
    'batch' => $batch,
    'employeeAssignments' => $groupedAssignments,
    'clients' => $clients,
    'availableByColorSize' => $availableByColorSize,
    'quotation' => $quotation   // ⭐ ADD THIS
]);

}



public function edit($batchId)
{
    $batch = Batch::with([
        'clients',
        'workers',
        'product.soles',
        'product.materials',
        'product.processes',
        'product.liquidMaterials',
    ])->findOrFail($batchId);

    if (!$batch->product) {
        return redirect()->route('batch.flow.index')
                         ->with('error', 'Batch has no associated product. Please assign a valid product.');
    }

    /** 🔹 Decode product variations */
    $variations = is_string($batch->variations)
        ? json_decode($batch->variations, true)
        : ($batch->variations ?? []);

    $normalizedVariations = [];
    foreach ($variations as $variation) {
        $sizes = [];
        foreach (range(35, 44) as $size) {
            $sizes[$size] = isset($variation['sizes'][$size])
                ? (int) $variation['sizes'][$size]
                : (isset($variation[$size]) ? (int) $variation[$size] : 0);
        }

        $normalizedVariations[] = [
            'color'       => $variation['color'] ?? '',
            'sole_color'  => $variation['sole_color'] ?? '',
            'sizes'       => $sizes,
            'images'      => $variation['images'] ?? [],
        ];
    }
    $batch->variations = $normalizedVariations;

    /** 🔹 Basic data */
    $articles  = Product::all();
    $orders    = Order::all();
    $clients   = User::whereIn('category', ['wholesale', 'retail'])->get();
    $salesReps = Employee::where('role', 'Sales Rep')->get();

    /** 🔹 Decode labor assignments */
    $assignedLabors        = [];
    $assignedLaborsDates   = [];
    $assignedLaborsStatus  = []; // ✅ Worker-specific statuses

    $laborAssignments = is_array($batch->labor_assignments)
        ? $batch->labor_assignments
        : json_decode($batch->labor_assignments ?? '[]', true);

    foreach ($laborAssignments as $assignment) {
        $procId       = $assignment['process_id'];
        $empId        = $assignment['employee_id'];
        $rate         = $assignment['salary_per_day'] ?? ($assignment['rate'] ?? 0);
        $laborStatus  = strtolower($assignment['labor_status'] ?? 'pending');

        foreach ($assignment['variations'] as $vIndex => $variation) {
            // Quantities
            $assignedLabors[$procId][$empId][$vIndex] = $variation;

            // Dates
            $assignedLaborsDates[$procId][$empId][$vIndex] = [
                'start_date' => $assignment['start_date'] ?? null,
                'end_date'   => $assignment['end_date'] ?? null,
                'rate'       => $rate,
            ];

            // Status
            $assignedLaborsStatus[$procId][$empId][$vIndex] = $laborStatus;
        }
    }

    /** 🔹 Group labors by labor_type */
   /** 🔹 Load all labor employees once */
$allLabors = Employee::where('role', 'Labor')
    ->where('status', 'active')
    ->get();

$laborsByType = $allLabors->groupBy(fn($l) => strtolower(trim($l->labor_type ?? 'unknown')));


/** 🔹 Normalize and simplify text */
function normalizeWord($text) {
    return strtolower(trim(preg_replace('/[^a-z]/i', '', $text)));
}

/** 🔹 Smart dynamic matching with first-word logic */
$processLabors = [];



foreach ($batch->product->processes ?? [] as $process) {
    $processName = strtolower(trim($process->name));
    $processWords = preg_split('/[\s_-]+/', $processName); 
    $processFirst = normalizeWord($processWords[0] ?? ''); // first word like "upper", "bottom", etc.

    $matchedLabors = collect();

    foreach ($allLabors as $labor) {
        $laborName = strtolower(trim($labor->labor_type ?? ''));
        $laborWords = preg_split('/[\s_-]+/', $laborName);
        $laborFirst = normalizeWord($laborWords[0] ?? '');

        // Calculate fuzzy similarity between first words
        $similarity = 0;
        similar_text($processFirst, $laborFirst, $similarity);
        $levDistance = levenshtein($processFirst, $laborFirst);

        // ✅ Matching rules:
        if (
            $similarity >= 70 ||        // fuzzy match threshold
            $levDistance <= 2 ||        // typo tolerance (e.g. "upper" vs "uppar")
            str_starts_with($laborFirst, $processFirst) ||
            str_starts_with($processFirst, $laborFirst)
        ) {
            $matchedLabors->push($labor);
        }
    }

    // Optional fallback if nothing matched
    // if ($matchedLabors->isEmpty()) {
    //     $matchedLabors = $allLabors;
    // }

    $processLabors[$process->id] = $matchedLabors;
}


    /** 🔹 Fill unassigned labors for UI completeness */
    foreach ($batch->product->processes as $process) {
        $procId = $process->id;
        $assignedLabors[$procId] ??= [];

        foreach ($batch->variations as $vIndex => $variation) {
            foreach ($processLabors[$procId] ?? [] as $labor) {
                $empId = $labor->id;

                if (!isset($assignedLabors[$procId][$empId][$vIndex])) {
                    $assignedLabors[$procId][$empId][$vIndex] = array_fill_keys(array_keys($variation['sizes']), 0);
                    $assignedLaborsDates[$procId][$empId][$vIndex] = [
                        'start_date' => null,
                        'end_date'   => null,
                        'rate'       => null,
                    ];
                    $assignedLaborsStatus[$procId][$empId][$vIndex] = 'pending';
                }
            }
        }
    }

    /** 🔹 Override statuses with live data from employee_batch */
    $liveStatuses = DB::table('employee_batch')
        ->where('batch_id', $batchId)
        ->select('process_id', 'employee_id', 'labor_status')
        ->get();

    foreach ($liveStatuses as $record) {
        $procId = $record->process_id;
        $empId  = $record->employee_id;
        $status = strtolower($record->labor_status ?? 'pending');

        if (isset($assignedLaborsStatus[$procId][$empId])) {
            foreach ($assignedLaborsStatus[$procId][$empId] as $vIndex => $_) {
                $assignedLaborsStatus[$procId][$empId][$vIndex] = $status;
            }
        }
    }

    /** 🔹 Compute per-process statuses */
    $processStatuses = DB::table('employee_batch')
    ->select(
        'process_id',
        DB::raw("
            CASE 
                WHEN SUM(labor_status NOT IN ('completed','paid')) = 0 
                THEN 'completed' 
                ELSE 'in_progress' 
            END as status
        ")
    )
    ->where('batch_id', $batchId)
    ->groupBy('process_id')
    ->pluck('status', 'process_id')
    ->toArray();


    /** 🔹 Prepare final product structure */
    $batchArray = [
        'variations' => $normalizedVariations,
        'product' => [
            'soles'            => $batch->product->soles?->toArray() ?? [],
            'materials'        => $batch->product->materials?->toArray() ?? [],
            'processes'        => $batch->product->processes?->unique('id')->toArray() ?? [],
            'liquid_materials' => $batch->product->liquidMaterials?->toArray() ?? [],
            'image'            => $batch->product->image,
            'name'             => $batch->product->name,
            'description'      => $batch->product->description,
        ],
    ];

    return view('production.batch_flow_edit', compact(
        'batch',
        'batchArray',
        'articles',
        'orders',
        'clients',
        'salesReps',
        'assignedLabors',
        'assignedLaborsDates',
        'assignedLaborsStatus', // ✅ Reflects live DB values now
        'laborsByType',
        'processLabors',
        'processStatuses'
    ));
}



public function update(Request $request, $id)
{
    // -----------------------------
    // Load Batch with Product
    // -----------------------------
    $batch = Batch::with([
        'product.soles',
        'product.materials',
        'product.liquidMaterials',
        'product.processes'
    ])->findOrFail($id);

    if (!$batch->product) {
        return redirect()->route('batch.flow.index')
            ->with('error', 'Batch has no associated product. Please assign a valid product.');
    }

    // -----------------------------
    // Normalize worker_qty input safely
    // -----------------------------
    $workerQty = $request->input('worker_qty', []);
    foreach ($workerQty as $procId => $workers) {
        foreach ($workers as $workerId => $variations) {
            foreach ($variations as $vIndex => $sizes) {
                foreach ($sizes as $size => $value) {
                    $workerQty[$procId][$workerId][$vIndex][$size] = is_numeric($value) ? (int)$value : 0;
                }
            }
        }
    }

    // -----------------------------
    // VALIDATION — variations is now OPTIONAL on edit
    // -----------------------------
    $request->validate([
        'po_no'              => 'nullable|string|max:255',
        'batch_start_date'   => 'required|date',
        'batch_end_date'     => 'nullable|date|after_or_equal:batch_start_date',
        'order_no'           => 'nullable|string',
        'client_ids'         => 'required|array|min:1',
        'client_ids.*' => 'exists:users,id',


        // Only validate variations if they are sent (in future when editable)
        'variations'         => 'sometimes|nullable|array',
        'variations.*.color' => 'required_with:variations|string',
        'variations.*.sizes' => 'required_with:variations|array',

        'labors'             => 'nullable|array',
        'worker_qty'         => 'nullable|array',
        'worker_qty.*.*.*.*' => 'numeric|min:0',
        'start_date'         => 'nullable|array',
        'end_date'           => 'nullable|array',
    ]);

    // -----------------------------
    // Handle Variations: Use new ones if provided, otherwise keep old
    // -----------------------------
    $variations = $batch->variations; // default = current from DB

    if ($request->has('variations') && is_array($request->variations)) {
        $raw = $request->variations;

        $normalized = [];
        foreach ($raw as $v) {
            $variation = is_array($v) ? $v : [];
            $sizes = [];

            $providedSizes = $variation['sizes'] ?? [];
            if (!is_array($providedSizes)) {
                $providedSizes = [];
            }

            foreach (range(35, 44) as $size) {
                $sizes[$size] = isset($providedSizes[$size]) ? (int)$providedSizes[$size] : 0;
            }

            $normalized[] = [
                'color'      => $variation['color'] ?? '',
                'sole_color' => $variation['sole_color'] ?? $variation['color'] ?? '',
                'sizes'      => $sizes,
            ];
        }

        $variations = $normalized;

        // Recalculate total quantity
        $newQuantity = collect($variations)->sum(fn($v) => array_sum($v['sizes']));
        if ($newQuantity !== $batch->quantity) {
            $this->adjustStockOnBatchUpdate($batch, $newQuantity);
        }
    }

    // -----------------------------
    // Update basic batch details
    // -----------------------------
    $batch->update([
        'po_no'        => $request->po_no,
        'order_no'     => $request->order_no,
        'start_date'   => $request->batch_start_date,
        'end_date'     => $request->batch_end_date ?? null,
        'quantity'     => $batch->quantity, // already updated above if variations changed
        'variations'   => $variations,
    ]);

    // -----------------------------
    // Sync Clients (multiple)
    // -----------------------------
    if ($request->has('client_ids')) {
        $clientIds = array_filter($request->client_ids, fn($id) => is_numeric($id));
        $batch->clients()->sync($clientIds);
    }

    // -----------------------------
    // Update Labor Assignments (Preserve completed status)
    // -----------------------------
    $existingRecords = DB::table('employee_batch')
        ->where('batch_id', $batch->id)
        ->get()
        ->keyBy(fn($r) => "{$r->process_id}_{$r->employee_id}");

    $currentAssignments = is_string($batch->labor_assignments)
        ? json_decode($batch->labor_assignments, true)
        : ($batch->labor_assignments ?? []);

    $currentMap = collect($currentAssignments)->keyBy(fn($a) => "{$a['process_id']}_{$a['employee_id']}");

    $newAssignments = [];

    foreach ($batch->product->processes as $process) {
        $procId = $process->id;
        if (!$request->has("labors.$procId")) continue;

        foreach ($request->labors[$procId] as $workerId) {
            $workerId = (int)$workerId;
            $worker = Employee::find($workerId);
            if (!$worker) continue;

            $sizeQuantities = $workerQty[$procId][$workerId] ?? [];
            $totalQty = collect($sizeQuantities)->flatten()->sum();

            if ($totalQty <= 0) continue;

            $startDate = $request->start_date[$procId][$workerId][0] ?? null;
            $endDate   = $request->end_date[$procId][$workerId][0] ?? null;
            $key = "{$procId}_{$workerId}";

            // Preserve completed status
            $oldStatus = $existingRecords[$key]->labor_status ?? 'pending';
            $laborStatus = ($oldStatus === 'completed') ? 'completed' : 'in_progress';

            $data = [
                'batch_id'     => $batch->id,
                'process_id'   => $procId,
                'employee_id'  => $workerId,
                'quantity'     => $totalQty,
                'quantities'   => json_encode($sizeQuantities),
                'labor_rate'   => $worker->labor_amount ?? 0,
                'labor_status' => $laborStatus,
                'start_date'   => $startDate,
                'end_date'     => $endDate,
                'updated_at'   => now(),
            ];

            if (isset($existingRecords[$key])) {
                DB::table('employee_batch')
                    ->where('id', $existingRecords[$key]->id)
                    ->update($data);
            } else {
                $data['created_at'] = now();
                DB::table('employee_batch')->insert($data);
            }

            $newAssignments[] = [
                'employee_id'     => $workerId,
                'process_id'      => $procId,
                'total_quantity'  => $totalQty,
                'salary_per_day'  => $worker->labor_amount ?? 0,
                'start_date'      => $startDate,
                'end_date'        => $endDate,
                'variations'      => $sizeQuantities,
            ];
        }
    }

    // Merge old + new assignments (preserve removed workers too)
    $merged = collect($currentMap)->merge(collect($newAssignments)->keyBy(fn($a) => "{$a['process_id']}_{$a['employee_id']}"))->values()->toArray();

    $batch->labor_assignments = !empty($merged) ? json_encode($merged) : null;
    $batch->status = $batch->status === 'completed' ? 'completed' : 'in_progress';
    $batch->save();

    return redirect()->route('batch.flow.index')
        ->with('success', 'Batch and labor assignments updated successfully!');
}



    public function destroy($batchId)
{
    $batch = Batch::findOrFail($batchId);
    
    $this->reverseStockOnBatchDelete($batch);

    return redirect()->route('batch.flow.index')->with('success', 'Batch deleted and stock reversed successfully.');
}

protected function adjustStockOnBatchUpdate($batch, $newQuantity)
{
    DB::transaction(function () use ($batch, $newQuantity) {
        $product = $batch->product;
        if (!$product) {
            throw new \Exception("No product associated with batch {$batch->batch_no}.");
        }

        \Log::info("♻️ Adjusting stock safely for batch {$batch->batch_no}");

        // ✅ 1. Restore previous stock movements
        $previousMovements = StockMovement::where('batch_id', $batch->id)->get();
        foreach ($previousMovements as $movement) {
            $stock = Stock::where([
                'item_id' => $movement->item_id,
                'type' => $movement->type,
                'size' => $movement->size,
            ])->lockForUpdate()->first();

            if ($stock) {
                $restoreQty = abs($movement->change);
                $stock->qty_available += $restoreQty;
                $stock->in_transit_qty = max(0, $stock->in_transit_qty - $restoreQty);
                $stock->saveQuietly();
            }

            $movement->delete();
        }

        // ✅ 2. Handle soles (with skip for new ones)
        $variations = json_decode($batch->variations, true) ?? [];
        foreach ($variations as $variation) {
            $soleName = $variation['sole_color'] ?? $variation['color'] ?? null;
            $sizes = $variation['sizes'] ?? [];

            if (!$soleName) continue;
            $sole = Sole::where('name', $soleName)->first();
            if (!$sole) continue;

            // 🧩 Skip deduction if sole has no stock or movement history
            $hasStockMovement = StockMovement::where('item_id', $sole->id)
                ->where('type', 'sole')
                ->exists();

            $hasStockRecord = Stock::where('item_id', $sole->id)
                ->where('type', 'sole')
                ->exists();

            if (!$hasStockMovement && !$hasStockRecord) {
                \Log::info("🛑 Skipping stock deduction for new sole '{$sole->name}' (ID: {$sole->id}) - no stock history found.");
                continue;
            }

            foreach ($sizes as $size => $qty) {
                if ($qty <= 0) continue;

                $requiredQty = $qty * ($sole->qty_per_unit ?? 1);

                // 🔐 Create stock record if not found (negative allowed)
                $stock = Stock::firstOrCreate(
                    ['item_id' => $sole->id, 'type' => 'sole', 'size' => $size],
                    ['qty_available' => 0, 'in_transit_qty' => 0]
                );

                $oldQty = $stock->qty_available;
                $stock->qty_available -= $requiredQty;
                $stock->in_transit_qty += $requiredQty;
                $stock->saveQuietly();

                $desc = "Deducted {$requiredQty} for batch {$batch->batch_no}";
                if ($oldQty < $requiredQty) {
                    $desc .= " (⚠️ went negative: shortage of " . abs($stock->qty_available) . ")";
                }

                StockMovement::create([
                    'batch_id' => $batch->id,
                    'item_id' => $sole->id,
                    'type' => 'sole',
                    'size' => $size,
                    'change' => -$requiredQty,
                    'qty_after' => $stock->qty_available,
                    'description' => $desc,
                ]);
            }
        }

        // 🚫 3. Skip materials for now (unchanged)
        /*
        foreach ($product->materials as $material) {
            $requiredQty = $newQuantity * ($material->pivot->quantity_used ?? 1);
            $stock = Stock::firstOrCreate(
                ['item_id' => $material->id, 'type' => 'material'],
                ['qty_available' => 0]
            );

            $stock->qty_available -= $requiredQty;
            $stock->saveQuietly();

            StockMovement::create([
                'batch_id' => $batch->id,
                'item_id' => $material->id,
                'type' => 'material',
                'change' => -$requiredQty,
                'qty_after' => $stock->qty_available,
                'description' => "Adjusted material for batch {$batch->batch_no}",
            ]);
        }
        */

        // ✅ 4. Handle liquids (negative allowed)
        foreach ($product->liquidMaterials as $liquid) {
            $requiredQty = $newQuantity * ($liquid->pivot->quantity_used ?? 1);

            $stock = Stock::firstOrCreate(
                ['item_id' => $liquid->id, 'type' => 'liquid'],
                ['qty_available' => 0]
            );

            $oldQty = $stock->qty_available;
            $stock->qty_available -= $requiredQty;
            $stock->saveQuietly();

            $desc = "Deducted {$requiredQty} for batch {$batch->batch_no}";
            if ($oldQty < $requiredQty) {
                $desc .= " (⚠️ went negative: shortage of " . abs($stock->qty_available) . ")";
            }

            StockMovement::create([
                'batch_id' => $batch->id,
                'item_id' => $liquid->id,
                'type' => 'liquid',
                'change' => -$requiredQty,
                'qty_after' => $stock->qty_available,
                'description' => $desc,
            ]);
        }

        \Log::info("✅ Stock adjustment completed safely for batch {$batch->batch_no}");
    });
}


    // Reverse stock on batch delete
public function reverseStockOnBatchDelete($batch)
    {
        DB::transaction(function () use ($batch) {
            $movements = StockMovement::where('batch_id', $batch->id)->orderBy('id', 'desc')->get();

            foreach ($movements as $movement) {
                $stock = Stock::where('item_id', $movement->item_id)
                    ->where('type', $movement->type)
                    ->where('size', $movement->size)
                    ->lockForUpdate()
                    ->first();

                if ($stock) {
                    $newQty = $stock->qty_available - $movement->change;
                    if ($newQty < 0) {
                        throw new \Exception("Cannot reverse stock for {$movement->type} (Item ID: {$movement->item_id}) below zero. Available: {$stock->qty_available}, Change: {$movement->change}");
                    }
                    $stock->qty_available = $newQty;
                    $stock->save();
                }
            }

            DB::table('stock_movements')->where('batch_id', $batch->id)->delete();
            DB::table('batch_labor_usage')->where('batch_id', $batch->id)->delete();
            DB::table('employee_batch')->where('batch_id', $batch->id)->delete();
            $batch->delete();

            \Log::info("Stock reversed and batch {$batch->batch_no} deleted");
        });
    }



    // Print batch details
    public function print(Batch $batch)
    {
        if (!$batch->product) {
            return redirect()->route('batch.flow.index')
                           ->with('error', 'Batch has no associated product. Please assign a valid product.');
        }

        $employeeAssignments = $batch->workers()->get()->groupBy('employee_id');

        return view('batch.print', compact('batch', 'employeeAssignments'));
    }

    // Update individual worker process status
// ✅ Update individual worker process status
public function updateWorkerProcessStatus($batchId, $workerId, Request $request)
{
    $request->validate([
        'status' => 'required|in:pending,in progress,completed',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
    ]);

    // 🔹 Build base query
    $query = DB::table('employee_batch')
        ->where('batch_id', $batchId)
        ->where('employee_id', $workerId);

    // 🔹 Filter by process_id if provided
    if ($request->filled('process_id')) {
        $query->where('process_id', $request->process_id);
    }

    // 🔹 Update the worker’s record
    $updated = $query->update([
        'labor_status' => $request->status,
        'start_date' => $request->start_date ?? DB::raw('start_date'),
        'end_date' => $request->end_date ?? DB::raw('end_date'),
        'updated_at' => now(),
    ]);

    if (!$updated) {
        return back()->with('error', 'No matching worker record found or nothing changed.');
    }

    // ✅ Step 1: If process_id present, check if all workers for this process are done
    if ($request->filled('process_id')) {
        $processId = $request->process_id;

        $remaining = DB::table('employee_batch')
            ->where('batch_id', $batchId)
            ->where('process_id', $processId)
            ->where('labor_status', '!=', 'completed')
            ->count();

        if ($remaining === 0) {
            // ✅ Mark the process pivot (batch_process) as completed
            DB::table('batch_process')
                ->where('batch_id', $batchId)
                ->where('process_id', $processId)
                ->update([
                    'status' => 'completed',
                    'updated_at' => now(),
                ]);
        }
    }

    // ✅ Step 2: If all processes are completed, mark batch as completed
    $pendingProcesses = DB::table('batch_process')
        ->where('batch_id', $batchId)
        ->where('status', '!=', 'completed')
        ->count();

    if ($pendingProcesses === 0) {
        DB::table('batches')
            ->where('id', $batchId)
            ->update([
                'status' => 'completed',
                'updated_at' => now(),
            ]);
    }

    return back()->with('success', 'Worker process status updated successfully!');
}


// Show batch status update form
    public function updateStatus()
    {
        $batches = Batch::with(['product', 'workers'])->get();
        $processes = Process::all(); // for dropdown

        return view('batch.update_status', compact('batches', 'processes'));
    }

public function saveStatus(Request $request)
{
    \Log::info('🔹 Incoming Worker Status Request', ['payload' => $request->all()]);

    $selectedBatchId = $request->input('selected_batch_id');
    $selectedWorkers = $request->input('selected_workers', []);
    $allWorkers = $request->input('workers', []);

    if (empty($selectedWorkers)) {
        return back()->with('error', '⚠️ Please select at least one worker.');
    }

    $affectedBatchIds = [];
    $completedBatches = [];
    $totalUpdates = 0;
    $redirectToIndex = false;

    foreach ($selectedWorkers as $employeeId) {
        if (!isset($allWorkers[$employeeId])) continue;
        $data = $allWorkers[$employeeId];

        $batchId   = $data['batch_id'] ?? $selectedBatchId;
        $processId = $data['process_id'] ?? null;
        $status    = strtolower(trim($data['labor_status'] ?? 'pending'));

        if (!$batchId || !$processId) {
            \Log::warning("⚠️ Missing batch_id or process_id for employee {$employeeId}");
            continue;
        }

        $affectedBatchIds[$batchId] = true;

        // 🔹 Prepare update data
        $updateData = [
            'labor_status' => $status,
            'updated_at'   => now(),
        ];

        if (!empty($data['quantity'])) {
            $updateData['quantity'] = (int) $data['quantity'];
        }
        if (!empty($data['labor_rate'])) {
            $updateData['labor_rate'] = (float) $data['labor_rate'];
        }

        // 🔹 Update employee_batch pivot table
        $rows = DB::table('employee_batch')
            ->where('employee_id', $employeeId)
            ->where('batch_id', $batchId)
            ->where('process_id', $processId)
            ->update($updateData);

        if ($rows > 0) {
            $totalUpdates += $rows;
            \Log::info('✅ Employee_Batch Updated', [
                'employee_id' => $employeeId,
                'batch_id'    => $batchId,
                'process_id'  => $processId,
                'status'      => $status,
                'affected_rows' => $rows,
            ]);
        } else {
            \Log::warning('❌ Employee_Batch update failed', [
                'employee_id' => $employeeId,
                'batch_id'    => $batchId,
                'process_id'  => $processId,
                'status'      => $status,
            ]);
        }

        // 🔹 Update production_processes
        $processUpdated = DB::table('production_processes')
            ->where('batch_id', $batchId)
            ->where('process_id', $processId)
            ->update([
                'status'     => $status,
                'updated_at' => now(),
            ]);

        \Log::info('🔄 Production_Process Updated', [
            'batch_id'     => $batchId,
            'process_id'   => $processId,
            'new_status'   => $status,
            'affected_rows'=> $processUpdated,
        ]);
    }

    // 🔹 Recalculate batch status after all updates
    $lastUpdatedBatchId = null;

    foreach (array_keys($affectedBatchIds) as $batchId) {
        $statuses = DB::table('employee_batch')
            ->where('batch_id', $batchId)
            ->pluck('labor_status')
            ->map(fn($s) => strtolower(trim($s)))
            ->toArray();

        $total = count($statuses);
        $completed = collect($statuses)->filter(fn($s) => in_array($s, ['completed', 'paid']))->count();
        $inProgress = collect($statuses)->filter(fn($s) => $s === 'in_progress')->count();

        $batchStatus = match (true) {
            $total > 0 && $completed === $total => 'completed',
            $completed > 0 || $inProgress > 0   => 'in_progress',
            default                             => 'pending',
        };

        DB::table('batches')
            ->where('id', $batchId)
            ->update([
                'status' => $batchStatus,
                'updated_at' => now(),
            ]);

        \Log::info('📦 Batch Status Recalculated', [
            'batch_id' => $batchId,
            'new_status' => $batchStatus,
            'completed' => $completed,
            'in_progress' => $inProgress,
            'total' => $total,
        ]);

        $lastUpdatedBatchId = $batchId;

        if ($batchStatus === 'completed') {
            $redirectToIndex = true;
            $batchNo = DB::table('batches')->where('id', $batchId)->value('batch_no');
            $completedBatches[] = $batchNo;
        }
    }

    $summary = "{$totalUpdates} worker(s) updated successfully.";

    // ✅ Redirect to index if completed
    if ($redirectToIndex) {
        return redirect()
            ->route('batch.flow.index')
            ->with('success', "✅ {$summary} Completed batch(es): " . implode(', ', $completedBatches));
    }

    // ✅ Otherwise, go to edit page of last updated batch
    if ($lastUpdatedBatchId) {
        return redirect()
            ->route('batch.flow.edit', ['batch' => $lastUpdatedBatchId])
            ->with('success', "✅ {$summary} Batch still in progress. Redirected to edit page.");
    }

    // ✅ Fallback
    return redirect()
        ->back()
        ->with('success', "✅ {$summary}");
}



public function card()
{
    // Example: fetch batches or data for card view
    $batches = Batch::with('product')->orderBy('created_at', 'desc')->get();

    return view('production.batch_flow_card', compact('batches'));
}




}