<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductionOrderController extends Controller
{
    // Display all production orders with optional status filter
public function index(Request $request)
{
    $statusFilter = $request->query('status');
    $clientFilter = $request->query('client');

    $productionQuery = \App\Models\ProductionOrder::query()
        ->leftJoin('orders as o', 'production_orders.client_order_id', '=', 'o.id')
        ->join('quotations as q', 'production_orders.quotation_id', '=', 'q.id')
        ->join('users as admins', 'q.salesperson_id', '=', 'admins.id')
        ->select('production_orders.*', 'admins.name as admin_name')
        ->with([
            'quotation.products' => function ($q) {
                $q->withPivot(['quantity', 'unit_price', 'variations']);
            },
            'quotation.client',
            'clientOrder.client'
        ])
        ->when($statusFilter, fn($q) => $q->where('production_orders.status', $statusFilter))
        ->when($clientFilter, fn($q) => $q->where('o.client_id', $clientFilter))
        ->orderBy('production_orders.id', 'desc');

    $allOrders = $productionQuery->get()
        ->map(fn($o) => (object)[
            'type' => 'production',
            'data' => $o
        ]);

    // ✅ Group by product name or SKU
    $groupedOrders = $allOrders->groupBy(function ($wrapper) {
        $order = $wrapper->data;
        $product = $order->quotation?->products?->first();
        return $product?->name ?? $product?->sku ?? 'Unknown Article';
    });

    $clients = \App\Models\User::whereIn('category', ['wholesale', 'retail'])->get();

    return view('sales.production-orders.index', compact('groupedOrders', 'statusFilter', 'clientFilter', 'clients'));
}

    // Show delivery form
    public function showDeliverForm(Order $order)
{
    $products = collect();

    // If quotation exists
    if ($order->quotation?->products) {
        $products = $order->quotation->products->map(function($p) {
            return (object)[
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'pivot' => $p->pivot,
            ];
        });
    }
    // If cart_items exist
    elseif ($order->cart_items) {
        $cartItems = json_decode($order->cart_items, true);
        foreach ($cartItems as $item) {
            $productModel = \App\Models\Product::find($item['product_id']);
            $products->push((object)[
                'id' => $productModel->id ?? null,
                'name' => $productModel->name ?? 'N/A',
                'sku' => $productModel->sku ?? 'N/A',
                'pivot' => (object)['quantity' => $item['quantity'] ?? 0],
            ]);
        }
    }

    return view('sales.production-orders.deliver', compact('order', 'products'));
}


public function myOrders()
{
    $myOrders = ProductionOrder::where('sales_person_id', auth()->id())->get();
    return view('sales.production-orders.my_orders', compact('myOrders'));
}

public function updateStatus(Request $request, ProductionOrder $order)
{
    // 🔒 Authorization
    if (!auth()->user()->hasRole('Admin')) {
        return redirect()->route('production-orders.index')
                         ->with('error', 'Unauthorized action.');
    }

    // ✅ Validate status
    $request->validate([
        'status' => 'required|in:pending,processing,accepted,rejected,shipping,delivered',
    ]);

    $userId = auth()->id();

    // 🔄 Update production order status
    $order->update(['status' => $request->status]);

    // 🔹 Create or fetch client order
    $clientOrder = \App\Models\Order::firstOrCreate(
        ['id' => $order->client_order_id],
        [
            'quotation_id'      => $order->quotation_id,
            'client_id'         => $order->quotation?->client_id ?? 1,
            'status'            => 'pending',
            'user_id'           => $userId,
            'transport_name'    => 'N/A',
            'transport_address' => 'N/A',
            'transport_id'      => 0,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]
    );

    // 🔹 Link production order to client order if not linked
    if (!$order->client_order_id) {
        $order->update(['client_order_id' => $clientOrder->id]);
    }

    // 🔔 Notify client
    if ($clientOrder->client) {
        $clientOrder->client->notify(new \App\Notifications\OrderStatusUpdated($clientOrder));
    }

    // 🔹 Prepare session for Batch Flow if accepted
    if ($request->status === 'accepted') {
        $productsForSession = [];

        // Eager load all necessary relationships
        $products = $order->quotation->products()->with([
            'soles', 'materials', 'processes', 'liquidMaterials'
        ])->get();

        foreach ($products as $product) {
            // Handle variations
            $variations = is_string($product->pivot->variations)
                ? json_decode($product->pivot->variations, true) ?? []
                : ($product->pivot->variations ?? []);

            $formattedVariations = [];
            foreach ($variations as $variation) {
                $sizes = [];
                for ($size = 35; $size <= 44; $size++) {
                    $sizes[$size] = $variation['sizes'][$size] ?? 0;
                }

                $formattedVariations[] = [
                    'color'      => $variation['color'] ?? '',
                    'sole_color' => $variation['sole_color'] ?? '',
                    'sizes'      => $sizes,
                    'main_image' => $variation['main_image'] ?? null, // only main_image
                ];
            }

            // Map processes with labor_rate
            $processes = $product->processes->map(function ($process) {
                return [
                    'id'            => $process->id,
                    'name'          => $process->name,
                    'stage'         => $process->stage ?? 'N/A',
                    'labor_rate'    => $process->pivot->labor_rate ?? 0,
                    'process_order' => $process->pivot->process_order ?? 0,
                ];
            })->toArray();

            // Get liquid materials
            $liquids = $product->liquidMaterialsArray;

            // ✅ Use only main_image from variations
            $image = !empty($formattedVariations[0]['main_image'])
                ? asset('storage/' . $formattedVariations[0]['main_image'])
                : 'https://via.placeholder.com/150?text=No+Image';

            // Prepare product data for session
            $productsForSession[] = [
                'product_id'      => $product->id,
                'name'            => $product->name,
                'sku'             => $product->sku ?? 'N/A',
                'quantity'        => $product->pivot->quantity ?? 0,
                'unit_price'      => $product->pivot->unit_price ?? 0,
                'image'           => $image,
                'description'     => $product->description ?? null,
                'soles'           => $product->soles->toArray(),
                'materials'       => $product->materials->toArray(),
                'processes'       => $processes,
                'liquidMaterials' => $liquids,
                'variations'      => $formattedVariations,
            ];
        }

        // Store client details in session
        if ($clientOrder->client) {
            $request->session()->put('client_details', [
                'id'    => $clientOrder->client->id,
                'name'  => $clientOrder->client->name,
                'email' => $clientOrder->client->email,
                'phone' => $clientOrder->client->phone,
            ]);
        }

        // Store products and orders in session
        $request->session()->put('quotation_products', $productsForSession);
        $request->session()->put('orders', [
            [
                'order_no' => $clientOrder->id,
                'status'   => $clientOrder->status,
            ]
        ]);

        // Optional debug logs
        \Log::info('Quotation Products stored in session', ['products' => $productsForSession]);

        return redirect()
            ->route('batch.flow.create')
            ->with('success', 'Production Order accepted. Proceed with Batch Flow.');
    }

    return redirect()
        ->route('production-orders.index')
        ->with('success', 'Production Order status updated successfully.');
}

public function show($id)
{
    // 1️⃣ Try to find a normal order first
    $order = Order::with(['products', 'client', 'user'])->find($id);
    $type = 'order';

    // 2️⃣ If not found, try ProductionOrder
    if (!$order) {
        $order = ProductionOrder::with([
            'quotation.client',
            'quotation.products',
            'clientOrder.client',
            'user'
        ])->find($id);

        $type = 'production';
    }

    // 3️⃣ If still not found, abort
    if (!$order) {
        abort(404, 'Order not found');
    }

    // 4️⃣ Determine the customer, createdBy, and dueDate robustly
    if ($type === 'production') {
        $customer = $order->clientOrder?->client 
                  ?: $order->quotation?->client 
                  ?: (object)[
                        'name' => $order->client_name ?? 'N/A',
                        'business_name' => $order->client_business_name ?? null,
                        'email' => $order->client_email ?? null,
                        'phone' => $order->client_phone ?? null,
                    ];

        $createdBy = $order->user ?? null;
        $dueDate = $order->due_date ?? null;
    } else {
        $customer = $order->client 
                  ?: (object)[
                        'name' => $order->client_name ?? 'N/A',
                        'business_name' => $order->client_business_name ?? null,
                        'email' => $order->client_email ?? null,
                        'phone' => $order->client_phone ?? null,
                    ];

        $createdBy = $order->user ?? null;
        $dueDate = $order->due_date ?? null;
    }

    // 5️⃣ Prepare products collection
    $products = collect();

    if ($order->products?->count()) {
        $products = $order->products;
    } elseif ($type === 'production' && $order->relationLoaded('quotation') && $order->quotation?->products?->count()) {
        $products = $order->quotation->products;
    } elseif ($order->cart_items) {
        $cartItems = json_decode($order->cart_items, true) ?? [];
        foreach ($cartItems as $item) {
            $p = \App\Models\Product::find($item['product_id']);
            if ($p) {
                $products->push((object)[
                    'name' => $p->name,
                    'sku' => $p->sku,
                    'pivot' => (object)[
                        'quantity'   => $item['quantity'] ?? 1,
                        'unit_price' => $item['unit_price'] ?? $p->price ?? 0,
                        'variations' => $item['variations'] ?? [],
                    ]
                ]);
            }
        }
    }

    // 6️⃣ Pass everything to the Blade view
    return view('orders.show-details', [
        'order'      => $order,
        'type'       => $type,
        'customer'   => $customer,
        'createdBy'  => $createdBy,
        'dueDate'    => $dueDate,
        'products'   => $products
    ]);
}



public function bulkAccept(Request $request)
{
    $orderIds = array_filter(explode(',', $request->input('selected_orders', '')));
    if (empty($orderIds)) {
        return redirect()->back()->with('error', 'Please select at least one order.');
    }

    $orders = ProductionOrder::with([
        'quotation.client',
        'quotation.products.soles',
        'quotation.products.materials',
        'quotation.products.processes',
        'quotation.products.liquidMaterials'
    ])->whereIn('id', $orderIds)->get();

    if ($orders->isEmpty()) {
        return redirect()->back()->with('error', 'No valid production orders found.');
    }

    $mergedData = []; // Group by product_id

    foreach ($orders as $order) {
        $quotation = $order->quotation;
        $client = $quotation->client;

        foreach ($quotation->products as $product) {
            $productId = $product->id;

            // ✅ Decode variations safely
            $variations = is_string($product->pivot->variations)
                ? json_decode($product->pivot->variations, true) ?? []
                : ($product->pivot->variations ?? []);

            // ✅ Extract main_image from variations if available
            $mainImage = null;
            foreach ($variations as $var) {
                if (!empty($var['main_image'])) {
                    $mainImage = $var['main_image'];
                    break;
                }
            }

            // ✅ Determine final image URL
            $image = null;
            if ($mainImage) {
                $image = asset('storage/' . $mainImage);
            } elseif (!empty($product->image)) {
                $image = asset('storage/' . $product->image);
            } else {
                $image = 'https://via.placeholder.com/150?text=No+Image';
            }

            // ✅ Initialize entry if not exists
            if (!isset($mergedData[$productId])) {
                $mergedData[$productId] = [
                    'product_id'      => $productId,
                    'sku'             => $product->sku,
                    'name'            => $product->name,
                    'description'     => $product->description,
                    'image'           => $image, // ✅ fixed image logic
                    'soles'           => $product->soles->toArray(),
                    'materials'       => $product->materials->toArray(),
                    'processes'       => $product->processes->map(fn($p) => [
                        'id'            => $p->id,
                        'name'          => $p->name,
                        'stage'         => $p->stage ?? 'N/A',
                        'labor_rate'    => $p->pivot->labor_rate ?? 0,
                        'process_order' => $p->pivot->process_order ?? 0,
                    ])->toArray(),
                    'liquidMaterials' => $product->liquidMaterialsArray,
                    'variations'      => [],
                    'clients'         => [],
                    'total_qty'       => 0,
                ];
            }

            // Add client to this product group
            $mergedData[$productId]['clients'][$client->id] = [
                'id' => $client->id,
                'name' => $client->name,
                'category' => $client->category,
                'brand' => $quotation->brand_name ?? 'N/A',
            ];


            // Merge variations by color & size
            foreach ($variations as $var) {
                $color = $var['color'] ?? 'N/A';
                $soleColor = $var['sole_color'] ?? '';

                // Find existing variation entry
                $existingIndex = null;
                foreach ($mergedData[$productId]['variations'] as $index => $existingVar) {
                    if ($existingVar['color'] === $color && $existingVar['sole_color'] === $soleColor) {
                        $existingIndex = $index;
                        break;
                    }
                }

                if ($existingIndex !== null) {
                    // Add quantities
                    foreach (range(35, 44) as $size) {
                        $mergedData[$productId]['variations'][$existingIndex]['sizes'][$size] += $var['sizes'][$size] ?? 0;
                        $mergedData[$productId]['total_qty'] += $var['sizes'][$size] ?? 0;
                    }
                } else {
                    // New variation entry
                    $sizes = [];
                    foreach (range(35, 44) as $size) {
                        $sizes[$size] = $var['sizes'][$size] ?? 0;
                        $mergedData[$productId]['total_qty'] += $var['sizes'][$size] ?? 0;
                    }

                    $mergedData[$productId]['variations'][] = [
                        'color'      => $color,
                        'sole_color' => $soleColor,
                        'sizes'      => $sizes,
                        'main_image' => $var['main_image'] ?? null,
                    ];
                }
            }

            // ✅ Update order status
            $order->update(['status' => 'accepted']);
        }
    }

    // ✅ Prepare session data
    $quotationProducts = array_values($mergedData);
    $ordersForSession = $orders->map(fn($o) => [
        'order_no' => $o->id,
        'status' => $o->status,
    ])->toArray();

    // ✅ Save session
    $request->session()->put('quotation_products', $quotationProducts);
    $request->session()->put('orders', $ordersForSession);

    \Log::info('BatchFlow merge complete (image fixed)', ['products' => $quotationProducts]);

    return redirect()
        ->route('batch.flow.create')
        ->with('success', 'Selected quotations merged successfully. Proceed with Batch Flow creation.');
}


public function updatePayment(Request $request, Order $order)
{
    $paidAmount = round(floatval($request->paid), 2);
    $orderTotal = round($order->total, 2);

    if ($paidAmount <= 0) {
        $status = 'pending';
    } elseif ($paidAmount < $orderTotal) {
        $status = 'partially_paid';
    } else {
        $status = 'paid';
    }

    $order->paid_amount = $paidAmount;
    $order->status = $status;
    $order->save();

    // Create invoice if fully paid
    if ($status === 'paid') {
        Invoice::firstOrCreate(
            ['order_id' => $order->id],
            [
                'client_id' => $order->client_id,
                'amount' => $order->total,
                'amount_paid' => $order->total,
                'items' => $order->cart_items ? json_decode($order->cart_items, true) : [],
                'status' => 'paid',
                'payment_type' => $order->payment_method ?? 'cod',
            ]
        );
    }

    return redirect()->back()->with('success', 'Payment & status updated successfully!');
}



    // Process delivery form submission
   public function processDelivery(Request $request, Order $order)

    {
        if (!auth()->user()->hasPermissionTo('production')) {
            Log::warning('Unauthorized attempt to deliver order', [
                'user_id' => auth()->id(),
                'order_id' => $order->id,
            ]);
            return redirect()->route('production-orders.index')
                             ->with('error', 'Unauthorized action.');
        }

        $deliverQuantities = $request->input('deliver_quantities', []);
        $deliveryDate = $request->input('delivery_date', now());
        $deliveryPerson = $request->input('delivery_person', 'N/A');

        if (empty($deliverQuantities)) {
            return back()->with('error', 'Please enter at least one quantity to deliver.');
        }

        try {
            $products = json_decode($order->clientOrder->cart_items ?? '[]', true);
            $allDelivered = true;
            $partialDelivered = false;

            foreach ($products as &$item) {
                $deliverQty = $deliverQuantities[$item['name']] ?? 0;
                $deliverQty = min($deliverQty, $item['quantity']); // prevent over-delivery

                if ($deliverQty < $item['quantity']) {
                    $allDelivered = false;
                    if ($deliverQty > 0) {
                        $partialDelivered = true;
                    }
                }

                $item['quantity'] -= $deliverQty;
            }

            $order->status = $allDelivered
                ? 'delivered'
                : ($partialDelivered ? 'partially delivered' : $order->status);

            $order->clientOrder->cart_items = json_encode($products);
            $order->clientOrder->save();
            $order->save();

            Log::info('Order delivery processed', [
                'order_id' => $order->id,
                'status' => $order->status,
                'delivered_quantities' => $deliverQuantities,
                'delivery_date' => $deliveryDate,
                'delivery_person' => $deliveryPerson,
            ]);

            return redirect()->route('production-orders.index')
                             ->with('success', 'Order delivery processed successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to deliver order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to process delivery.');
        }
    }

    // Mark order as processing
    public function process(ProductionOrder $order)
    {
        if (!auth()->user()->hasPermissionTo('production')) {
            return redirect()->route('production-orders.index')
                             ->with('error', 'Unauthorized action.');
        }

        $order->status = 'processing';
        $order->save();

        return redirect()->route('production-orders.index')
                         ->with('success', 'Order is now being processed.');
    }
}
