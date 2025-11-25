<?php

namespace App\Http\Controllers;
use App\Models\Batch;
use App\Models\StockArrival;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Stock;
use App\Models\User;
use App\Models\RawMaterial;
use App\Models\LiquidMaterial;
use App\Models\Sole;
use Illuminate\Support\Facades\DB;

class RawMaterialController extends Controller
{
    // Show all products with related soles, materials, and liquids
    public function index()
    {
        // 1️⃣ Load all products
        $products = Product::with(['soles', 'materials', 'liquidMaterials'])->get();
        $suppliers = Supplier::all(['id', 'name']);

        // 2️⃣ Pre-load sole stock and arrivals (unchanged)
        $allSoleStocks = Stock::where('type', 'sole')
            ->get()
            ->groupBy('item_id')
            ->map(fn($stocksByItem) => $stocksByItem->keyBy('size'));

        $allPendingArrivals = StockArrival::where('type', 'sole')
            ->whereNull('received_at')
            ->get()
            ->groupBy('item_id')
            ->map(fn($arrivalsByItem) => $arrivalsByItem->groupBy('size'));

        $calculateSoleQuantities = function ($soleId, $price = 0) {
            $sole = Sole::find($soleId);
            if (!$sole)
                return null;

            $price = floatval($price ?: ($sole->price ?? 0));

            // ✅ Always include sizes 34–44 (was 35–44 before)
            $sizeKeys = range(34, 44);

            // 🔍 Check pending arrivals (in-transit)
            $hasArrival = StockArrival::where('item_id', $soleId)
                ->where('type', 'sole')
                ->exists();

            // 🔍 Detect real stock movements
            $hasStockMovement = StockMovement::where('item_id', $soleId)
                ->where('type', 'sole')
                ->where(function ($q) {
                    $q->where('change', '!=', 0)
                        ->orWhere('qty_after', '!=', 0);
                })
                ->exists();

            // 🔍 Fetch valid stock entries with non-null size
            $validStock = Stock::where('item_id', $soleId)
                ->where('type', 'sole')
                ->whereNotNull('size')
                ->get();

            $hasRealStock = $hasStockMovement && $validStock->contains(fn($s) => floatval($s->qty_available) > 0);

            // 🔍 Decode soles.sizes_qty JSON
            $sizesData = is_string($sole->sizes_qty)
                ? json_decode($sole->sizes_qty, true)
                : ($sole->sizes_qty ?? []);

            if (!is_array($sizesData)) {
                $sizesData = [];
            }

            $totalSizesQty = collect($sizesData)
                ->flatten()
                ->filter(fn($v) => is_numeric($v))
                ->sum();

            // ✅ Check if sole is completely new / empty
            $isEmptySole = (
                !$hasArrival &&
                !$hasStockMovement &&
                !$hasRealStock &&
                ($totalSizesQty === 0)
            );

            \Log::info('🧩 Sole Check', [
                'sole_id' => $soleId,
                'name' => $sole->name ?? 'N/A',
                'hasArrival' => $hasArrival,
                'hasStockMovement' => $hasStockMovement,
                'hasRealStock' => $hasRealStock,
                'totalSizesQty' => $totalSizesQty,
                'isEmptySole' => $isEmptySole,
            ]);

            // 🧩 Fallback to soles.sizes_qty when no stock/arrivals
            if ($isEmptySole || !$hasStockMovement) {
                $sizesFromSole = collect($sizesData)
                    ->map(fn($qty) => is_numeric($qty) ? (float) $qty : 0)
                    ->toArray();

                $available_per_size = [];
                $total_available = 0;

                foreach ($sizeKeys as $size) {
                    $qty = floatval($sizesFromSole[$size] ?? 0);
                    $available_per_size[$size] = [
                        'qty_available' => $qty,
                        'in_transit' => 0,
                        'effective_qty' => $qty,
                        'total_price' => round($qty * $price, 2),
                    ];
                    $total_available += $qty;
                }

                return [
                    'sizes_qty' => $available_per_size,
                    'available_qty' => round($total_available, 2),
                    'in_transit_qty' => 0,
                    'total_quantity' => round($total_available, 2),
                    'total_price' => round($total_available * $price, 2),
                    'per_unit_price' => $price,
                    'per_unit_price_text' => $price > 0 ? number_format($price, 2) . ' /unit' : '',
                    'is_new' => false,
                ];
            }

            // 📦 For active soles or those with arrivals → compute stock
            $stocks = $validStock->keyBy(fn($s) => intval($s->size));

            $pendingArrivals = StockArrival::where('item_id', $soleId)
                ->where('type', 'sole')
                ->whereNull('received_at')
                ->get()
                ->groupBy(fn($p) => intval($p->size));

            $available_per_size = [];
            $total_available = $total_in_transit = 0;

            foreach ($sizeKeys as $size) {
                $stockQty = floatval(optional($stocks->get($size))->qty_available ?? 0);
                $pendingQty = floatval($pendingArrivals->get($size, collect())->sum('quantity'));

                $available_per_size[$size] = [
                    'qty_available' => round($stockQty, 2),
                    'in_transit' => round($pendingQty, 2),
                    'effective_qty' => round($stockQty + $pendingQty, 2),
                    'total_price' => round($stockQty * $price, 2),
                ];

                $total_available += $stockQty;
                $total_in_transit += $pendingQty;
            }

            return [
                'sizes_qty' => $available_per_size,
                'available_qty' => round($total_available, 2),
                'in_transit_qty' => round($total_in_transit, 2),
                'total_quantity' => round($total_available + $total_in_transit, 2),
                'total_price' => round($total_available * $price, 2),
                'per_unit_price' => $price,
                'per_unit_price_text' => $price > 0 ? number_format($price, 2) . ' /unit' : '',
                'is_new' => false,
            ];
        };


        // 4️⃣ Process soles (unchanged)
        $allSoleCalculations = collect();
        $processedSoleIds = [];

        foreach ($products as $product) {
            $product->soles_list = collect();
            foreach ($product->soles ?? [] as $sole) {
                if (in_array($sole->id, $processedSoleIds))
                    continue;
                $processedSoleIds[] = $sole->id;
                $quantities = $calculateSoleQuantities($sole->id, $sole->price ?? 0);
                $soleData = (object) array_merge([
                    'id' => $sole->id,
                    'name' => $sole->name,
                    'color' => $sole->color,
                    'price' => $sole->price ?? 0,
                    'type' => 'Sole'
                ], $quantities);
                $allSoleCalculations->put($sole->id, $soleData);
                $product->soles_list->push($soleData);
            }
        }

        $independentSoles = Sole::whereNotIn('id', $processedSoleIds)->get()->map(function ($sole) use ($calculateSoleQuantities, &$allSoleCalculations, &$processedSoleIds) {
            $processedSoleIds[] = $sole->id;
            $quantities = $calculateSoleQuantities($sole->id, $sole->price ?? 0);
            $soleData = (object) array_merge([
                'id' => $sole->id,
                'name' => $sole->name,
                'color' => $sole->color,
                'price' => $sole->price ?? 0,
                'type' => 'Sole'
            ], $quantities);
            $allSoleCalculations->put($sole->id, $soleData);
            return $soleData;
        });

        // 5️⃣ Clean materials logic to avoid duplicates
        $usedMaterialIds = collect();

        foreach ($products as $product) {
            $materials = $product->materials()
                ->withPivot('quantity_used')
                ->get()
                ->groupBy('id')  // Group materials by ID to consolidate duplicates
                ->map(function ($groupedMaterials) {
                    $totalQuantityUsed = $groupedMaterials->sum(fn($m) => floatval($m->pivot->quantity_used ?? 0));
                    $m = $groupedMaterials->first();

                    $latestMovement = StockMovement::where('item_id', $m->id)
                        ->where('type', 'material')
                        ->orderByDesc('created_at')
                        ->first();

                    $stock = Stock::where('item_id', $m->id)
                        ->where('type', 'material')
                        ->first();

                    $available_qty = $latestMovement ? floatval($latestMovement->qty_after) : floatval($m->quantity ?? 0);
                    $in_transit_qty = $stock ? floatval($stock->in_transit_qty ?? 0) : 0;

                    return (object) [
                        'id' => $m->id,
                        'name' => $m->name,
                        'color' => $m->color ?? '-',
                        'unit' => $m->unit ?? 'metre',
                        'price' => floatval($m->price ?? 0),
                        'per_unit_length' => floatval($m->per_unit_length ?? 0),
                        'quantity_used' => round($totalQuantityUsed, 2),
                        'available_qty' => round($available_qty, 2),
                        'in_transit_qty' => round($in_transit_qty, 2),
                        'total_quantity' => round($available_qty + $in_transit_qty, 2),
                        'total_price' => round(($m->price ?? 0) * $available_qty, 2),
                        'type' => 'Material',
                    ];
                })
                ->values(); // Reset keys for UI

            $product->materials_list = $materials;
            $usedMaterialIds = $usedMaterialIds->merge($materials->pluck('id'));
        }

        // Remove duplicates
        $usedMaterialIds = $usedMaterialIds->unique();

        // Independent materials
        $independentMaterials = RawMaterial::where('type', 'Material')
            ->whereNotIn('id', $usedMaterialIds)
            ->whereNull('product_id')
            ->get()
            ->unique('id')
            ->map(function ($m) {
                $latestMovement = StockMovement::where('item_id', $m->id)
                    ->where('type', 'material')
                    ->latest()
                    ->first();

                $stock = Stock::where('item_id', $m->id)
                    ->where('type', 'material')
                    ->first();

                $available_qty = $latestMovement ? floatval($latestMovement->qty_after) : floatval($m->quantity ?? 0);
                $in_transit_qty = $stock ? floatval($stock->in_transit_qty ?? 0) : 0;

                return (object) [
                    'id' => $m->id,
                    'name' => $m->name,
                    'color' => $m->color ?? '-',
                    'unit' => $m->unit ?? 'metre',
                    'price' => floatval($m->price ?? 0),
                    'per_unit_length' => floatval($m->per_unit_length ?? 0),
                    'quantity_used' => 0,
                    'available_qty' => round($available_qty, 2),
                    'in_transit_qty' => round($in_transit_qty, 2),
                    'total_quantity' => round($available_qty + $in_transit_qty, 2),
                    'total_price' => round(($m->price ?? 0) * $available_qty, 2),
                    'type' => 'Material',
                ];
            });

        // 6️⃣ Liquids (same)
        $allProductLiquidIds = $products->pluck('liquidMaterials')->flatten()->pluck('id')->unique()->toArray();
        $independentLiquids = LiquidMaterial::whereNotIn('id', $allProductLiquidIds)
            ->get()
            ->map(function ($l) {
                $latestMovement = StockMovement::where('item_id', $l->id)->where('type', 'liquid')->latest()->first();
                $available_qty = $latestMovement ? floatval($latestMovement->qty_after) : floatval($l->quantity ?? 0);
                $stock = Stock::where('item_id', $l->id)->where('type', 'liquid')->first();
                $in_transit_qty = $stock ? floatval($stock->in_transit_qty ?? 0) : 0;
                return (object) [
                    'id' => $l->id,
                    'name' => $l->name,
                    'color' => $l->color ?? '-',
                    'price' => $l->price ?? 0,
                    'available_qty' => $available_qty,
                    'in_transit_qty' => $in_transit_qty,
                    'total_quantity' => $available_qty,
                    'total_price' => ($l->price ?? 0) * $available_qty,
                    'type' => 'Liquid Material'
                ];
            });

        // 7️⃣ Add independent items group
        if ($independentSoles->isNotEmpty() || $independentMaterials->isNotEmpty() || $independentLiquids->isNotEmpty()) {
            $products->push((object) [
                'id' => null,
                'name' => 'Independent Items',
                'soles_list' => $independentSoles->values(),
                'materials_list' => $independentMaterials->values(),
                'liquids_list' => $independentLiquids->values(),
            ]);
        }

        // 8️⃣ Stock arrivals
        try {
            $stockArrivals = StockArrival::with(['sole', 'supplier'])
                ->where('type', 'sole')
                ->whereNull('received_at')
                ->get()
                ->map(fn($arrival) => [
                    'id' => $arrival->id,
                    'name' => $arrival->sole->name ?? 'N/A',
                    'article_no' => $arrival->article_no ?? 'N/A',
                    'color' => $arrival->sole->color ?? $arrival->color ?? 'N/A',
                    'size' => $arrival->size,
                    'qty' => $arrival->quantity,
                    'quantity' => $arrival->quantity,
                    'item_id' => $arrival->item_id,
                    'supplier' => $arrival->supplier ? ['id' => $arrival->supplier->id, 'name' => $arrival->supplier->name] : null,
                    'reason' => $arrival->reason,
                    'received_at' => null,
                ]);
        } catch (\Exception $e) {
            $stockArrivals = collect([]);
            \Log::error('Error loading stock arrivals: ' . $e->getMessage());
        }



        return view('raw-materials.index', compact('products', 'suppliers', 'stockArrivals'));
    }

    // Restock existing item (DYNAMIC: Validates sizes against sole's initial entered sizes_qty)
    public function restock(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:Sole,Material,Liquid Material',
            'quantity' => 'required_if:type,Material,Liquid Material|numeric|min:0',
            'sizes_qty' => 'required_if:type,Sole|array',
            'sizes_qty.*' => 'nullable|numeric|min:0',
            'reason' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
        ]);

        $type = $request->input('type');
        $sizes_qty = $request->input('sizes_qty', []);
        $quantity = floatval($request->input('quantity', 0));
        $reason = $request->input('reason');
        $reference = $request->input('reference');
        $supplier_id = $request->input('supplier_id');

        DB::beginTransaction();
        try {
            $stockArrivals = [];

            switch ($type) {
                case 'Sole':
                    $item = Sole::findOrFail($id);

                    // All valid sizes: 35 to 44
                    $validSizeKeys = range(35, 44);

                    // Filter incoming request sizes: only numeric > 0
                    $sizes_qty = array_filter($sizes_qty, fn($q) => floatval($q) > 0);

                    if (empty($sizes_qty)) {
                        throw new \Exception('No valid sizes provided for restock.');
                    }

                    foreach ($sizes_qty as $size => $qty) {
                        $size = intval($size);

                        if (!in_array($size, $validSizeKeys)) {
                            \Log::warning("Invalid restock size {$size} for sole ID {$item->id}");
                            continue;
                        }

                        $qty = floatval($qty);
                        if ($qty <= 0)
                            continue; // skip zero quantities

                        // Create stock arrival
                        StockArrival::create([
                            'item_id' => $item->id,
                            'type' => 'sole',
                            'color' => $item->color,
                            'size' => $size,
                            'quantity' => $qty,
                            'status' => 'pending',
                            'reason' => $reason,
                            'supplier_id' => $supplier_id,
                            'reference' => $reference,
                        ]);

                        // Update or create stock in-transit
                        $stock = Stock::firstOrNew([
                            'item_id' => $item->id,
                            'type' => 'sole',
                            'size' => $size,
                        ]);
                        $stock->in_transit_qty = ($stock->in_transit_qty ?? 0) + $qty;
                        $stock->qty_available = $stock->qty_available ?? 0;
                        $stock->save();

                        \Log::info("Restock created for Sole ID {$item->id}, size {$size}, qty {$qty}");
                    }
                    break;



                // Material and Liquid: Unchanged (no size validation needed)
                case 'Material':
                    $item = RawMaterial::findOrFail($id);
                    $stock = Stock::firstOrNew(['item_id' => $item->id, 'type' => 'material', 'size' => null]);
                    $stock->in_transit_qty = ($stock->in_transit_qty ?? 0) + $quantity;
                    if (!isset($stock->qty_available)) {
                        $stock->qty_available = 0;
                    }
                    $stock->save();

                    StockMovement::create([
                        'item_id' => $item->id,
                        'type' => 'material',
                        'change' => $quantity,
                        'qty_after' => $stock->qty_available,
                        'description' => "Restock material (in-transit): {$reason}" . ($reference ? " (Ref: {$reference})" : ''),
                        'supplier_id' => $supplier_id,
                    ]);
                    break;

                case 'Liquid Material':
                    $item = LiquidMaterial::findOrFail($id);
                    $stock = Stock::firstOrNew(['item_id' => $item->id, 'type' => 'liquid', 'size' => null]);
                    $stock->in_transit_qty = ($stock->in_transit_qty ?? 0) + $quantity;
                    if (!isset($stock->qty_available)) {
                        $stock->qty_available = 0;
                    }
                    $stock->save();

                    StockMovement::create([
                        'item_id' => $item->id,
                        'type' => 'liquid',
                        'change' => $quantity,
                        'qty_after' => $stock->qty_available,
                        'description' => "Restock liquid (in-transit): {$reason}" . ($reference ? " (Ref: {$reference})" : ''),
                        'supplier_id' => $supplier_id,
                    ]);
                    break;

                default:
                    throw new \Exception('Invalid item type');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$type restocked successfully (in-transit)",
                'arrivals' => $stockArrivals,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Restock error', ['request' => $request->all(), 'message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function markStockReceived(Request $request, $arrivalId)
    {
        $qtyToReceive = floatval($request->input('qty_to_receive', 0));

        try {
            DB::transaction(function () use ($arrivalId, $qtyToReceive) {
                $arrival = StockArrival::where('id', $arrivalId)
                    ->whereNull('received_at')
                    ->firstOrFail();

                $availableQty = floatval($arrival->quantity);
                if ($qtyToReceive <= 0 || $qtyToReceive > $availableQty) {
                    throw new \Exception("Invalid quantity selected. Max available: {$availableQty}");
                }

                $stock = Stock::firstOrNew([
                    'item_id' => $arrival->item_id,
                    'type' => 'sole',
                    'size' => $arrival->size,
                ]);

                if (!$stock->exists) {
                    $sole = Sole::find($arrival->item_id);
                    $sizes = is_array($sole->sizes_qty) ? $sole->sizes_qty : json_decode($sole->sizes_qty, true) ?? [];
                    $stock->qty_available = floatval($sizes[$arrival->size] ?? 0);
                    $stock->in_transit_qty = 0;
                }

                $stock->qty_available += $qtyToReceive;
                $stock->in_transit_qty = max(($stock->in_transit_qty ?? 0) - $qtyToReceive, 0);
                $stock->save();

                // Update arrival qty or mark fully received
                $remainingQty = $availableQty - $qtyToReceive;
                if ($remainingQty <= 0) {
                    $arrival->received_at = now();
                    $arrival->status = 'received';
                }
                $arrival->quantity = $remainingQty;
                $arrival->save();

                // After saving stock arrival
                $order = \App\Models\SupplierOrder::find($arrival->order_id);
                if ($order) {
                    $order->updateReceiveStatus();
                }


                StockMovement::create([
                    'item_id' => $arrival->item_id,
                    'type' => 'sole',
                    'size' => $arrival->size,
                    'change' => $qtyToReceive,
                    'qty_after' => $stock->qty_available,
                    'description' => "Received {$qtyToReceive} for size {$arrival->size}",
                    'supplier_id' => $arrival->supplier_id,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Stock received successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error receiving stock', [
                'error' => $e->getMessage(),
                'arrival_id' => $arrivalId
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }






    // 🔹 New endpoint: refresh a single sole
    public function refreshSoleStock($itemId)
    {
        $sole = Sole::findOrFail($itemId);
        $sizesQty = is_string($sole->sizes_qty) ? json_decode($sole->sizes_qty, true) : ($sole->sizes_qty ?? []);

        if (!is_array($sizesQty))
            $sizesQty = [];

        // Calculate totals
        $availableQty = array_sum(array_map(fn($s) => floatval($s['qty_available'] ?? 0), $sizesQty));
        $inTransitQty = array_sum(array_map(fn($s) => floatval($s['in_transit'] ?? 0), $sizesQty));
        return response()->json([
            'sizes_qty' => $sizesQty,
            'available_qty' => $availableQty,
            'in_transit_qty' => $inTransitQty,
            'total_quantity' => $availableQty,
            'total_price' => ($sole->price ?? 0) * $availableQty,
        ]);
    }

    // Store a new Sole, Material, or Liquid
    public function store(Request $request)
    {
        try {
            \Log::info('--- STORE METHOD CALLED ---');
            \Log::info('Raw Request Data:', $request->all());

            $data = $request->json()->all();
            $type = $data['type'] ?? null;

            if (!$type) {
                return response()->json(['success' => false, 'message' => 'Type is required'], 400);
            }

            $rules = [
                'type' => 'required|in:Sole,Material,Liquid Material',
                'name' => 'required|string|max:255',
                'color' => 'nullable|string|max:255',
                'product_id' => 'nullable|exists:products,id',
            ];

            if ($type === 'Sole') {
                $rules['name'] = 'required|string|max:255';
                $rules['color'] = 'required|string|max:255';
                $rules['price'] = 'required|numeric|min:0';
                $rules['sole_type'] = 'nullable|string|max:255';
                $rules['sizes_qty'] = 'nullable|array';
                $rules['sizes_qty.*'] = 'nullable|numeric|min:0';
                $rules['quantity'] = 'nullable|numeric|min:0';
            } elseif ($type === 'Material') {
                $rules['unit'] = 'required|in:kg,g,metre,piece';
                $rules['quantity'] = 'required|numeric|min:0';
                $rules['price'] = 'required|numeric|min:0';
                $rules['per_unit_length'] = 'required_if:unit,piece|numeric|min:0.01|nullable';
            } elseif ($type === 'Liquid Material') {
                $rules['unit'] = 'required|in:litre,ml,kg,g,piece';
                $rules['quantity'] = 'required|numeric|min:0';
                $rules['price'] = 'required|numeric|min:0';
                $rules['per_unit_volume'] = 'required_if:unit,piece|numeric|min:0.01|nullable';
            }

            $validated = $request->validate($rules);

            // ✅ Duplicate Check
            $duplicate = null;
            if ($type === 'Sole') {
                $normalizedName = strtolower($validated['name']);
                $duplicate = Sole::where('product_id', $validated['product_id'])
                    ->whereRaw('LOWER(name) = ?', [$normalizedName])
                    ->first();
            } elseif ($type === 'Material') {
                $duplicate = RawMaterial::where('product_id', $validated['product_id'])
                    ->where('name', $validated['name'])
                    ->where('type', 'Material')
                    ->first();
            } elseif ($type === 'Liquid Material') {
                $duplicate = LiquidMaterial::where('product_id', $validated['product_id'])
                    ->where('name', $validated['name'])
                    ->first();
            }

            if ($duplicate) {
                return response()->json([
                    'success' => false,
                    'message' => "A $type with the same name already exists for this product"
                ], 400);
            }

            DB::beginTransaction();

            if ($type === 'Sole') {
                $sole = Sole::create([
                    'name' => $validated['name'],
                    'color' => $validated['color'],
                    'product_id' => $validated['product_id'] ?? null,
                    'sole_type' => $validated['sole_type'] ?? null,
                    'quantity' => 0,
                    'price' => $validated['price'],

                    'sizes_qty' => json_encode($validated['sizes_qty'] ?? []),
                ]);

                // Handle Stock
                $sizesQty = $validated['sizes_qty'] ?? [];
                foreach ($sizesQty as $size => $qty) {
                    if ($qty > 0) {
                        Stock::create([
                            'item_id' => $sole->id,
                            'type' => 'sole',
                            'size' => $size,
                            'qty_available' => $qty,
                        ]);

                        StockArrival::create([
                            'item_id' => $sole->id,
                            'type' => 'sole',
                            'size' => $size,
                            'quantity' => $qty,
                            'supplier_id' => null,
                            'received_at' => now(),
                        ]);
                    }
                }
            } elseif ($type === 'Material') {
                $per_unit_length = $validated['unit'] === 'piece' ? floatval($data['per_unit_length'] ?? 0) : null;
                $quantity_to_add = floatval($validated['quantity'] ?? 0);

                $material = RawMaterial::create([
                    'name' => $validated['name'],
                    'color' => $validated['color'] ?? '-',
                    'unit' => $validated['unit'],
                    'type' => 'Material',
                    'product_id' => $validated['product_id'] ?? null,
                    'quantity' => $quantity_to_add,
                    'price' => $validated['price'],
                    'per_unit_length' => $per_unit_length,
                ]);
            } elseif ($type === 'Liquid Material') {
                $per_unit_volume = $validated['unit'] === 'piece' ? floatval($data['per_unit_volume'] ?? 0) : null;
                $quantity_to_add = floatval($validated['quantity'] ?? 0);

                $material = LiquidMaterial::create([
                    'name' => $validated['name'],
                    'color' => $validated['color'] ?? '-',
                    'unit' => $validated['unit'],
                    'type' => 'Liquid Material',
                    'product_id' => $validated['product_id'] ?? null,
                    'quantity' => $quantity_to_add,
                    'price' => $validated['price'],
                    'per_unit_volume' => $per_unit_volume,
                ]);
            }

           DB::commit();
return response()->json([
    'success' => true,
    'message' => "$type added successfully",
    'data' => $type === 'Sole' ? $sole : $material
]);


        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Store Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getColorsByProduct($productId)
    {
        $colors = Sole::where('product_id', $productId)
            ->select('color')
            ->distinct()
            ->pluck('color');

        return response()->json($colors);
    }




    public function receiveStock($batchId)
    {
        DB::transaction(function () use ($batchId) {

            $batch = Batch::with('product.soles')->findOrFail($batchId);

            \Log::info("Receiving stock for Batch {$batch->batch_no}");

            foreach ($batch->product->soles as $sole) {

                // Get all stock rows for this sole
                $stocks = Stock::where('item_id', $sole->id)
                    ->where('type', 'sole')
                    ->get();

                foreach ($stocks as $stock) {

                    // Only process if there's in-transit quantity
                    if ($stock->in_transit_qty > 0) {

                        // Move in-transit to available stock
                        $stock->qty_available += $stock->in_transit_qty;

                        // Reset in-transit
                        $receivedQty = $stock->in_transit_qty;
                        $stock->in_transit_qty = 0;

                        $stock->saveQuietly();

                        // Record stock movement
                        StockMovement::create([
                            'batch_id' => $batch->id,
                            'item_id' => $sole->id,
                            'type' => 'sole',
                            'size' => $stock->size,
                            'change' => $receivedQty,
                            'qty_after' => $stock->qty_available,
                            'description' => "Stock received for batch {$batch->batch_no}",
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        \Log::info("Received {$receivedQty} units for sole '{$sole->name}' (Size: {$stock->size}). New qty_available: {$stock->qty_available}");
                    }
                }
            }

            \Log::info("✅ Stock successfully received for Batch {$batch->batch_no}");
        });
    }



    // Update existing item safely
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $type = $data['type'] ?? null;

        if (!$type) {
            return response()->json(['success' => false, 'message' => 'Type is required'], 400);
        }

        // Common rules
        $rules = [
            'type' => 'required|string|in:Sole,Material,Liquid Material',
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:255',
            'product_id' => 'nullable|exists:products,id',
        ];

        // Type-specific rules
        if ($type === 'Sole') {
            // Allow sizes_qty to be omitted during edits — frontend may not send sizes when user
            // only updates metadata like name/color/price. When omitted, controller will keep existing sizes.
            $rules['sizes_qty'] = 'nullable|array';
            $rules['sizes_qty.*'] = 'nullable|numeric|min:0';
            $rules['price'] = 'required|numeric|min:0';
            $rules['sole_type'] = 'nullable|string|max:255';
        } elseif ($type === 'Material') {
            $rules['unit'] = 'required|in:kg,g,metre,piece';
            $rules['quantity'] = 'required|numeric|min:0';
            $rules['price'] = 'required|numeric|min:0';
            $rules['per_unit_length'] = 'required_if:unit,piece|numeric|min:0.01|nullable';
        } elseif ($type === 'Liquid Material') {
            $rules['unit'] = 'required|in:litre,ml,kg,g,piece';
            $rules['quantity'] = 'required|numeric|min:0';
            $rules['price'] = 'required|numeric|min:0';
            $rules['per_unit_volume'] = 'required_if:unit,piece|numeric|min:0.01|nullable';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            switch ($type) {
                case 'Sole':
                    $item = Sole::findOrFail($id);
                    $product_id = $validated['product_id'] ?? $item->product_id;

                    // Check for duplicates
                    $duplicate = Sole::where('product_id', $product_id)
                        ->where('name', $validated['name'])
                        ->where('color', $validated['color'])
                        ->where('id', '!=', $id)
                        ->first();
                    if ($duplicate) {
                        return response()->json([
                            'success' => false,
                            'message' => 'A sole with the same name and color already exists'
                        ], 400);
                    }

                    // If sizes_qty is provided, normalize and update sizes + stock.
                    // If it's omitted (null), keep existing sizes and stock unchanged.
                    if (array_key_exists('sizes_qty', $validated) && is_array($validated['sizes_qty'])) {
                        $sizes_qty = array_map(fn($qty) => floatval($qty) ?: 0, $validated['sizes_qty']);
                        $total_qty = array_sum($sizes_qty);

                        \Log::info('Sole update payload', [
                            'sizes_qty' => $validated['sizes_qty'],
                            'total_qty' => $total_qty
                        ]);

                        // Prepare update data including sizes/quantity
                        $updateData = [
                            'product_id' => $product_id,
                            'name' => $validated['name'],
                            'color' => $validated['color'] ?? '-',
                            'quantity' => $total_qty,
                            'sizes_qty' => $sizes_qty, // cast to JSON automatically if $casts set in model
                            'price' => $validated['price'],
                            'sole_type' => $validated['sole_type'],
                        ];

                        $item->update($updateData);

                        // Sync stock quantities for each size (35-44)
                        foreach (range(35, 44) as $size) {
                            $qty = floatval($sizes_qty[$size] ?? 0);

                            Stock::updateOrCreate(
                                ['item_id' => $item->id, 'type' => 'sole', 'size' => $size],
                                ['qty_available' => $qty]
                            );
                        }
                    } else {
                        // No sizes sent — update only metadata and price
                        \Log::info('Sole update payload: sizes omitted, updating metadata only', [
                            'validated' => $validated
                        ]);

                        $item->update([
                            'product_id' => $product_id,
                            'name' => $validated['name'],
                            'color' => $validated['color'] ?? '-',
                            'price' => $validated['price'],
                            'sole_type' => $validated['sole_type'],
                        ]);
                    }

                    break;

                case 'Material':
                    $item = RawMaterial::findOrFail($id);
                    if ($item->type !== 'Material') {
                        return response()->json(['success' => false, 'message' => 'Type mismatch'], 400);
                    }

                    $product_id = $validated['product_id'] ?? $item->product_id;
                    $per_unit_length = $validated['unit'] === 'piece' ? floatval($validated['per_unit_length'] ?? 0) : null;

                    $item->update([
                        'product_id' => $product_id,
                        'name' => $validated['name'],
                        'color' => $validated['color'] ?? '-',
                        'unit' => $validated['unit'],
                        'quantity' => $validated['quantity'],
                        'price' => $validated['price'],
                        'per_unit_length' => $per_unit_length,
                    ]);

                    // Update stock
                    Stock::updateOrCreate(
                        ['item_id' => $item->id, 'type' => 'material', 'size' => null],
                        ['qty_available' => $validated['quantity']]
                    );
                    break;

                case 'Liquid Material':
                    $item = LiquidMaterial::findOrFail($id);
                    $product_id = $validated['product_id'] ?? $item->product_id;
                    $per_unit_volume = $validated['unit'] === 'piece' ? floatval($validated['per_unit_volume'] ?? 0) : null;

                    $item->update([
                        'product_id' => $product_id,
                        'name' => $validated['name'],
                        'unit' => $validated['unit'],
                        'quantity' => $validated['quantity'],
                        'price' => $validated['price'],
                        'per_unit_volume' => $per_unit_volume,
                    ]);

                    Stock::updateOrCreate(
                        ['item_id' => $item->id, 'type' => 'liquid', 'size' => null],
                        ['qty_available' => $validated['quantity']]
                    );
                    break;
            }

            DB::commit();
            $item->refresh();

            return response()->json([
                'success' => true,
                'data' => $item

            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('RawMaterial update error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update item: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete existing items
    public function destroy($id)
    {
        try {
            // Normalize type to lowercase for safe comparison
            $type = strtolower(trim(request()->get('type', '')));

            DB::beginTransaction();

            switch ($type) {
                case 'sole':
                    $item = Sole::findOrFail($id);

                    // Detach from products if pivot exists
                    if (method_exists($item, 'products')) {
                        $item->products()->detach();
                    }

                    $item->delete();
                    \Log::info("🗑️ Sole deleted", ['id' => $id]);
                    break;

                case 'material':
                    $item = RawMaterial::findOrFail($id);
                    $item->delete();
                    \Log::info("🗑️ Material deleted", ['id' => $id]);
                    break;

                case 'liquid material':
                    $item = LiquidMaterial::findOrFail($id);
                    $item->delete();
                    \Log::info("🗑️ Liquid Material deleted", ['id' => $id]);
                    break;

                default:
                    \Log::warning("⚠️ Invalid item type for delete", ['id' => $id, 'type' => $type]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid item type'
                    ], 400);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            \Log::error("❌ Item not found for delete", ['id' => $id, 'type' => $type]);
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("💥 Delete error", [
                'id' => $id,
                'type' => $type,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete item: ' . $e->getMessage()
            ], 500);
        }
    }


}