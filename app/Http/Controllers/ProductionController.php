<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\RawMaterial;
use App\Models\ProductionOrder;
use App\Models\Product;
use App\Models\Batch;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Employee;

class ProductionController extends Controller
{


public function dashboard()
{
    // ---------------------------------------
    // 1. Low Stock Alerts
    // ---------------------------------------
    $lowRawMaterials = DB::table('raw_materials')->where('quantity', '<', 10)->get();
    $lowLiquidMaterials = DB::table('liquid_materials')->where('quantity', '<', 10)->get();
    $lowSoles = DB::table('soles')->where('quantity', '<', 10)->get();

    // ---------------------------------------
    // 2. KPI CARDS
    // ---------------------------------------
    $totalBatches = DB::table('batches')->count();

    $todayBatches = DB::table('batches')
        ->whereDate('start_date', today())
        ->count();

    $inProgressProcesses = DB::table('production_processes')
        ->where('status', 'in_progress')
        ->count();

    $completedProcesses = DB::table('production_processes')
        ->where('status', 'completed')
        ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->count();

    $delayedProcesses = DB::table('production_processes')
        ->where('status', '!=', 'completed')
        ->count();
    
    $batchStatusSummary = [
    'Pending' => DB::table('batches')->where('status', 'pending')->count(),
    'In Progress' => DB::table('batches')->where('status', 'in_progress')->count(),
    'Completed' => DB::table('batches')->where('status', 'completed')->count(),
    'Delayed' => DB::table('batches')
                    ->where('status', '!=', 'completed')
                    ->whereDate('start_date', '<', now())
                    ->count(),
];


    // ---------------------------------------
    // 3. CLEANED Process Summary Chart
    // ---------------------------------------
    $rawProcesses = DB::table('production_processes')
        ->join('processes', 'processes.id', '=', 'production_processes.process_id')
        ->select('processes.name')
        ->get()
        ->pluck('name')
        ->toArray();

    $cleaned = [];

    foreach ($rawProcesses as $name) {
        $name = strtolower(trim($name));

        if (str_contains($name, 'bottom')) {
            $cleaned['Bottom'] = ($cleaned['Bottom'] ?? 0) + 1;
        } 
        elseif (str_contains($name, 'upper')) {
            $cleaned['Upper'] = ($cleaned['Upper'] ?? 0) + 1;
        } 
        elseif (str_contains($name, 'finish')) {
            $cleaned['Finishing'] = ($cleaned['Finishing'] ?? 0) + 1;
        }
        else {
            // Use original name with proper capitalization
            $cleaned[ucfirst($name)] = ($cleaned[ucfirst($name)] ?? 0) + 1;
        }
    }

    $processSummary = $cleaned;

    // ---------------------------------------
    // 4. Recent Batches
    // ---------------------------------------
    $recentBatches = \App\Models\Batch::with('product')
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get();

    // ---------------------------------------
    // 5. Send Data To View
    // ---------------------------------------
    return view('production.dashboard', compact(
        'lowRawMaterials',
        'lowLiquidMaterials',
        'lowSoles',

        'todayBatches',
        'totalBatches',
        'inProgressProcesses',
        'completedProcesses',
        'delayedProcesses',

        'processSummary',
        'batchStatusSummary',
        'recentBatches'
    ));
}


public function updateStatus(Request $request, $batchId)
{
    // Validate the status input
    $request->validate([
        'status' => 'required|in:pending,in progress,completed',
    ]);

    try {
        $batch = \App\Models\Batch::findOrFail($batchId);
        $batch->status = $request->status;
        $batch->save();

        return redirect()->back()->with('success', 'Batch status updated successfully!');
    } catch (\Exception $e) {
        \Log::error('Error updating batch status: '.$e->getMessage(), [
            'batch_id' => $batchId,
            'request' => $request->all()
        ]);

        return redirect()->back()->with('error', 'Failed to update batch status.');
    }
}


public function batchFlow()
    {
        // Example: Fetch counts of batches at different stages
        $rawMaterial = DB::table('batches')->where('stage', 'Raw Material')->count();
        $cutting = DB::table('batches')->where('stage', 'Cutting')->count();
        $stitching = DB::table('batches')->where('stage', 'Stitching')->count();
        $finishing = DB::table('batches')->where('stage', 'Finishing')->count();
        $readyStock = DB::table('batches')->where('stage', 'Ready Stock')->count();

        return view('production.batch_flow', compact(
            'rawMaterial',
            'cutting',
            'stitching',
            'finishing',
            'readyStock'
        ));
    }



    // Show all processes
    public function process()
    {
        $processes = ProductionProcess::orderBy('created_at', 'desc')->paginate(10);
        return view('production.process', compact('processes'));
    }

    // Show create form
    public function createProcess()
    {
        return view('production.create_process');
    }

    // Store new process
    public function storeProcess(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stage' => 'required|string|max:255',
            'status' => 'required|in:Pending,In Progress,Completed',
            'operator' => 'nullable|string|max:255'
        ]);

        ProductionProcess::create($request->all());
        return redirect()->route('production.process')->with('success', 'Process created successfully!');
    }

public function storeLabor(Request $request)
{
    \Log::info('Store Labor Hit', $request->all());

   $request->validate([
    'batch_no'   => 'required|string',
    'article_id' => 'required|exists:products,id',
    'labors'     => 'nullable|array',
    'worker_qty' => 'nullable|array',
    'start_date' => 'nullable|array',
    'end_date'   => 'nullable|array',
]);


    $batch = Batch::firstOrCreate(
        ['batch_no' => $request->batch_no],
        [
            'name'       => Product::find($request->article_id)->name ?? 'N/A',
            'product_id' => $request->article_id,
            'quantity'   => 0,
            'status'     => 'pending',
            'priority'   => 'normal',
            'po_no'      => $request->po_no ?? null,
            'created_by' => auth()->user()->name,
        ]
    );

    try {
        $batch->workers()->detach();
        \Log::info("Cleared previous labor assignments for batch {$batch->id}");

        $assignments = [];
        $syncData = [];

        /** 🔹 Load all labors dynamically */
        $allLabors = Employee::where('role', 'Labor')
            ->where('status', 'active')
            ->get();

        /** 🔹 Helper to normalize text */
        $normalize = fn($text) => strtolower(trim(preg_replace('/[^a-z]/i', '', $text)));

        /** 🔹 Helper: fuzzy match between process & labor type */
        $findMatchingLabors = function ($processName) use ($allLabors, $normalize) {
            $processName = strtolower(trim($processName));
            $processWords = preg_split('/[\s_-]+/', $processName);
            $processFirst = $normalize($processWords[0] ?? '');

            $matched = collect();

            foreach ($allLabors as $labor) {
                $laborWords = preg_split('/[\s_-]+/', strtolower(trim($labor->labor_type ?? '')));
                $laborFirst = $normalize($laborWords[0] ?? '');

                $similarity = 0;
                similar_text($processFirst, $laborFirst, $similarity);
                $distance = levenshtein($processFirst, $laborFirst);

                if (
                    $similarity >= 70 ||
                    $distance <= 2 ||
                    str_starts_with($laborFirst, $processFirst) ||
                    str_starts_with($processFirst, $laborFirst)
                ) {
                    $matched->push($labor);
                }
            }

            // Optional fallback if none matched
            if ($matched->isEmpty()) {
                $matched = $allLabors;
            }

            return $matched;
        };

        /** 🔹 Loop through assigned labors */
       // ✅ Prevent crash if no labors selected
if (!empty($request->labors) && is_array($request->labors)) {
    foreach ($request->labors as $process_id => $workers) {
        $process = \App\Models\ProductionProcess::find($process_id);
        if (!$process) {
            \Log::warning("Process ID {$process_id} not found, skipping...");
            continue;
        }

        // 🔹 Fuzzy match possible labors for this process
        $matchedLabors = $findMatchingLabors($process->name);
        $rate = $process->labor_rate ?? 0;

        foreach ((array) $workers as $worker_id) {
            // If worker not valid for process name, try to auto-fix
            if (!$matchedLabors->contains('id', $worker_id)) {
                $autoMatch = $matchedLabors->first();
                if ($autoMatch) {
                    \Log::info("Auto-matched worker {$worker_id} to {$autoMatch->id} for process {$process->name}");
                    $worker_id = $autoMatch->id;
                }
            }

            $workerVariations = $request->worker_qty[$process_id][$worker_id] ?? [];
            $totalQty = 0;
            $variationWise = [];

            foreach ($workerVariations as $variationIndex => $sizes) {
                if (!is_array($sizes)) {
                    $sizes = $workerVariations;
                    $variationIndex = 0;
                }

                $sizes = array_map('intval', (array) $sizes);
                $variationQty = array_sum($sizes);

                if ($variationQty > 0) {
                    $variationWise[$variationIndex] = $sizes;
                    $totalQty += $variationQty;
                }
            }

            if ($totalQty <= 0) {
                \Log::info("Skipping worker {$worker_id} for process {$process_id} because total quantity is 0");
                continue;
            }

            $startDate = $request->start_date[$process_id][$worker_id] ?? null;
            $endDate   = $request->end_date[$process_id][$worker_id] ?? null;

            // ✅ Optional: if start date is empty, skip gracefully instead of returning error
            if (empty($startDate)) {
                \Log::warning("Start date missing for worker {$worker_id} in process {$process->name}, skipping assignment.");
                continue;
            }

            $syncData[$worker_id][$process_id] = [
                'process_id' => $process_id,
                'quantity'   => $totalQty,
                'labor_rate' => $rate,
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $assignments[] = [
                'employee_id'    => $worker_id,
                'process_id'     => $process_id,
                'total_quantity' => $totalQty,
                'salary_per_day' => $rate,
                'start_date'     => $startDate,
                'end_date'       => $endDate,
                'variations'     => $variationWise,
            ];

            \Log::info("✅ Prepared assignment for worker {$worker_id} (process: {$process->name})", [
                'totalQty' => $totalQty,
                'variations' => $variationWise
            ]);
        }
    }
} else {
    \Log::info('⚠️ No labors selected — skipping assignment.');
}


        /** 🔹 Save all pivot data */
        foreach ($syncData as $worker_id => $processes) {
            foreach ($processes as $process_id => $data) {
                $batch->workers()->syncWithoutDetaching([$worker_id => $data]);
            }
        }

        /** 🔹 Save assignment data into JSON column */
        $batch->labor_assignments = json_encode($assignments);
        $batch->status = 'in_progress';
        $batch->save();

        \Log::info("Batch {$batch->id} saved successfully with fuzzy-matched labor assignments");

        return redirect()->route('batch.flow.index')
                         ->with('success', 'Batch and labors saved successfully!');
    } catch (\Exception $e) {
        \Log::error("Error saving labors for batch {$batch->id}: {$e->getMessage()}", [
            'request' => $request->all(),
            'trace'   => $e->getTraceAsString()
        ]);

        return back()->with('error', 'Something went wrong while saving labors. Check logs.');
    }
}





public function assignLabor($article, Request $request)
{
    $product = Product::with('processes')->findOrFail($article);
    $workers = Employee::where('role', 'Labor')->get();

    // 1️⃣ Create a batch record or get existing
    $batch = Batch::firstOrCreate(
        ['batch_no' => $request->batch_no ?? 'BATCH-' . date('Ymd') . '-TEMP'], 
        [
            'name'       => $product->name,
            'product_id' => $product->id,
            'quantity'   => 0,
            'status'     => 'pending',
            'priority'   => 'normal',
            'created_by' => auth()->user()->name,
            'po_no'      => $request->po_no ?? null,
        ]
    );

    return view('production.labor_assignment', compact('product', 'workers', 'batch'));
}





    // Show edit form
    public function editProcess($id)
    {
        $process = ProductionProcess::findOrFail($id);
        return view('production.edit_process', compact('process'));
    }

    // Update process
    public function updateProcess(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stage' => 'required|string|max:255',
            'status' => 'required|in:Pending,In Progress,Completed',
            'operator' => 'nullable|string|max:255'
        ]);

        $process = ProductionProcess::findOrFail($id);
        $process->update($request->all());
        return redirect()->route('production.process')->with('success', 'Process updated successfully!');
    }

    // Delete process
    public function deleteProcess($id)
    {
        $process = ProductionProcess::findOrFail($id);
        $process->delete();
        return redirect()->route('production.process')->with('success', 'Process deleted successfully!');
    }

}
