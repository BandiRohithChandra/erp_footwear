@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    <!-- Page Heading -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4 md:mb-0">Order #{{ $order->id }}</h1>
        <button onclick="history.back()" class="flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back
        </button>
    </div>

    <!-- Order Info -->
    <div class="bg-white shadow-md rounded-2xl p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p><span class="font-semibold text-gray-700">Customer:</span> {{ $order->customer_name ?? ($order->client->name ?? 'N/A') }}</p>
                <p><span class="font-semibold text-gray-700">Status:</span> 
                    <span class="px-2 py-1 rounded-full text-white font-semibold text-sm 
                        @if($order->status == 'pending') bg-yellow-500 
                        @elseif($order->status == 'completed') bg-green-500 
                        @elseif($order->status == 'cancelled') bg-red-500 
                        @else bg-gray-400 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                <p><span class="font-semibold text-gray-700">Payment Method:</span> {{ $order->payment_method }}</p>
            </div>
            <div>
                <p><span class="font-semibold text-gray-700">Total:</span> 
                    <span class="text-green-700 font-bold text-lg">₹{{ number_format($order->total, 2) }}</span>
                </p>
                <p><span class="font-semibold text-gray-700">Address:</span> {{ $order->address }}</p>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Products</h2>

    @php
        $cartItems = is_array($order->cart_items) ? $order->cart_items : json_decode($order->cart_items, true) ?? [];
    @endphp

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Product</th>
                    <th class="px-4 py-3 text-center">Quantity</th>
                    <th class="px-4 py-3 text-right">Price</th>
                    <th class="px-4 py-3 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-3 flex items-center gap-2">
                            @if(!empty($item['image']) && file_exists(public_path('storage/' . $item['image'])))
                                <img src="{{ asset('storage/' . $item['image']) }}" class="w-12 h-12 object-cover rounded-lg shadow-sm" alt="{{ $item['name'] }}">
                            @endif
                            <span>{{ $item['name'] ?? 'N/A' }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">{{ $item['quantity'] ?? 0 }}</td>
                        <td class="px-4 py-3 text-right">₹{{ number_format($item['price'] ?? 0, 2) }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-800">₹{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Order Summary -->
    <div class="mt-6 flex justify-end">
        <div class="w-full md:w-96 bg-green-50 rounded-2xl shadow-md p-6">
            @php
                $subtotal = collect($cartItems)->sum(function($item) { return ($item['price'] ?? 0) * ($item['quantity'] ?? 0); });
                $gstRate = 0.18;
                $gstAmount = $subtotal * $gstRate;
                $total = $subtotal + $gstAmount;
            @endphp

            <h3 class="text-lg font-semibold mb-4 border-b border-green-400 pb-2">Order Summary</h3>
            <div class="flex justify-between mb-2 text-gray-700">
                <span>Subtotal</span>
                <span>₹{{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between mb-2 text-gray-700">
                <span>GST (18%)</span>
                <span>₹{{ number_format($gstAmount, 2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-green-700 text-lg mb-2">
                <span>Total</span>
                <span>₹{{ number_format($total, 2) }}</span>
            </div>
        </div>
    </div>

</div>
@endsection
