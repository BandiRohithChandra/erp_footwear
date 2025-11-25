<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class AdminSalesController extends Controller
{


 public function index()
    {
        // Fetch orders with related user and client
        // Only include partially paid / unpaid orders
        $orders = Order::with(['user', 'client'])
            ->whereRaw('total - IFNULL(paid_amount, 0) > 0')
            ->latest()
            ->take(50)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'total' => $order->total ?? 0,
                    'paid_amount' => $order->paid_amount ?? 0,
                    'status' => $order->status,
                    'created_at' => $order->created_at->toDateTimeString(),
                    'customer_name' => $order->customer_name ?? 'N/A',
                    'client' => [
                        'name' => $order->client->name ?? 'N/A',
                    ],
                    'user' => [
                        'name' => $order->user->name ?? 'N/A',
                    ],
                ];
            });

        // Total sales (completed + pending) – keep total for all orders if needed
        $totalSales = Order::sum('total');

        // Completed orders count
        $completedOrdersCount = Order::where('status', ['paid', 'delivered'])->count();

        return view('admin.sales.total', compact('orders', 'totalSales', 'completedOrdersCount'));
    }

    public function details()
    {
        // Fetch all completed orders as details
        $completedOrders = Order::where('status', ['paid', 'delivered'])->latest()->get();

        $totalSales = Order::sum('total');
        $completedOrdersCount = $completedOrders->count();

        return view('admin.sales.details', compact('completedOrders', 'totalSales', 'completedOrdersCount'));
    }
}
