@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6 space-y-6">

    <!-- Back Button -->
    <div>
        <button type="button" onclick="history.back()" 
            class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back
        </button>
    </div>

    <!-- Print & Download Buttons -->
    <div class="flex justify-end gap-3 mb-4">
        <button onclick="window.print()" 
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition no-print">
            Print
        </button>

        <form action="{{ route('orders.download', $order->id) }}" method="GET">
            <button type="submit" 
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition no-print">
                Download PDF
            </button>
        </form>
    </div>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b pb-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Order #{{ $order->id }}</h2>
            <p class="text-sm text-gray-500 mt-1">Order Date: {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="mt-3 sm:mt-0 flex flex-col sm:items-end">
            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full 
                {{ $order->status === 'placed' ? 'bg-blue-100 text-blue-800' : ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                {{ ucfirst($order->status) }}
            </span>
            <p class="text-sm text-gray-500 mt-1">Payment Method</p>
            @php
                $paymentMethods = ['cod'=>'Cash on Delivery','online'=>'Online Payment','upi'=>'UPI Payment'];
            @endphp
            <p class="font-medium text-gray-900">{{ $paymentMethods[strtolower($order->payment_method)] ?? ucfirst($order->payment_method) }}</p>
        </div>
    </div>

    @php
        $cartItems = is_array($order->cart_items) ? $order->cart_items : json_decode($order->cart_items, true) ?? [];
    @endphp

    <!-- Buyer & Vendor Details Side by Side -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Buyer Details -->
        <div class="p-4 bg-white border rounded shadow-sm">
            <h3 class="font-semibold text-gray-800 mb-3">Buyer Details</h3>
            <p><strong>Name:</strong> {{ $order->customer_name ?? '-' }}</p>
            <p><strong>Address:</strong> {{ $order->address ?? '-' }}</p>
            <p><strong>City / State / Pin:</strong> {{ $order->city ?? '-' }} / {{ $order->state ?? '-' }} / {{ $order->pincode ?? '-' }}</p>
            <p><strong>GST:</strong> {{ $order->gst ?? '-' }}</p>
            <p><strong>Mobile:</strong> {{ $order->mobile ?? '-' }}</p>
            <p><strong>PAN:</strong> {{ $order->pan_no ?? '-' }}</p>
            <p><strong>Email:</strong> {{ $order->email ?? '-' }}</p>
        </div>

        <!-- Vendor Details -->
        <div class="p-4 bg-white border rounded shadow-sm">
            <h3 class="font-semibold text-gray-800 mb-3">Vendor Details</h3>
            <p><strong>Company:</strong> CREATIVE SHOES</p>
            <p><strong>Owner:</strong> SIRATULLAH JAMIRULLAH KHAN</p>
            <p><strong>Business Type:</strong> Proprietorship</p>
            <p><strong>Address:</strong> grd-floor, room no.5, municipal chawl no.6, transit camp road, Byculla, Mumbai, Maharashtra, 400011</p>
            <p><strong>GSTIN:</strong> 27AMRPK6699L1ZV</p>
            <p><strong>Registration Date:</strong> 01/07/2017</p>
        </div>
    </div>

    <!-- Order Information -->
    <div class="p-4 bg-white border rounded shadow-sm">
        <h3 class="font-semibold text-gray-800 mb-3">Order Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p><strong>Order No:</strong> {{ $order->id }}</p>
            <p><strong>STO:</strong> {{ $order->sto ?? '-' }}</p>
            <p><strong>PO Type:</strong> {{ $order->po_type ?? '-' }}</p>
            <p><strong>Ordered By:</strong> {{ $order->ordered_by ?? '-' }}</p>
            <p class="md:col-span-2"><strong>Article No:</strong> 
                @foreach($cartItems as $item)
                    {{ $item['product_id'] ?? '-' }}@if(!$loop->last), @endif
                @endforeach
            </p>
        </div>
    </div>

    <!-- Process Flow & Sole Details -->
    <div class="p-4 bg-white border rounded shadow-sm">
        <h3 class="font-semibold text-gray-800 mb-3">Process Flow & Sole Details</h3>
        @foreach($cartItems as $item)
            <div class="mb-4 p-3 border rounded bg-gray-50">
                <p><strong>Product:</strong> {{ $item['name'] ?? '-' }}</p>
                <p><strong>Article No:</strong> {{ $item['product_id'] ?? '-' }}</p>
                <p><strong>Sole Name:</strong> {{ $item['sole_name'] ?? '-' }}</p>
                <p><strong>Sole Color:</strong> {{ $item['sole_color'] ?? '-' }}</p>
                <p><strong>Process Flow:</strong> {{ $item['process_flow'] ?? '-' }}</p>
            </div>
        @endforeach
    </div>

    <!-- Table for Sizes, Colors, Costs -->
    @php $allSizes = [6,7,8,9,10,11,12]; @endphp
    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
        <table class="w-full text-sm text-left border-collapse min-w-[1000px]">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-3 py-2">S.No</th>
                    <th class="px-3 py-2">Color</th>
                    <th class="px-3 py-2">Color Code</th>
                    @foreach($allSizes as $size)<th class="px-3 py-2 text-center">{{ $size }}</th>@endforeach
                    <th class="px-3 py-2 text-right">Cost (Disc)</th>
                    <th class="px-3 py-2 text-right">MRP</th>
                    <th class="px-3 py-2 text-right">Total MRP</th>
                </tr>
            </thead>
            <tbody>
                @php $sno=1; $grandTotalCost=0; $grandTotalMRP=0; @endphp
                @foreach($cartItems as $item)
                    @php
                        $costDisc = $item['unit_price'] ?? $item['price'] ?? 0;
                        $mrp = $item['price'] ?? 0;
                        $size = $item['size'] ?? null;
                        $qty = $item['quantity'] ?? 0;
                        $totalMRP = $mrp * $qty;
                        $grandTotalCost += $costDisc * $qty;
                        $grandTotalMRP += $totalMRP;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2">{{ $sno++ }}</td>
                        <td class="px-3 py-2">{{ $item['color'] ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $item['color_code'] ?? '-' }}</td>
                        @foreach($allSizes as $s)
                            <td class="px-3 py-2 text-center">{{ ($size==$s)?$qty:0 }}</td>
                        @endforeach
                        <td class="px-3 py-2 text-right">₹{{ number_format($costDisc,2) }}</td>
                        <td class="px-3 py-2 text-right">₹{{ number_format($mrp,2) }}</td>
                        <td class="px-3 py-2 text-right">₹{{ number_format($totalMRP,2) }}</td>
                    </tr>
                @endforeach
                <tr class="font-semibold bg-gray-100">
                    <td colspan="{{3+count($allSizes)}}" class="text-right px-3 py-2">Total Cost</td>
                    <td class="text-right px-3 py-2">₹{{ number_format($grandTotalCost,2) }}</td>
                </tr>
                <tr class="font-semibold bg-gray-100">
                    <td colspan="{{3+count($allSizes)}}" class="text-right px-3 py-2">Total MRP</td>
                    <td class="text-right px-3 py-2">₹{{ number_format($grandTotalMRP,2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Tax & Totals -->
    <div class="p-4 bg-white border rounded shadow-sm">
        <h3 class="font-semibold text-gray-800 mb-3">Tax & Totals</h3>
        @php
            $tradeDisc = $order->trade_discount ?? 0;
            $taxableValue = $grandTotalCost - $tradeDisc;
            $buyerState = strtolower($order->state ?? '');
            $vendorState = 'maharashtra';
            $cgst = $sgst = $igst = 0;
            if($buyerState === strtolower($vendorState)){
                $cgst = $taxableValue*0.09;
                $sgst = $taxableValue*0.09;
            } else { $igst = $taxableValue*0.18; }
            $grandTotal = $taxableValue + $cgst + $sgst + $igst;
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p><strong>Total Cost:</strong> ₹{{ number_format($grandTotalCost,2) }}</p>
            <p><strong>Less Trade Disc:</strong> ₹{{ number_format($tradeDisc,2) }}</p>
            <p><strong>Total Taxable Value:</strong> ₹{{ number_format($taxableValue,2) }}</p>
            <p><strong>CGST 9%:</strong> ₹{{ number_format($cgst,2) }}</p>
            <p><strong>SGST 9%:</strong> ₹{{ number_format($sgst,2) }}</p>
            <p><strong>IGST 18%:</strong> ₹{{ number_format($igst,2) }}</p>
            <p class="md:col-span-2 text-right font-bold text-lg">Grand Total: ₹{{ number_format($grandTotal,2) }}</p>
        </div>
    </div>

</div>

<style>
@media print {
    .no-print {
        display: none;
    }
}
</style>
@endsection
