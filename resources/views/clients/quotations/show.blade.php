@extends('layouts.app')

@section('content')
<div class="container mx-auto p-8" style="font-family: 'Poppins', sans-serif; max-width:900px;">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8 border-b pb-4">
        <div>
            <img src="{{ $quotation->company_logo ?? '' }}" alt="Company Logo" style="max-height:60px;">
            <p class="text-sm text-gray-600 mt-1">{{ $quotation->company_name ?? 'CREATIVE SHOES' }}</p>
            <p class="text-sm text-gray-600">{{ $quotation->company_address ?? 'Company Address' }}</p>
            <p class="text-sm text-gray-600">GST: {{ $quotation->company_gst ?? '27AMRPK6699L1ZV' }}</p>
            <p class="text-sm text-gray-600">Phone: {{ $quotation->company_phone ?? '+++++++++9' }}</p>
            <p class="text-sm text-gray-600">Email: {{ $quotation->company_email ?? 'kiran@gmail.com' }}</p>
        </div>

        <div class="text-right">
            <h1 class="text-3xl font-bold text-gray-800">Quotation</h1>
            <p class="text-sm text-gray-600">Quotation No: <strong>{{ $quotation->quotation_no ?? 'QTN-' . str_pad($quotation->id,5,'0',STR_PAD_LEFT) }}</strong></p>
            <p class="text-sm text-gray-600">Date: {{ $quotation->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    {{-- Client Details --}}
    <div class="flex justify-between mb-6">
        <div>
            <h4 class="font-semibold text-gray-700 mb-2">Bill To:</h4>
            <p class="text-gray-700">{{ $quotation->client->business_name ?? $quotation->client->name ?? 'N/A' }}</p>
            <p class="text-gray-600">GST: {{ $quotation->client->gst_no ?? 'N/A' }}</p>
            <p class="text-gray-600">{{ $quotation->client->address ?? 'N/A' }}</p>
            <p class="text-gray-600">Phone: {{ $quotation->client->phone ?? 'N/A' }}</p>
            <p class="text-gray-600">Email: {{ $quotation->client->email ?? 'N/A' }}</p>
        </div>
        <div>
            <h4 class="font-semibold text-gray-700 mb-2">Ship To (if different):</h4>
            <p class="text-gray-600">{{ $quotation->client->shipping_address ?? 'Same as billing' }}</p>
        </div>
    </div>

    {{-- Products Table --}}
    <table class="w-full border-collapse mb-6">
        <thead>
            <tr class="bg-gray-200 text-gray-700 text-left">
                <th class="p-3 border border-gray-300">S.No</th>
                <th class="p-3 border border-gray-300">Product / Service</th>
                <th class="p-3 border border-gray-300">Description</th>
                <th class="p-3 border border-gray-300 text-center">Quantity</th>
                <th class="p-3 border border-gray-300 text-right">Unit Price</th>
                <th class="p-3 border border-gray-300 text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->products as $index => $product)
            @php
                $productTotal = ($product->pivot->quantity ?? 1) * ($product->pivot->unit_price ?? 0);
            @endphp
            <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                <td class="p-3 border border-gray-300">{{ $index + 1 }}</td>
                <td class="p-3 border border-gray-300">{{ $product->name ?? 'N/A' }}</td>
                <td class="p-3 border border-gray-300">
                    @if($product->pivot->variations)
                        @foreach($product->pivot->variations as $variation)
                            Color: {{ $variation['color'] ?? '-' }}<br>
                            @if(!empty($variation['sizes']))
                                @foreach($variation['sizes'] as $size => $qty)
                                    Size {{ $size }} ({{ $qty }})<br>
                                @endforeach
                            @endif
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td class="p-3 border border-gray-300 text-center">{{ $product->pivot->quantity ?? 1 }}</td>
                <td class="p-3 border border-gray-300 text-right">₹{{ number_format($product->pivot->unit_price ?? 0,2) }}</td>
                <td class="p-3 border border-gray-300 text-right">₹{{ number_format($productTotal,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals Section --}}
    <div class="flex justify-end mb-6">
        <table class="w-1/2 border-collapse">
            <tr>
                <td class="p-2 text-gray-700 font-semibold border">Subtotal</td>
                <td class="p-2 text-right border">₹{{ number_format($quotation->subtotal ?? 0,2) }}</td>
            </tr>
            <tr>
                <td class="p-2 text-gray-700 font-semibold border">Tax ({{ $quotation->tax_percentage ?? 0 }}%)</td>
                <td class="p-2 text-right border">₹{{ number_format($quotation->tax ?? 0,2) }}</td>
            </tr>
            <tr class="bg-gray-100">
                <td class="p-2 text-gray-700 font-semibold border">Grand Total</td>
                <td class="p-2 text-right border font-bold">₹{{ number_format($quotation->grand_total ?? 0,2) }}</td>
            </tr>
        </table>
    </div>

    {{-- Notes / Terms --}}
    <div class="mb-6">
        <h4 class="font-semibold text-gray-700 mb-2">Terms & Notes:</h4>
        <ul class="list-disc list-inside text-gray-600">
            <li>Payment Terms: 50% advance, 50% on delivery.</li>
            <li>Quotation valid for 30 days from date of issue.</li>
            <li>Delivery: As per agreement.</li>
        </ul>
    </div>

    {{-- Action Buttons --}}
    <div class="text-center mt-6">
        <button onclick="printQuotation()" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 mr-3">Print Quotation</button>
        <button onclick="downloadPDF()" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">Download PDF</button>
    </div>

</div>

{{-- Scripts --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
function printQuotation() {
    window.print();
}

function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');
    const element = document.querySelector('.container');
    html2canvas(element, { scale: 2 }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const imgProps = doc.getImageProperties(imgData);
        const pdfWidth = doc.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
        doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        doc.save('Quotation-{{ str_pad($quotation->id,5,'0',STR_PAD_LEFT) }}.pdf');
    });
}
</script>
@endsection
