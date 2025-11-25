@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 font-sans">
    <h1 class="text-3xl font-semibold text-gray-900 mb-8 border-b-2 border-gray-300 pb-2">Edit Purchase Order</h1>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-600 text-green-800 px-4 py-3 rounded mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-600 text-red-800 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200">
        <form action="{{ route('supplier-orders.update', $supplierOrder->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Supplier --}}
                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <select name="supplier_id" id="supplier_id" 
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-400 transition">
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $supplierOrder->supplier_id == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- PO Number --}}
                <div>
                    <label for="po_number" class="block text-sm font-medium text-gray-700 mb-1">PO Number</label>
                    <input type="text" name="po_number" id="po_number" 
                           value="{{ old('po_number', $supplierOrder->po_number) }}" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-400 transition" required>
                </div>

                @php
                    $companyGst = \App\Models\Settings::get('company_gst');
                    $companyState = $companyGst ? substr($companyGst, 0, 2) : null;

                    $supplierGst = $supplierOrder->supplier->gst_number ?? null;
                    $supplierState = $supplierGst && strlen($supplierGst) >= 2 ? substr($supplierGst, 0, 2) : null;

                    $subtotal = $supplierOrder->total_amount;

                    if ($companyState === $supplierState && $companyState !== null) {
                        $gstType = 'cgst';
                        $cgst = $subtotal * 0.025;
                        $sgst = $subtotal * 0.025;
                        $igst = 0;
                    } else {
                        $gstType = 'igst';
                        $igst = $subtotal * 0.05;
                        $cgst = 0;
                        $sgst = 0;
                    }

                    $totalWithGst = $subtotal + $cgst + $sgst + $igst;
                @endphp

                {{-- Total Amount (Before GST) --}}
<div>
    <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-1">
        Total Amount (Without GST)
    </label>
    <input type="number" name="total_amount" id="total_amount" step="0.01"
           value="{{ old('total_amount', $supplierOrder->total_amount) }}"
           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-400 transition"
           required>
</div>

{{-- Total Amount With GST (Read Only) --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Total Amount (With GST)
    </label>
    <input type="text"
           value="₹{{ number_format($totalWithGst, 2) }}"
           class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-gray-700"
           readonly>
</div>


                {{-- GST Breakdown --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">GST Breakdown</label>

                    <div class="p-4 bg-gray-100 rounded-lg text-sm text-gray-700 border border-gray-300">
                        <div><strong>Subtotal:</strong> ₹{{ number_format($subtotal, 2) }}</div>

                        @if($igst > 0)
                            <div><strong>IGST (5%):</strong> ₹{{ number_format($igst, 2) }}</div>
                        @else
                            <div><strong>CGST (2.5%):</strong> ₹{{ number_format($cgst, 2) }}</div>
                            <div><strong>SGST (2.5%):</strong> ₹{{ number_format($sgst, 2) }}</div>
                        @endif

                        <div class="mt-2 text-lg font-semibold text-gray-900">
                            Total with GST: ₹{{ number_format($totalWithGst, 2) }}
                        </div>
                    </div>
                </div>

                {{-- Paid Amount --}}
<div>
    <label for="paid_amount" class="block text-sm font-medium text-gray-700 mb-1">Paid Amount</label>
    <input type="number" name="paid_amount" id="paid_amount" step="0.01"
           value="{{ old('paid_amount', $supplierOrder->paid_amount) }}"
           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-400 transition">
</div>

{{-- Hidden GST Total for JavaScript --}}
<input type="hidden" id="total_with_gst" value="{{ $totalWithGst }}">


                {{-- Payment Status --}}
                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                    <select name="payment_status" id="payment_status"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-400 transition">
                        <option value="pending" {{ $supplierOrder->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="partial" {{ $supplierOrder->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="paid" {{ $supplierOrder->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>

                {{-- Order Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                    <select name="status" id="status"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-400 transition">
                        <option value="pending" {{ $supplierOrder->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $supplierOrder->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="delivered" {{ $supplierOrder->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>

                {{-- Order Date --}}
                <div>
                    <label for="order_date" class="block text-sm font-medium text-gray-700 mb-1">Order Date</label>
                    <input type="date" name="order_date" id="order_date" 
                           value="{{ old('order_date', $supplierOrder->order_date?->format('Y-m-d')) }}" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-400 transition">
                </div>

            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('supplier-orders.index') }}" 
                   class="bg-gray-200 text-gray-800 px-6 py-2 rounded shadow hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-gray-900 text-white px-6 py-2 rounded shadow hover:bg-gray-800 transition">
                    Update Order
                </button>
            </div>
        </form>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    let paidAmount = document.getElementById("paid_amount");
    let totalWithGst = document.getElementById("total_with_gst").value;

    // Only auto-fill if paid amount is empty or zero
    if (!paidAmount.value || Number(paidAmount.value) === 0) {
        paidAmount.value = Number(totalWithGst).toFixed(2);
    }
});
</script>

@endsection
