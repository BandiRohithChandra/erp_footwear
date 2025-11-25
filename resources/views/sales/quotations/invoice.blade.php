@extends('layouts.app')

@section('content')

@php
    use App\Models\BankDetail;
    $bank = BankDetail::first();
@endphp


<!-- ✅ Back Button -->
<a href="{{ url()->previous() }}" 
   class="no-print"
   style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:linear-gradient(90deg, #9747FF 0%, #92D3F5 100%); color:#fff; font-weight:600; font-size:16px; border-radius:30px; text-decoration:none; box-shadow:0 4px 15px rgba(0,0,0,0.2); transition:all 0.3s ease; margin:20px 0;">
    <span style="font-size:18px;">←</span> Back
</a>

<!-- ✅ Invoice Container -->
<div id="invoiceContainer" class="container" style="max-width: 900px; margin: 20px auto 40px auto; font-family: 'Poppins', sans-serif; border:1px solid #ddd; padding:30px; border-radius:10px; box-shadow:0 4px 20px rgba(0,0,0,0.1); background:#fff;">

    {{-- Header --}}
    <div style="text-align:center; margin-bottom:40px;">
        @php
            $source = $invoice->type === 'quotation' ? $invoice->quotation : $invoice->order;
        @endphp
        @if(!empty($source->company_logo))
            <img src="{{ asset('storage/'.$source->company_logo) }}" alt="Company Logo" style="max-height:60px; margin-bottom:10px;">
        @endif
        <h1 style="font-size:28px; color:#4f46e5;">Invoice</h1>
        <p style="color:#555;">Thank you for your business!</p>
    </div>

   

   <div style="display:flex; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap;">
    <p><strong>Invoice No:</strong> INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</p>
    


    

    <p><strong>Date:</strong> {{ $invoice->created_at->format('d/m/Y') }}</p>

    <!-- <p>
        <strong>Status:</strong>
        @if($invoice->status === 'paid')
            <span style="color: green; font-weight: 600;">Paid</span>
        @elseif($invoice->status === 'partially_paid')
            <span style="color: orange; font-weight: 600;">Partially Paid</span>
        @elseif($invoice->status === 'cancelled')
            <span style="color: gray; font-weight: 600;">Cancelled</span>
        @else
            <span style="color: red; font-weight: 600;">Pending</span>
        @endif
    </p> -->
</div>

{{-- Client & Company Info --}}
<div style="display:flex; justify-content:space-between; margin-bottom:30px; align-items:flex-start;">

    {{-- Client Info --}}
    <div style="width:48%;">
        <h4>Bill To :</h4>
        <p><strong>{{ $invoice->client->business_name ?? $invoice->client->name ?? 'N/A' }}</strong></p>
        {{-- Client Brand --}}

   <p class="text-sm text-gray-600">
    Brand: {{ $invoice->quotation->brand_name ?? 'N/A' }}
</p>
{{-- ✅ Show Quotation No if available --}}
    @if(!empty($invoice->quotation_id) && !empty($invoice->quotation))
        <p><strong>Quotation No:</strong> QT-{{ str_pad($invoice->quotation->id, 5, '0', STR_PAD_LEFT) }}</p>
    @else
        <p><strong>Quotation No:</strong> N/A</p>
    @endif

        <p>GST: {{ $invoice->client->gst_no ?? 'N/A' }}</p>
        <p>{{ $invoice->client->address ?? 'N/A' }}</p>
        <p><strong>PO No:</strong> {{ $invoice->po_no ?? 'N/A' }}</p>
        <p>Phone: {{ $invoice->client->phone ?? 'N/A' }}</p>
        <p>Email: {{ $invoice->client->email ?? 'N/A' }}</p>
    </div>

    {{-- Company Info --}}
    <div style="width:48%; text-align:right;">

        {{-- Company Logo (First) --}}
        @php
            $logo = \App\Models\Settings::get('company_logo');
        @endphp

        @if($logo)
            <img src="{{ asset($logo) }}" 
                 style="height:60px; width:auto; margin-bottom:10px; display:inline-block;">
        @endif

        <h4 style="margin-top:0;">Company Details:</h4>

        <p><strong>{{ \App\Models\Settings::get('company_name') }}</strong></p>
        <p>{{ \App\Models\Settings::get('company_gst') }}</p>
        <p>{{ \App\Models\Settings::get('company_address') }}</p>
        <p>{{ \App\Models\Settings::get('company_phone') }}</p>
        <p>{{ \App\Models\Settings::get('company_email') }}</p>

        {{-- Optional Contact Person --}}
        @php
            $contact = \App\Models\Settings::get('company_contact_person');
        @endphp
        @if($contact)
            <p>Contact: {{ $contact }}</p>
        @endif

    </div>

</div>
    {{-- Products Table --}}
    @php
        $itemsArray = is_string($invoice->items) ? json_decode($invoice->items, true) : ($invoice->items ?? []);
    @endphp

    <table style="width:100%; border-collapse: collapse; margin-bottom:30px; font-size:14px;">
        <thead>
            <tr style="background:#4f46e5; color:#fff;">
                <th style="padding:10px; text-align:left;">Article</th>
                <th style="padding:10px; text-align:left;">Color</th>
                <th style="padding:10px; text-align:left;">Size</th>
                <th style="padding:10px; text-align:center;">Qty</th>
                <th style="padding:10px; text-align:right;">Price</th>
                <th style="padding:10px; text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($itemsArray as $item)
                @php
                    $variations = is_string($item['variations'] ?? null)
                        ? json_decode($item['variations'], true)
                        : ($item['variations'] ?? []);
                @endphp
                <tr style="border-bottom:1px solid #ddd; background: {{ $loop->even ? '#f9f9f9' : '#fff' }};">
                    <td style="padding:10px;">{{ $item['name'] ?? 'N/A' }}</td>
                    <td style="padding:10px;">
                        @if(!empty($variations))
                            @foreach($variations as $v)
                                <div><strong>{{ ucfirst($v['color'] ?? '-') }}</strong></div>
                            @endforeach
                        @else - @endif
                    </td>
                    <td style="padding:10px;">
                        @if(!empty($variations))
                            @foreach($variations as $v)
                                @php
                                    $sizeList = collect($v['sizes'] ?? [])
                                        ->filter(fn($qty) => intval($qty) > 0)
                                        ->map(fn($qty, $size) => "$size($qty)")
                                        ->implode(', ');
                                @endphp
                                <div>{{ $sizeList ?: 'No sizes' }}</div>
                            @endforeach
                        @else - @endif
                    </td>
                    <td style="padding:10px; text-align:center;">{{ $item['quantity'] ?? 1 }}</td>
                    <td style="padding:10px; text-align:right;">₹{{ number_format($item['unit_price'] ?? 0, 2) }}</td>
                    <td style="padding:10px; text-align:right;">₹{{ number_format(($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0), 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding:10px; text-align:center;">No products added for this invoice.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Totals --}}
@php
    // Company GST
    $companyGst = \App\Models\Settings::get('company_gst');
    $companyState = $companyGst ? substr($companyGst, 0, 2) : null;

    // Client GST
    $clientGst = $invoice->client->gst_no ?? null;
    $clientState = $clientGst && strlen($clientGst) >= 2 ? substr($clientGst, 0, 2) : null;

    // Detect GST type
    if ($companyState === $clientState && $companyState !== null) {
        $taxType = 'cgst'; // INTRA-STATE
    } else {
        $taxType = 'igst'; // INTER-STATE
    }

    // Taxes
    $subtotal = $invoice->amount;

    if ($taxType === 'igst') {
        $igst = $subtotal * 0.05;  // 5%
        $cgst = 0;
        $sgst = 0;
    } else {
        $cgst = $subtotal * 0.025; // 2.5%
        $sgst = $subtotal * 0.025; // 2.5%
        $igst = 0;
    }

    $grandTotal = $subtotal + $cgst + $sgst + $igst;
@endphp




<div style="text-align:right; font-size:16px; margin-bottom:30px;">
    <p><strong>Subtotal:</strong> ₹{{ number_format($subtotal, 2) }}</p>

    @if($igst > 0)
        <p><strong>IGST (5%):</strong> ₹{{ number_format($igst, 2) }}</p>
    @else
        <p><strong>CGST (2.5%):</strong> ₹{{ number_format($cgst, 2) }}</p>
        <p><strong>SGST (2.5%):</strong> ₹{{ number_format($sgst, 2) }}</p>
    @endif

    <hr style="margin: 8px 0; border-color: #ddd;">
    <p style="font-size:18px;"><strong>Grand Total:</strong> ₹{{ number_format($grandTotal, 2) }}</p>
    <p><strong>Amount Paid:</strong> ₹{{ number_format($invoice->amount_paid, 2) }}</p>
    <p style="font-size:18px;"><strong>Balance Due:</strong> ₹{{ number_format($grandTotal - $invoice->amount_paid, 2) }}</p>
</div>


{{-- ✅ Bank Details Section --}}
<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: 30px; flex-wrap: wrap;">
    {{-- Bank Details (Left Side) --}}
    <div style="width: 48%;">
        <h4 style="color:#4f46e5; margin-bottom:10px;">Bank Details:</h4>

        @if($bank)
            <p><strong>Bank Name:</strong> {{ $bank->bank_name }}</p>
            <p><strong>Branch:</strong> {{ $bank->branch_name }}</p>
            <p><strong>Account Holder:</strong> {{ $bank->account_holder }}</p>
            <p><strong>Account Number:</strong> {{ $bank->account_number }}</p>
            <p><strong>IFSC Code:</strong> {{ $bank->ifsc_code }}</p>
            @if($bank->upi_id)
                <p><strong>UPI ID:</strong> {{ $bank->upi_id }}</p>
            @endif
        @else
            <p style="color:#888;">No bank details available. Please update them in Settings → General.</p>
        @endif
    </div>

    {{-- Optional Right Side Signature Area --}}
    <div style="width: 48%; text-align: right;">
        <h4 style="color:#4f46e5; margin-bottom:10px;">Authorized Signature</h4>
        <div style="height:60px; border-bottom:1px solid #ccc; margin-bottom:5px;"></div>
        <p style="font-size:13px; color:#666;">For {{ $source->company_name ?? 'CREATIVE SHOES' }}</p>
    </div>
</div>



    {{-- QR Code --}}
    <div style="text-align:center; margin-bottom:20px;">
        <div id="invoiceQR"></div>
    </div>

    {{-- Action Buttons --}}
    <div class="no-print" style="text-align:center; margin-top:20px;">
        <button onclick="printInvoice()" style="padding:12px 25px; background:#4f46e5; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:500; margin-right:10px;">Print Invoice</button>
        <button onclick="downloadPDF()" style="padding:12px 25px; background:#10b981; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:500;">Download PDF</button>
    </div>
</div>

<!-- ✅ Print Styles -->
<style>
@media print {
    body * {
        visibility: hidden !important;
    }
    #invoiceContainer, #invoiceContainer * {
        visibility: visible !important;
    }
    #invoiceContainer {
        position: absolute !important;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 0;
        box-shadow: none !important;
        border: none !important;
    }
    .sidebar, .navbar, .footer, .no-print {
        display: none !important;
    }
    @page {
        size: A4;
        margin: 12mm;
    }
}
</style>

<!-- ✅ Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
function printInvoice() {
    window.print();
}


new QRCode(document.getElementById('invoiceQR'), {
    text: 'Invoice ID: {{ $invoice->id }} | Balance Due: ₹{{ number_format($invoice->amount - $invoice->amount_paid, 2) }}',
    width: 100,
    height: 100
});
</script>

<script>
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');
    const invoice = document.getElementById('invoiceContainer');

    // ✅ Hide action buttons before capturing
    const buttons = invoice.querySelectorAll('.no-print');
    buttons.forEach(btn => btn.style.display = 'none');

    html2canvas(invoice, {
        scale: 2,
        useCORS: true,
        backgroundColor: '#ffffff',
        logging: false
    }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdfWidth = doc.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
        doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        doc.save('Invoice-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}.pdf');

        // ✅ Restore buttons after saving
        buttons.forEach(btn => btn.style.display = '');
    });
}
</script>


@endsection
