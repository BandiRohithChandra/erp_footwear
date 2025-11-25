@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Total Sales Details</h1>

    <!-- Summary -->
    <div class="mb-6">
        <p class="text-xl font-semibold">Total Sales Amount: ₹{{ number_format($totalSales, 2) }}</p>
        <p class="text-xl font-semibold">Completed Orders: {{ $completedOrdersCount }}</p>
    </div>

    <!-- Orders Table -->
    <div class="bg-white shadow-lg rounded-xl p-6 overflow-x-auto">
        <table class="min-w-full table-auto text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                <tr>
                    <th class="px-4 py-2">Order ID</th>
                    <th class="px-4 py-2">Customer</th>
                    <th class="px-4 py-2">Total Amount</th>
                    <th class="px-4 py-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($completedOrders as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">#{{ $order->id }}</td>
                        <td class="px-4 py-2">{{ $order->customer_name ?? ($order->user->name ?? ($order->client->name ?? 'N/A')) }}</td>
                        <td class="px-4 py-2">₹{{ number_format($order->total,2) }}</td>
                        <td class="px-4 py-2">{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 py-4">No completed orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
