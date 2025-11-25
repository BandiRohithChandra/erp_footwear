@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    <!-- Back Button – hidden in print -->
    <div class="max-w-6xl mx-auto pt-6 px-4 no-print">
        <a href="{{ url()->previous() }}" class="back-btn">
            <svg class="back-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 19l-7-7 7-7"></path>
            </svg>
            Back
        </a>
    </div>

    <!-- ==== PRINTABLE QUOTATION ==== -->
    <div class="printable-quotation container mx-auto p-6 md:p-8 max-w-6xl"
         style="font-family: 'Inter', sans-serif;">
        <div class="quotation-content bg-white rounded-2xl shadow-xl p-8 md:p-10 border border-gray-200">

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b border-gray-200 pb-6">
                <div class="mb-4 md:mb-0">

    @php
        $logo = \App\Models\Settings::get('company_logo');
    @endphp

    <!-- Company Logo -->
    <img src="{{ $logo ? asset($logo) : 'https://placehold.co/150x50?text=Logo' }}"
         alt="Company Logo"
         class="max-h-16 mb-3">

    <!-- Company Name -->
    <p class="text-sm text-gray-600 font-medium">
        {{ \App\Models\Settings::get('company_name') ?? 'CREATIVE SHOES' }}
    </p>

    <!-- Address -->
    <p class="text-sm text-gray-500">
        {{ \App\Models\Settings::get('company_address') ?? 'Company Address' }}
    </p>

    <!-- GST -->
    <p class="text-sm text-gray-500">
        GST: {{ \App\Models\Settings::get('company_gst') ?? '27AMRPK6699L1ZV' }}
    </p>

    <!-- Phone -->
    <p class="text-sm text-gray-500">
        Phone: {{ \App\Models\Settings::get('company_phone') ?? '+++++++++9' }}
    </p>

    <!-- Email -->
    <p class="text-sm text-gray-500">
        Email: {{ \App\Models\Settings::get('company_email') ?? 'info@example.com' }}
    </p>
</div>

                <div class="text-left md:text-right">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Quotation</h1>
                    <p class="text-sm text-gray-600">Quotation No: <span class="font-semibold">QUO-{{ str_pad($quotation->id, 5, '0', STR_PAD_LEFT) }}</span></p>
                    <p class="text-sm text-gray-600">Date: <span class="font-semibold">{{ $quotation->created_at->format('d/m/Y') }}</span></p>
                </div>
            </div>

            <!-- Client Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
    <h4 class="font-semibold text-gray-700 mb-3 text-lg">Bill To</h4>

    <p class="text-gray-700 font-medium">
        {{ $quotation->client->business_name ?? $quotation->client->name ?? 'N/A' }}
    </p>

    {{-- Brand --}}
    <p class="text-sm text-gray-600">
        Brand: {{ $quotation->brand_name ?? 'N/A' }}
    </p>

    {{-- GST --}}
    <p class="text-sm text-gray-600">
        GST: {{ $quotation->client->gst_no ?? 'N/A' }}
    </p>

    {{-- Address --}}
    <p class="text-sm text-gray-600">
        {{ $quotation->client->address ?? 'N/A' }}
    </p>

    {{-- Phone --}}
    <p class="text-sm text-gray-600">
        Phone: {{ $quotation->client->phone ?? 'N/A' }}
    </p>

    {{-- Email --}}
    <p class="text-sm text-gray-600">
        Email: {{ $quotation->client->email ?? 'N/A' }}
    </p>
</div>

                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h4 class="font-semibold text-gray-700 mb-3 text-lg">Ship To</h4>
                    <p class="text-sm text-gray-600">{{ $quotation->client->shipping_address ?? 'Same as billing' }}</p>
                </div>
            </div>

            <!-- Products Table -->
            <div class="overflow-x-auto mb-8">
                <table class="w-full border-collapse bg-white rounded-lg shadow-sm">
                    <thead class="bg-gradient-to-r from-indigo-50 to-blue-50">
                        <tr class="text-gray-700 text-left">
                            <th class="p-4 border-b border-gray-200 text-sm font-semibold">S.No</th>
                            <th class="p-4 border-b border-gray-200 text-sm font-semibold">Product</th>
                            <th class="p-4 border-b border-gray-200 text-sm font-semibold">Variations</th>
                            <th class="p-4 border-b border-gray-200 text-sm font-semibold text-center">Qty</th>
                            <th class="p-4 border-b border-gray-200 text-sm font-semibold text-right">Unit Price</th>
                            <th class="p-4 border-b border-gray-200 text-sm font-semibold text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($quotation->products as $index => $product)
                        @php
                            $productTotal = ($product->pivot->quantity ?? 1) * ($product->pivot->unit_price ?? 0);
                        @endphp
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100 transition-colors">
                            <td class="p-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                            <td class="p-4 text-sm text-gray-700">{{ $product->name ?? 'N/A' }}</td>
                            <td class="p-4 text-sm text-gray-600">
                                @php
                                    $variations = $product->pivot->variations;
                                    if (!is_array($variations)) $variations = json_decode($variations, true) ?? [];
                                @endphp
                                @if(!empty($variations))
                                    <div class="space-y-1 text-xs">
                                        @foreach($variations as $v)
                                            <div class="bg-gray-100 p-1 rounded">
                                                <strong>Color:</strong> {{ $v['color'] ?? '-' }}
                                                @if(!empty($v['sizes']))
                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                        @foreach($v['sizes'] as $size => $qty)
                                                            @if($qty > 0)
                                                                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs">
                                                                    {{ $size }}: {{ $qty }}
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">—</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-gray-600 text-center">{{ $product->pivot->quantity ?? 1 }}</td>
                            <td class="p-4 text-sm text-gray-600 text-right">₹{{ number_format($product->pivot->unit_price ?? 0, 2) }}</td>
                            <td class="p-4 text-sm text-gray-700 font-medium text-right">₹{{ number_format($productTotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            

           @php
    // Company GST
    $companyGst = $quotation->company_gst ?? '27AMRPK6699L1ZV';
    $companyState = substr($companyGst, 0, 2);

    // Client GST
    $clientGst = $quotation->client->gst_no ?? null;
    $clientState = $clientGst ? substr($clientGst, 0, 2) : null;

    // Values
    $subtotal = $quotation->subtotal ?? 0;

    // Default
    $cgst = $sgst = $igst = 0;

    // GST Type
    if ($clientState && $companyState === $clientState) {
        // Intra-state (CGST + SGST)
        $cgst = $subtotal * 0.025;
        $sgst = $subtotal * 0.025;
    } else {
        // Inter-state (IGST)
        $igst = $subtotal * 0.05;
    }

    $grandTotal = $subtotal + $cgst + $sgst + $igst;
@endphp

<!-- Totals -->
<div class="flex justify-end mb-8">
    <div class="w-full md:w-1/3">
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">

            <div class="flex justify-between py-2">
                <span class="text-sm font-medium text-gray-700">Subtotal</span>
                <span class="text-sm font-semibold text-gray-900">₹{{ number_format($subtotal, 2) }}</span>
            </div>

            @if($igst > 0)
                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-700">IGST (5%)</span>
                    <span class="text-sm font-semibold text-gray-900">₹{{ number_format($igst, 2) }}</span>
                </div>
            @else
                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-700">CGST (2.5%)</span>
                    <span class="text-sm font-semibold text-gray-900">₹{{ number_format($cgst, 2) }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-700">SGST (2.5%)</span>
                    <span class="text-sm font-semibold text-gray-900">₹{{ number_format($sgst, 2) }}</span>
                </div>
            @endif

            <div class="flex justify-between py-3 border-t border-gray-200">
                <span class="text-base font-bold text-gray-800">Grand Total</span>
                <span class="text-lg font-bold text-indigo-600">₹{{ number_format($grandTotal, 2) }}</span>
            </div>

        </div>
    </div>
</div>


            <!-- Terms -->
            <div class="mb-8">
                <h4 class="font-semibold text-gray-700 mb-3 text-lg">Terms & Notes</h4>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Payment Terms: 50% advance, 50% on delivery.</li>
                    <li>Quotation valid for 30 days from date of issue.</li>
                    <li>Delivery: As per agreement.</li>
                </ul>
            </div>

            <!-- QR Code -->
            <div class="text-center mb-8">
                <div id="invoiceQR" class="inline-block p-4 bg-gray-50 rounded-lg"></div>
            </div>
        </div>
    </div>

    <!-- Action Buttons – hidden in print -->
    <div class="flex justify-center gap-4 mt-8 no-print">
        <button onclick="printQuotation()"
                class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-all duration-200">
            Print Quotation
        </button>
        <button onclick="downloadPDF()"
                class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-all duration-200">
            Download PDF
        </button>
    </div>
</div>

<!-- ✅ Libraries (correct order) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
// Blade-safe variables
const quotationId = "{{ str_pad($quotation->id, 5, '0', STR_PAD_LEFT) }}";
const grandTotal = "{{ number_format($quotation->grand_total ?? 0, 2) }}";

// ✅ QR Code
document.addEventListener('DOMContentLoaded', () => {
    new QRCode(document.getElementById('invoiceQR'), {
        text: `QUO-${quotationId} ₹${grandTotal}`,
        width: 120,
        height: 120,
        colorDark: '#1E3A8A',
        colorLight: '#F3F4F6',
        errorCorrectionLevel: 'H'
    });
});

// ✅ PRINT – preserve layout
window.printQuotation = () => {
    const originalTitle = document.title;
    document.title = `Quotation-QUO-${quotationId}`;
    window.print();
    document.title = originalTitle;
};

// ✅ PDF – A4, full scaling, multi-page support
window.downloadPDF = async () => {
    const { jsPDF } = window.jspdf;
    const el = document.querySelector('.quotation-content');
    try {
        const canvas = await html2canvas(el, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff'
        });
        const img = canvas.toDataURL('image/png');
        const pdf = new jsPDF('p', 'mm', 'a4');
        const w = pdf.internal.pageSize.getWidth();
        const h = pdf.internal.pageSize.getHeight();
        const imgH = (canvas.height * w) / canvas.width;
        let heightLeft = imgH;
        let position = 0;

        pdf.addImage(img, 'PNG', 0, position, w, imgH);
        heightLeft -= h;
        while (heightLeft > 0) {
            position = heightLeft - imgH;
            pdf.addPage();
            pdf.addImage(img, 'PNG', 0, position, w, imgH);
            heightLeft -= h;
        }
        pdf.save(`Quotation-QUO-${quotationId}.pdf`);
    } catch (e) {
        console.error('PDF Error:', e);
        alert('Failed to generate PDF. Please try again.');
    }
};
</script>

<style>
/* ------------------------------------------------------------------
   PRINT STYLES – ONLY the quotation, clean A4 look
   ------------------------------------------------------------------ */
@media print {
    /* ✅ Hide all non-print elements */
    body > * {
        visibility: hidden !important;
    }
    .printable-quotation,
    .printable-quotation * {
        visibility: visible !important;
    }
    .printable-quotation {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 0 !important;
        margin: 0 auto !important;
        background: #fff !important;
    }

    /* ✅ Core layout fixes */
    .quotation-content {
        width: 100% !important;
        background: #fff !important;
        padding: 35px 50px !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        page-break-inside: avoid !important;
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }

    /* ✅ Fix "Logo + Quotation Info" alignment */
    .quotation-content .flex.md\:flex-row {
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: flex-start !important;
        flex-wrap: nowrap !important;
        width: 100% !important;
        margin-bottom: 20px !important;
    }

    /* Force both logo and quotation info to stay inline */
    .quotation-content .flex.md\:flex-row > div:first-child {
        width: 50% !important;
        text-align: left !important;
    }
    .quotation-content .flex.md\:flex-row > div:last-child {
        width: 50% !important;
        text-align: right !important;
    }

    /* ✅ Fix "Bill To" & "Ship To" side by side */
    .grid.grid-cols-1.md\:grid-cols-2 {
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: flex-start !important;
        gap: 20px !important;
        width: 100% !important;
    }
    .grid.grid-cols-1.md\:grid-cols-2 > div {
        width: 48% !important;
        display: inline-block !important;
        vertical-align: top !important;
    }

    /* ✅ Keep table layout intact */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    table th {
        background: #E0E7FF !important;
        color: #111827 !important;
        -webkit-print-color-adjust: exact !important;
        text-align: left !important;
    }

    tr:hover {
        background: none !important;
    }

    /* ✅ Ensure page fits cleanly on one sheet */
    html, body {
        height: auto !important;
        overflow: visible !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .printable-quotation,
    .quotation-content,
    .quotation-content > * {
        page-break-before: avoid !important;
        page-break-after: avoid !important;
        page-break-inside: avoid !important;
    }

    /* ✅ A4 formatting */
    @page {
        size: A4 portrait;
        margin: 1cm;
    }

    /* ✅ Hide buttons and sidebar */
    .no-print,
    .no-print * {
        display: none !important;
    }
}

/* Back button (screen only) */
.back-btn{
    display:inline-flex;align-items:center;gap:8px;padding:8px 20px;
    background:linear-gradient(to right,#4f46e5,#3b82f6);color:#fff;font-weight:600;
    border-radius:9999px;text-decoration:none;box-shadow:0 4px 6px rgba(0,0,0,.1);
    transition:.2s;
}
.back-btn:hover{background:linear-gradient(to right,#4338ca,#2563eb);}
.back-icon{width:20px;height:20px;}
</style>
@endsection