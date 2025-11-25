<?php
namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\InventoryTransfer;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // Warehouse Management
    public function warehouses(Request $request)
    {
        $search = $request->query('search');
        $warehouses = Warehouse::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('location', 'like', "%{$search}%");
            })
            ->paginate(10);
        return view('inventory.warehouses.index', compact('warehouses'));
    }

    public function createWarehouse()
    {
        return view('inventory.warehouses.create');
    }

    public function storeWarehouse(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        Warehouse::create($validated);

        return redirect()->route('inventory.warehouses')->with('success', __('Warehouse added successfully!'));
    }

    public function editWarehouse(Warehouse $warehouse)
    {
        return view('inventory.warehouses.edit', compact('warehouse'));
    }

    public function updateWarehouse(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $warehouse->update($validated);

        return redirect()->route('inventory.warehouses')->with('success', __('Warehouse updated successfully!'));
    }

    public function destroyWarehouse(Warehouse $warehouse)
    {
        if ($warehouse->products()->count() > 0) {
            return redirect()->route('inventory.warehouses')->withErrors(['error' => __('Cannot delete warehouse with associated products.')]);
        }
        $warehouse->delete();
        return redirect()->route('inventory.warehouses')->with('success', __('Warehouse deleted successfully!'));
    }

    // Stock Adjustments
    public function adjustments(Request $request)
    {
        $search = $request->query('search');
        $adjustments = StockAdjustment::query()
            ->with(['product', 'warehouse'])
            ->when($search, function ($query, $search) {
                return $query->where('reason', 'like', "%{$search}%")
                             ->orWhereHas('product', function ($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%");
                             })
                             ->orWhereHas('warehouse', function ($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%");
                             });
            })
            ->paginate(10);
        return view('inventory.adjustments.index', compact('adjustments'));
    }

    public function createAdjustment()
    {
        $products = Product::all();
        $warehouses = Warehouse::all();
        return view('inventory.adjustments.create', compact('products', 'warehouses'));
    }

    public function storeAdjustment(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'adjustment_date' => 'required|date',
        ]);

        $product = Product::find($validated['product_id']);
        $warehouse = Warehouse::find($validated['warehouse_id']);
        $currentQuantity = $product->warehouses()->where('warehouse_id', $warehouse->id)->first()->pivot->quantity ?? 0;

        if ($currentQuantity + $validated['quantity'] < 0) {
            return redirect()->back()->withErrors(['quantity' => __('Resulting stock cannot be negative.')]);
        }

        StockAdjustment::create($validated);

        // Update stock in product_warehouse pivot
        $product->warehouses()->syncWithoutDetaching([$warehouse->id => ['quantity' => $currentQuantity + $validated['quantity']]]);

        return redirect()->route('inventory.adjustments')->with('success', __('Stock adjustment recorded successfully!'));
    }

    // Inventory Transfers
    public function transfers(Request $request)
    {
        $search = $request->query('search');
        $transfers = InventoryTransfer::query()
            ->with(['product', 'fromWarehouse', 'toWarehouse'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('fromWarehouse', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('toWarehouse', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->paginate(10);
        return view('inventory.transfers.index', compact('transfers'));
    }

    public function createTransfer()
    {
        $products = Product::all();
        $warehouses = Warehouse::all();
        return view('inventory.transfers.create', compact('products', 'warehouses'));
    }

    public function storeTransfer(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'quantity' => 'required|integer|min:1',
            'transfer_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $product = Product::find($validated['product_id']);
        $fromWarehouse = Warehouse::find($validated['from_warehouse_id']);
        $toWarehouse = Warehouse::find($validated['to_warehouse_id']);

        $fromQuantity = $product->warehouses()->where('warehouse_id', $fromWarehouse->id)->first()->pivot->quantity ?? 0;

        if ($fromQuantity < $validated['quantity']) {
            return redirect()->back()->withErrors(['quantity' => __('Insufficient stock in source warehouse.')]);
        }

        InventoryTransfer::create($validated);

        // Update stock in product_warehouse pivot
        $product->warehouses()->syncWithoutDetaching([
            $fromWarehouse->id => ['quantity' => $fromQuantity - $validated['quantity']],
            $toWarehouse->id => ['quantity' => ($product->warehouses()->where('warehouse_id', $toWarehouse->id)->first()->pivot->quantity ?? 0) + $validated['quantity']],
        ]);

        return redirect()->route('inventory.transfers')->with('success', __('Inventory transfer recorded successfully!'));
    }

    // Check Quantity for Quotation System
    public function checkQuantity(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($validated['product_id']);
        $availableQuantity = $product->getAvailableQuantityForWarehouse($validated['warehouse_id']);

        return response()->json([
            'available' => $availableQuantity >= $validated['quantity'],
            'available_quantity' => $availableQuantity,
        ]);
    }

public function dashboard()
{
    $lowStockThreshold = 20; // updated threshold

    // Totals
    $totalMaterials = \App\Models\RawMaterial::count();
    $totalLiquids = \App\Models\LiquidMaterial::count();
    $totalSoles = \App\Models\Sole::count();

    // Fetch all items
    $rawMaterials = \App\Models\RawMaterial::all();
    $liquidMaterials = \App\Models\LiquidMaterial::all();
    $soles = \App\Models\Sole::all();

    // Count of low stock items per type
    $lowRawMaterials = $rawMaterials->where('quantity', '<', $lowStockThreshold)->count();
    $lowLiquidMaterials = $liquidMaterials->where('quantity', '<', $lowStockThreshold)->count();
    $lowSoles = $soles->where('quantity', '<', $lowStockThreshold)->count();

    return view('inventory.dashboard', compact(
        'totalMaterials',
        'totalLiquids',
        'totalSoles',
        'lowStockThreshold',
        'rawMaterials',
        'liquidMaterials',
        'soles',
        'lowRawMaterials',
        'lowLiquidMaterials',
        'lowSoles'
    ));
}



// Update Product Image
public function updateImage(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    if ($request->hasFile('image')) {
        // Store the new image in storage/app/public/products
        $path = $request->file('image')->store('products', 'public');

        // Delete old image if exists
        if ($product->image && \Storage::disk('public')->exists($product->image)) {
            \Storage::disk('public')->delete($product->image);
        }

        // Update the image field of the existing product
        $product->image = $path;
        $product->update(); // <--- use update() or save() on the existing model
    }

    return redirect()->back()->with('success', 'Product image updated successfully!');
}






}