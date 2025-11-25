@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6 font-sans">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Supplier Purchase Orders</h1>

    {{-- ✅ Success Message --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- ➕ Add New Purchase Order --}}
    <div class="mb-6">
        <a href="{{ route('supplier-orders.create') }}"
           class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition">
            + New Purchase Order
        </a>
    </div>

    {{-- 📊 Table Card --}}
    <div class="overflow-x-auto bg-white shadow-xl rounded-xl border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-gray-700 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">PO Number</th>
                    <th class="px-6 py-3 text-left font-semibold">Supplier</th>
                    <th class="px-6 py-3 text-left font-semibold">Total Amount</th>
                    <th class="px-6 py-3 text-left font-semibold">Paid</th>
                    <th class="px-6 py-3 text-left font-semibold">Payment Status</th>
                    <th class="px-6 py-3 text-left font-semibold">Order Status</th>
                    <th class="px-6 py-3 text-left font-semibold">Date</th>
                    <th class="px-6 py-3 text-left font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition cursor-pointer"
                        onclick="if(!event.target.closest('button, a, select')){window.location='{{ route('supplier-orders.show', $order->id) }}'}">
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $order->po_number }}</td>
                        <td class="px-6 py-3">{{ $order->supplier->name ?? 'N/A' }}</td>
                        @php
    // Company GST from settings
    $companyGst = \App\Models\Settings::get('company_gst');
    $companyState = $companyGst ? substr($companyGst, 0, 2) : null;

    // Supplier GST
    $supplierGst = $order->supplier->gst_number ?? null;
    $supplierState = $supplierGst && strlen($supplierGst) >= 2 ? substr($supplierGst, 0, 2) : null;

    // Detect GST Type
    if ($companyState === $supplierState && $companyState !== null) {
        $gstType = 'cgst';
    } else {
        $gstType = 'igst';
    }

    $subtotal = $order->total_amount;

    if ($gstType === 'igst') {
        $igst = $subtotal * 0.05;
        $cgst = 0;
        $sgst = 0;
    } else {
        $cgst = $subtotal * 0.025;
        $sgst = $subtotal * 0.025;
        $igst = 0;
    }

    $totalWithGst = $subtotal + $cgst + $sgst + $igst;
@endphp

<td class="px-6 py-3">
    <div>₹{{ number_format($totalWithGst, 2) }}</div>
    <div class="text-xs text-gray-500">
        @if($igst > 0)
            IGST (5%): ₹{{ number_format($igst, 2) }}
        @else
            CGST (2.5%): ₹{{ number_format($cgst, 2) }},
            SGST (2.5%): ₹{{ number_format($sgst, 2) }}
        @endif
    </div>
</td>

                        <td class="px-6 py-3">₹{{ number_format($order->paid_amount, 2) }}</td>
                        <td class="px-6 py-3">
                            @php
                                $paymentClasses = [
                                    'paid' => 'bg-green-100 text-green-700',
                                    'partial' => 'bg-yellow-100 text-yellow-700',
                                    'pending' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $paymentClasses[$order->payment_status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <form action="{{ route('supplier-orders.updateStatus', $order->id) }}" method="POST" onsubmit="event.stopPropagation()">
                                @csrf
                                @method('PATCH')
                                <select name="status" 
                                        onchange="this.form.submit(); event.stopPropagation();" 
                                        class="text-xs py-1 rounded border border-gray-300 hover:border-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-300">
                                    <option value="pending" {{ $order->status=='pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status=='processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="delivered" {{ $order->status=='delivered' ? 'selected' : '' }}>Delivered</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-3">{{ $order->order_date?->format('d M Y') ?? '-' }}</td>
                        <td class="px-6 py-3 flex flex-wrap gap-2">
                            <a href="{{ route('supplier-orders.show', $order->id) }}" 
                               class="text-blue-600 hover:underline text-sm">View</a>
                            <a href="{{ route('supplier-orders.edit', $order->id) }}" 
                               class="text-yellow-600 hover:underline text-sm">Edit</a>
                            <form action="{{ route('supplier-orders.destroy', $order->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this order?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No supplier orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
