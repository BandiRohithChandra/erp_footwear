<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
   public function show($id)
{
    // Fetch the order with client relation
    $order = Order::with('client')->findOrFail($id);

    // Decode cart items (assuming stored as JSON)
    $cartItems = is_string($order->cart_items) ? json_decode($order->cart_items, true) : $order->cart_items;

    // Fetch the logged-in user/company info
    $company = auth()->user();

    // Calculate paid and due amounts
    $paidAmount = $order->paid ?? 0;
    $dueAmount  = ($order->total ?? 0) - $paidAmount;

    return view('admin.orders.invoice', [
        'order'      => $order,
        'cartItems'  => $cartItems,
        'company'    => $company,
        'paidAmount' => $paidAmount,
        'dueAmount'  => $dueAmount,
    ]);
}

}
