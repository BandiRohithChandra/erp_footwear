<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class SalesOrderController extends Controller
{
    /**
     * Add product to cart and show sales order create page
     */
public function create(Request $request, $productId = null)
{
    $cart = session('sales_cart', []);

    // Only add if we have explicit POST data with variations
    if ($productId && $request->filled('quantities') && $request->isMethod('post')) {
        $product = Product::findOrFail($productId);
        $color = $request->input('color', $product->color ?? 'N/A');
        $quantities = json_decode($request->input('quantities', '{}'), true);

        // Find the selected variation
        $variation = collect($product->variations)->firstWhere('color', $color);
        $image = $variation->image ?? $product->image ?? null;

        foreach ($quantities as $size => $quantity) {
            $quantity = (int)$quantity;
            if ($quantity <= 0) continue;

            $existingIndex = null;
            foreach ($cart as $index => $item) {
                if ($item['product_id'] == $product->id && $item['color'] == $color && $item['size'] == $size) {
                    $existingIndex = $index;
                    break;
                }
            }

            if ($existingIndex !== null) {
                $cart[$existingIndex]['quantity'] += $quantity;
            } else {
                $cart[] = [
                    'product_id' => $product->id,
                    'name'       => $product->name,
                    'color'      => $color,
                    'size'       => $size,
                    'quantity'   => $quantity,
                    'price'      => $product->price,
                    'image'      => $image ? '/storage/' . $image : null, // store selected variation image
                ];
            }
        }

        session(['sales_cart' => $cart]);
    }

    $orderItems = collect($cart)->map(function($item) {
        return [
            'product_id' => $item['product_id'],
            'name'       => $item['name'],
            'color'      => $item['color'] ?? 'N/A',
            'size'       => $item['size'] ?? 'N/A',
            'quantity'   => $item['quantity'],
            'price'      => $item['price'],
            'image'      => $item['image'] ?? null, // use the stored image
        ];
    })->values()->toArray();

    $clients = User::whereIn('category', ['wholesale', 'retail'])->get();

    return view('sales.orders.create', [
        'orderItems' => $orderItems,
        'clients'    => $clients,
        'success'    => $request->session()->get('success'), // Pass success message
    ]);
}

    

    /**
     * Store sales order
     */
  public function store(Request $request)
{
    $request->validate([
        'products' => 'required|array',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'payment_method' => 'required|string',
        'address' => 'required|string',
        'client_id' => 'required|exists:users,id',
        'customer_name' => 'nullable|string|max:255',
    ]);

    $subtotal = 0;
    $orderItems = [];

    foreach ($request->products as $item) {
        $product = Product::findOrFail($item['product_id']);
        $price = $item['price'] ?? $product->price;
        $lineTotal = $price * $item['quantity'];
        $subtotal += $lineTotal;

        $orderItems[] = [
            'product_id' => $product->id,
            'name'       => $product->name,
            'quantity'   => $item['quantity'],
            'price'      => $price,
            'color'      => $item['color'] ?? 'N/A',
            'size'       => $item['size'] ?? 'N/A',
        ];
    }

    $gst = $subtotal * 0.18;
    $total = $subtotal + $gst;

    // 🔹 fetch client (user) to copy company details
    $client = \App\Models\User::findOrFail($request->client_id);

    $order = Order::create([
        'user_id'       => auth()->id(),
        'client_id'     => $request->client_id,
        'customer_name' => $request->customer_name,
        'cart_items'    => json_encode($orderItems, JSON_UNESCAPED_SLASHES),
        'subtotal'      => $subtotal,
        'gst'           => $gst,
        'total'         => $total,
        'payment_method'=> $request->payment_method,
        'status'        => 'pending',
        'address'       => $request->address,
        'transport_name'=> $request->transport_name ?? 'Not Assigned',
        'transport_address' => $request->transport_address ?? 'Not Assigned',
        'transport_id'  => $request->transport_id ?? 0,

        // 🔹 new company fields (copied from client profile)
        'company_name'   => $client->business_name ?? 'N/A',
        'company_address'=> $client->address ?? 'N/A',
        'company_gst'    => $client->gst_no ?? 'N/A',
    ]);

    session()->forget('sales_cart');

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order_id' => $order->id,
        ]);
    }

    return redirect()->route('sales.orders.create')
                    ->with('success', 'Order created successfully!');
}


/**
     * Show order details
     */
    public function show(Order $order)
{
    $orderItems = json_decode($order->cart_items, true);
    $client = $order->client; // make sure Order model has a 'client' relation
    return view('sales.orders.show', compact('order', 'orderItems', 'client'));
}

public function indexInvoices()
{
    $invoices = \App\Models\Order::with('client')->orderBy('created_at', 'desc')->get();
    dd($invoices);
    return view('sales.invoices.index', compact('invoices'));
}


public function myOrders()
{
    $userId = auth()->id();
    $orders = Order::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    return view('sales.my_orders', compact('orders'));
}




public function generateInvoice($orderId)
{
    $order = Order::with('client')->findOrFail($orderId);
    $cartItems = json_decode($order->cart_items, true);

    foreach ($cartItems as &$item) {
        $product = Product::find($item['product_id']);
        if ($product) {
            $item['name'] = $product->name;
            $item['price'] = $product->price;
        }
    }

    $user = auth()->user(); // pass your own company info

    return view('sales.orders.invoice', compact('order', 'cartItems', 'user'));
}


}
