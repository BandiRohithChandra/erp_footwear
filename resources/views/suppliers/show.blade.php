@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 font-sans space-y-8">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Supplier Details</h1>
        <a href="{{ route('suppliers.index') }}"
           class="bg-gray-200 text-gray-800 px-4 py-2 rounded shadow hover:bg-gray-300 transition">
           ← Back
        </a>
    </div>

    {{-- Supplier Info Card --}}
    <div class="bg-gray-50 shadow-md rounded-xl p-6 border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Supplier Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><span class="font-medium text-gray-700">Supplier ID:</span> {{ 'SUP-' . str_pad($supplier->id, 5, '0', STR_PAD_LEFT) }}</div>
            <div><span class="font-medium text-gray-700">Business Name:</span> {{ $supplier->business_name ?? '-' }}</div>
            <div><span class="font-medium text-gray-700">Name:</span> {{ $supplier->name }}</div>
            <div><span class="font-medium text-gray-700">Email:</span> {{ $supplier->email ?? 'N/A' }}</div>
            <div><span class="font-medium text-gray-700">Phone:</span> {{ $supplier->phone ?? 'N/A' }}</div>
            <div><span class="font-medium text-gray-700">GST Number:</span> {{ $supplier->gst_number ?? 'N/A' }}</div>

            {{-- ✅ What They Supply --}}
            <div class="md:col-span-2">
                <span class="font-medium text-gray-700">Supplied Materials:</span>
                <span class="ml-1 text-gray-800">
                    {{ $supplier->material_types ?? 'N/A' }}
                </span>
            </div>

            <div class="md:col-span-2">
                <span class="font-medium text-gray-700">Address:</span> 
                <span class="ml-1 text-gray-800">{{ $supplier->address ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    {{-- Purchase Orders --}}
    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Purchase Orders</h2>

        @if($supplier->supplierOrders->isEmpty())
            <p class="text-gray-500">No purchase orders found for this supplier.</p>
        @else
            @foreach($supplier->supplierOrders as $order)
                <div class="bg-white shadow-md rounded-xl p-6 border border-gray-200 hover:shadow-lg transition">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                        <div class="space-y-1">
                            <div><span class="font-medium text-gray-700">PO Number:</span> {{ $order->po_number }}</div>
                            <div><span class="font-medium text-gray-700">Order Date:</span> {{ $order->order_date?->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div class="space-y-1 mt-4 md:mt-0 text-right">
                            <div><span class="font-medium text-gray-700">Total Amount:</span> ₹{{ number_format($order->total_amount, 2) }}</div>
                            <div><span class="font-medium text-gray-700">Paid:</span> ₹{{ number_format($order->paid_amount, 2) }}</div>
                            <div>
                                @php
                                    $paymentClasses = [
                                        'paid' => 'bg-green-100 text-green-700',
                                        'partial' => 'bg-yellow-100 text-yellow-700',
                                        'pending' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $paymentClasses[$order->payment_status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                            <div class="capitalize"><span class="font-medium text-gray-700">Status:</span> {{ $order->status }}</div>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="mt-4">
                        <h3 class="font-medium text-gray-700 mb-2">Items:</h3>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($order->items as $item)
                                <li class="bg-gray-100 px-2 py-1 rounded">
                                    Type: <span class="font-medium">{{ $item['type'] ?? '-' }}</span>, 
                                    ID: {{ $item['id'] ?? '-' }}, 
                                    Quantity: {{ $item['quantity'] ?? '-' }}, 
                                    Price: ₹{{ number_format($item['price'] ?? 0, 2) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</div>
@endsection
