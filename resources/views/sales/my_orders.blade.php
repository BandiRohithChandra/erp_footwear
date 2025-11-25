@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-3xl font-bold text-[#1a2a4e] mb-6 text-center md:text-left">My Orders</h2>

    @if($orders->isEmpty())
        <div class="bg-white text-gray-600 text-lg p-8 rounded-xl shadow-md text-center">
            <p>You have not placed any orders yet.</p>
        </div>
    @else
        @foreach($orders as $order)
        @php
            $cart_items = json_decode($order->cart_items, true) ?? [];
        @endphp

        <div class="bg-white border border-gray-200 rounded-xl shadow-md mb-8 overflow-hidden hover:shadow-lg transition">
            
            {{-- Header --}}
            <div class="flex flex-wrap justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div>
                    <h3 class="text-lg font-semibold text-[#1a2a4e]">Order #{{ $order->id }}</h3>
                    <p class="text-sm text-gray-600">
                        Placed on: {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : 'N/A' }}
                    </p>
                </div>
                <span class="px-4 py-1.5 rounded-full text-sm font-bold capitalize
                    @if($order->status == 'placed') bg-blue-100 text-blue-700 
                    @elseif($order->status == 'completed') bg-green-100 text-green-700 
                    @elseif($order->status == 'cancelled') bg-red-100 text-red-700 
                    @elseif($order->status == 'pending') bg-yellow-100 text-yellow-700 
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            {{-- Items Table (Desktop) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse min-w-[500px]">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <th class="px-4 py-3">Product</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Unit Price</th>
                            <th class="px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart_items as $item)
                        @php
                            $qty = $item['quantity'] ?? 0;
                            $price = $item['price'] ?? 0;
                            $total_price = $qty * $price;
                        @endphp
                        <tr class="border-b last:border-0">
                            <td class="px-4 py-3">{{ $item['name'] ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $qty }}</td>
                            <td class="px-4 py-3">₹{{ number_format($price, 2) }}</td>
                            <td class="px-4 py-3 font-semibold text-[#1a2a4e]">₹{{ number_format($total_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Items Mobile Card View --}}
            <div class="block md:hidden p-4 space-y-4">
                @foreach($cart_items as $item)
                @php
                    $qty = $item['quantity'] ?? 0;
                    $price = $item['price'] ?? 0;
                    $total_price = $qty * $price;
                @endphp
                <div class="border-b last:border-0 pb-3">
                    <strong class="block text-base font-semibold text-[#1a2a4e]">
                        {{ $item['name'] ?? 'N/A' }}
                    </strong>
                    <span class="block text-sm text-gray-600">Qty: {{ $qty }}</span>
                    <span class="block text-sm text-gray-600">Unit: ₹{{ number_format($price, 2) }}</span>
                    <span class="block text-sm text-gray-600">Total: ₹{{ number_format($total_price, 2) }}</span>
                </div>
                @endforeach
            </div>

            {{-- Summary Section --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6">
                <div>
                    <h4 class="text-lg font-semibold text-[#1a2a4e] mb-3">Shipping Details</h4>
                    <p class="text-gray-700"><strong>Name:</strong> {{ $order->transport_name ?? '-' }}</p>
                    <p class="text-gray-700"><strong>Address:</strong> {{ $order->transport_address ?? '-' }}</p>
                    <p class="text-gray-700"><strong>ID:</strong> {{ $order->transport_id ?? '-' }}</p>
                </div>

                <div>
                    <h4 class="text-lg font-semibold text-[#1a2a4e] mb-3">Order Summary</h4>
                    <p class="text-gray-700"><strong>Subtotal:</strong> ₹{{ number_format($order->subtotal, 2) }}</p>
                    <p class="text-gray-700"><strong>GST:</strong> ₹{{ number_format($order->gst, 2) }}</p>
                    <p class="text-gray-900 font-bold text-lg mt-2"><strong>Total:</strong> ₹{{ number_format($order->total, 2) }}</p>
                    <p class="text-gray-700"><strong>Payment:</strong> {{ ucfirst($order->payment_method) }}</p>
                    <p class="text-gray-700"><strong>Delivery Address:</strong> {{ $order->address ?? '-' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
