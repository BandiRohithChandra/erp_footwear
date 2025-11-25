<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\StockArrival;
use App\Models\SupplierReturn;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\SupplierOrder;
use App\Models\RawMaterial;
use App\Models\LiquidMaterial;
use App\Models\Sole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupplierOrderController extends Controller
{
    public function index()
    {
        $orders = SupplierOrder::with('supplier')->latest()->get();
        return view('supplier_orders.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $rawMaterials = RawMaterial::all();
        $liquidMaterials = LiquidMaterial::all();
        $articles = Product::all();

        // Fetch all soles with price > 0
        $soles = Sole::select('id', 'name', 'color', 'sole_type', 'price')->get();


        return view('supplier_orders.create', compact(
            'suppliers',
            'rawMaterials',
            'liquidMaterials',
            'soles',
            'articles'
        ));
    }


public function store(Request $request)
{
    // Validate request
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'items' => 'required|array|min:1',
        'items.*.type' => 'required|string|in:raw_material,liquid_material,sole',
        'items.*.id' => 'required|numeric',
        'items.*.article_id' => 'nullable|numeric|exists:products,id',
        'items.*.quantity' => 'nullable|numeric|min:0',
        'items.*.sizes_qty' => 'nullable|array',
        'items.*.sizes_qty.*' => 'nullable|numeric|min:0',
        'total_amount' => 'required|numeric|min:0',
        'paid_amount' => 'nullable|numeric|min:0',
        'payment_status' => 'nullable|string|in:pending,partial,paid',
        'expected_delivery' => 'nullable|date'
    ]);

    $poNumber = 'PO-' . strtoupper(Str::random(6));

    // Normalize items
    $items = collect($request->items)->map(function ($item) {
        $item['type'] = $item['type'] ?? 'raw_material';
        $item['id'] = $item['id'] ?? 0;
        $item['article_id'] = $item['article_id'] ?? null;

        if ($item['type'] === 'sole') {
            if (isset($item['sizes_qty'])) {
                unset($item['quantity']);
            } else {
                $item['sizes_qty'] = [];
            }
        } else {
            $item['sizes_qty'] = null;
            $item['quantity'] = $item['quantity'] ?? 0;
        }

        return $item;
    });

    // Create purchase order
    $order = SupplierOrder::create([
        'supplier_id' => $request->supplier_id,
        'po_number' => $poNumber,
        'items' => $items,
        'total_amount' => $request->total_amount,
        'paid_amount' => $request->paid_amount ?? 0,
        'payment_status' => $request->payment_status ?? 'pending',
        'status' => 'pending',
        'order_date' => now(),
        'expected_delivery' => $request->expected_delivery
    ]);

    /* ------------------------------------------------------
       CREATE STOCK ARRIVAL RECORDS FOR SOLES ONLY
       (This ensures they reflect in Raw Materials > Stock Arrival)
       ------------------------------------------------------ */
    foreach ($items as $item) {
        if ($item['type'] === 'sole' && !empty($item['sizes_qty'])) {

            foreach ($item['sizes_qty'] as $size => $qty) {
                if ($qty > 0) {

                    StockArrival::create([
                        'type' => 'sole',                          // REQUIRED for index() loader
                        'item_id' => $item['id'],                  // Sole ID
                        'supplier_id' => $request->supplier_id,
                        'order_id' => $order->id,                  // Link to PO
                        'reason' => 'Purchase Order',
                        'article_no' => $item['article_id'] ?? null,
                        'size' => $size,
                        'quantity' => $qty,                        // This must be 'quantity'
                        'received_at' => null,                     // Pending
                    ]);
                }
            }
        }
    }

    return redirect()->route('supplier-orders.index')
        ->with('success', 'Purchase order created successfully.');
}


    public function show(SupplierOrder $supplier_order)
    {
        return view('supplier_orders.show', compact('supplier_order'));
    }

    public function edit(SupplierOrder $supplierOrder)
    {
        $suppliers = Supplier::all();
        $rawMaterials = RawMaterial::all();
        $liquidMaterials = LiquidMaterial::all();
        $soles = Sole::all();

        return view('supplier_orders.edit', compact(
            'supplierOrder',
            'suppliers',
            'rawMaterials',
            'liquidMaterials',
            'soles'
        ));
    }

    public function update(Request $request, SupplierOrder $supplierOrder)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,delivered',
            'payment_status' => 'required|string|in:pending,partial,paid',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        $supplierOrder->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'paid_amount' => $request->paid_amount ?? $supplierOrder->paid_amount,
        ]);

        return redirect()->route('supplier-orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(SupplierOrder $supplierOrder)
    {
        $supplierOrder->delete();
        return redirect()->route('supplier-orders.index')
            ->with('success', 'Supplier order deleted successfully.');
    }

    public function supplierOrders($supplierId)
    {
        $supplier = Supplier::with('supplierOrders')->findOrFail($supplierId);
        return view('suppliers.show', compact('supplier'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = SupplierOrder::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,processing,delivered']);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function returns()
{
    $returns = \App\Models\SupplierReturn::with('supplier', 'order')->latest()->get();
    return view('supplier_orders.returns.index', compact('returns'));
}

public function createReturn($orderId)
{
    $order = SupplierOrder::findOrFail($orderId);

    // Only allow SOLE returns (just like stock arrival)
    $items = collect($order->items)->filter(function($item){
        return $item['type'] === 'sole';
    });

    return view('supplier_orders.returns.create', compact('order','items'));
}


public function storeReturn(Request $request)
{
    \Log::info("STORE RETURN REQUEST RECEIVED", [
        'full_request' => $request->all()
    ]);

    try {

        // 1) FILTER OUT EMPTY ITEMS
       $cleanItems = collect($request->items)
    ->filter(fn($item) => !empty($item['qty']) && $item['qty'] > 0)
    ->map(function ($item) {

        // If reason = Other → Keep other_reason
        if (isset($item['reason']) && $item['reason'] === 'Other') {
            $item['other_reason'] = $item['other_reason'] ?? null;
        } else {
            $item['other_reason'] = null;
        }

        return $item;
    })
    ->toArray();


        \Log::info("CLEANED ITEMS", $cleanItems);

        // If no qty > 0, throw error
        if (empty($cleanItems)) {
            return back()->with('error', 'Please enter at least one return quantity.');
        }

        // 2) VALIDATE CLEANED ITEMS ONLY
        foreach ($cleanItems as $key => $item) {
            $validator = \Validator::make($item, [
    'sole_id' => 'required|numeric',
    'size'    => 'required|string',
    'qty'     => 'required|numeric|min:1',
    'reason'  => 'required|string',
    'other_reason' => $item['reason'] === 'Other' ? 'required|string' : 'nullable',
]);


            if ($validator->fails()) {
                \Log::error("VALIDATION FAILED FOR ITEM $key", $validator->errors()->toArray());
                return back()->withErrors($validator)->withInput();
            }
        }

        // 3) CREATE THE RETURN ORDER
        $return = \App\Models\SupplierReturn::create([
            'supplier_id' => $request->supplier_id,
            'order_id'    => $request->order_id,
            'items'       => $cleanItems,
            'remarks'     => $request->remarks,
            'status'      => 'pending',
        ]);

        \Log::info("RETURN CREATED", ['return_id' => $return->id]);

        // 4) INSERT NEGATIVE STOCK MOVEMENTS
        foreach ($cleanItems as $item) {

            \Log::info("CREATING STOCK ENTRY", $item);

            \App\Models\StockArrival::create([
                'type'        => 'sole_return',
                'item_id'     => $item['sole_id'],
                'supplier_id' => $request->supplier_id,
                'order_id'    => $request->order_id,
                'reason'      => 'Return to Supplier',
                'size'        => $item['size'],
                'quantity'    => -$item['qty'], // negative stock
                'received_at' => now(),
            ]);
        }

        return redirect()->route('supplier-orders.returns')
            ->with('success', 'Return order submitted successfully.');

    } catch (\Exception $e) {
        \Log::error("ERROR IN STORE RETURN", [
            'error_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Error: '.$e->getMessage());
    }
}


public function editReturn($id)
{
    $returnOrder = SupplierReturn::findOrFail($id);

    $order = $returnOrder->order;

    // Convert order items to structured list
    $items = collect($order->items)
        ->filter(fn($item) => $item['type'] === 'sole')
        ->map(function ($item) {
            return [
                'id' => $item['id'],  // <--- correct key
                'sizes_qty' => is_string($item['sizes_qty'])
                    ? json_decode($item['sizes_qty'], true)
                    : $item['sizes_qty']
            ];
        });

    // Prepare return items (already saved)
    $returnItems = [];
    foreach ($returnOrder->items as $item) {
        $returnItems[$item['sole_id']][$item['size']] = [
            'qty'          => $item['qty'],
            'reason'       => $item['reason'],
            'other_reason' => $item['other_reason'] ?? '',
        ];
    }

    return view('supplier_orders.returns.edit', compact(
        'order',
        'items',
        'returnItems',
        'returnOrder'
    ));
}



public function updateReturn(Request $request, $id)
{
    \Log::info("🔵 STEP 1: UPDATE RETURN REQUEST RECEIVED", [
        'id' => $id,
        'request_data' => $request->all()
    ]);

    try {

        // STEP 2: CHECK IF RETURN ORDER EXISTS
        $returnOrder = SupplierReturn::find($id);
        if (!$returnOrder) {
            \Log::error("❌ STEP 2 FAILED: RETURN ORDER NOT FOUND", ['id' => $id]);
            return back()->with('error', 'Return order not found');
        }

        \Log::info("✅ STEP 2: RETURN ORDER FOUND", [
            'returnOrder' => $returnOrder
        ]);

        // 3) FILTER VALID ITEMS
        \Log::info("🔵 STEP 3: FILTERING ITEMS");

        $cleanItems = collect($request->items ?? [])
            ->filter(function ($item) {
                return !empty($item['qty']) && $item['qty'] > 0;
            })
            ->map(function ($item) {
                if (isset($item['reason']) && $item['reason'] === 'Other') {
                    $item['other_reason'] = $item['other_reason'] ?? null;
                } else {
                    $item['other_reason'] = null;
                }
                return $item;
            })
            ->toArray();

        \Log::info("🟡 STEP 3 RESULT: CLEAN ITEMS", [
            'clean_items' => $cleanItems
        ]);

        if (empty($cleanItems)) {
            \Log::warning("⚠️ STEP 3 FAILED: NO VALID ITEMS");
            return back()->with('error', 'Please enter at least one return quantity.');
        }

        // 4) VALIDATE EACH ITEM
        \Log::info("🔵 STEP 4: VALIDATING ITEMS");

        foreach ($cleanItems as $key => $item) {
            \Log::info("Validating Item", ['item' => $item]);

            $validator = \Validator::make($item, [
                'sole_id' => 'required|numeric',
                'size'    => 'required|string',
                'qty'     => 'required|numeric|min:1',
                'reason'  => 'required|string',
                'other_reason' => ($item['reason'] === 'Other') ? 'required|string' : 'nullable',
            ]);

            if ($validator->fails()) {
                \Log::error("❌ STEP 4 FAILED: VALIDATION ERROR", [
                    'errors' => $validator->errors()->toArray()
                ]);
                return back()->withErrors($validator)->withInput();
            }
        }

        // 5) DELETE OLD STOCK MOVEMENTS
        \Log::info("🔵 STEP 5: DELETING OLD STOCK MOVEMENTS", [
            'order_id' => $returnOrder->order_id,
            'supplier_id' => $returnOrder->supplier_id
        ]);

        StockArrival::where('order_id', $returnOrder->order_id)
            ->where('supplier_id', $returnOrder->supplier_id)
            ->where('type', 'sole_return')
            ->delete();

        // 6) CREATE NEW STOCK MOVEMENTS
        \Log::info("🔵 STEP 6: CREATING NEW STOCK MOVEMENTS");

        foreach ($cleanItems as $item) {
            \Log::info("Creating Stock Movement", ['item' => $item]);

            StockArrival::create([
                'type'        => 'sole_return',
                'item_id'     => $item['sole_id'],
                'supplier_id' => $returnOrder->supplier_id,
                'order_id'    => $returnOrder->order_id,
                'reason'      => 'Return to Supplier',
                'size'        => $item['size'],
                'quantity'    => -$item['qty'],
                'received_at' => now(),
            ]);
        }

        // 7) UPDATE RETURN ORDER
        \Log::info("🔵 STEP 7: UPDATING RETURN ORDER", [
            'update_data' => [
                'items'   => $cleanItems,
                'remarks' => $request->remarks,
                'status'  => 'pending',
            ]
        ]);

        $returnOrder->update([
            'items'   => $cleanItems,
            'remarks' => $request->remarks,
            'status'  => 'pending',
        ]);

        \Log::info("✅ STEP 7 SUCCESS: RETURN ORDER UPDATED");

        return redirect()
            ->route('supplier-orders.returns')
            ->with('success', 'Return order updated successfully.');

    } catch (\Exception $e) {

        \Log::error("🔥 FATAL ERROR IN updateReturn()", [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()->with('error', 'Error: '.$e->getMessage());
    }
}



public function returnBill($id)
{
    $return = SupplierReturn::with(['supplier', 'order'])->findOrFail($id);

    $items = collect($return->items)->map(function($i){

        $sole = \App\Models\Sole::find($i['sole_id']);

        // IMPORTANT: Fetch unit price from sole model
        $price = $sole->price ?? 0;

        return [
            'sole_name' => $sole->name ?? 'Unknown',
            'color'     => $sole->color ?? '-',
            'size'      => $i['size'],
            'qty'       => $i['qty'],
            'price'     => $price,  // <-- ADD THIS
            'total'     => $price * $i['qty'], // <-- ADD THIS
            'reason'    => $i['reason'] === 'Other' ? ($i['other_reason'] ?? 'Other') : $i['reason'],
        ];
    });

    return view('supplier_orders.returns.bill', compact('return', 'items'));
}





public function showReturn($id)
{
    $return = SupplierReturn::with('supplier', 'order')->findOrFail($id);
    return view('supplier_orders.returns.show', compact('return'));
}



public function completeReturn($id)
{
    $return = SupplierReturn::findOrFail($id);

    foreach ($return->items as $item) {

        $soleId = $item['sole_id'];
        $size   = $item['size'];
        $qty    = floatval($item['qty']);

        // FIND STOCK ROW
        $stock = Stock::firstOrNew([
            'item_id' => $soleId,
            'type' => 'sole',
            'size' => $size,
        ]);

        // Initialize if missing
        if (!$stock->exists) {
            $stock->qty_available = 0;
            $stock->in_transit_qty = 0;
        }

        // 🔥 DEDUCT FROM AVAILABLE STOCK
        $stock->qty_available = max(0, $stock->qty_available - $qty);
        $stock->save();

        // 🔥 LOG MOVEMENT
        StockMovement::create([
            'item_id' => $soleId,
            'type' => 'sole',
            'size' => $size,
            'change' => -$qty,
            'qty_after' => $stock->qty_available,
            'description' => "Returned {$qty} – deducted from stock",
            'supplier_id' => $return->supplier_id,
        ]);
    }

    // UPDATE RETURN STATUS
    $return->status = 'completed';
    $return->save();

    return redirect()
        ->route('supplier-orders.returns')
        ->with('success', 'Return completed & stock updated!');
}



}
