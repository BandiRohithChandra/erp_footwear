@extends('layouts.app')

@section('content')
<div class="container" id="invoiceContainer" style="max-width: 900px; margin: 40px auto; font-family: 'Poppins', sans-serif; border:1px solid #ddd; padding:30px; border-radius:10px; box-shadow:0 4px 20px rgba(0,0,0,0.1); background:#fff;">

    {{-- Header --}}
    <div style="text-align:center; margin-bottom:40px;">
        <img src="{{ $user->logo ?? '' }}" alt="Company Logo" style="max-height:60px; margin-bottom:10px;">
        <h1 style="font-size:28px; color:#4f46e5;">Invoice</h1>
        <p style="color:#555;">Thank you for your purchase!</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    </div>

    {{-- Invoice Number & Date --}}
    <div style="display:flex; justify-content:space-between; margin-bottom:20px;">
        <p><strong>Invoice No:</strong> INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
    </div>

    {{-- Client & Company Info --}}
    <div style="display:flex; justify-content:space-between; margin-bottom:30px;">
        {{-- Client Info --}}
        <div style="width:48%;">
            <p><strong>Client Name:</strong> {{ $order->client->name ?? 'N/A' }}</p>
            <p><strong>Business Name:</strong> {{ $order->client->business_name ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $order->address ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $order->client->phone ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $order->client->email ?? 'N/A' }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>
            <p><strong>Paid Amount:</strong> ₹{{ number_format($order->paid_amount ?? 0, 2) }}</p>
            <p><strong>Due Amount:</strong> ₹{{ number_format(max(($order->total ?? 0) - ($order->paid_amount ?? 0), 0), 2) }}</p>
        </div>

        {{-- Company Info --}}
        <div style="width:48%; text-align:right;">
            <img src="{{ $company->logo ?? '' }}" alt="Company Logo" style="max-height:60px; margin-bottom:10px;">
            <p>{{ $company->business_name ?? 'N/A' }}</p>
            <p>GST: {{ $company->gst_no ?? 'N/A' }}</p>
            <p>{{ $company->address ?? 'N/A' }}</p>
            <p>Phone: {{ $company->phone ?? 'N/A' }}</p>
            <p>Email: {{ $company->email ?? 'N/A' }}</p>
            <p>Website: {{ $company->website ?? 'N/A' }}</p>
            <p>Contact Person: {{ $company->contact_person ?? 'N/A' }}</p>
        </div>
    </div>

    {{-- Products Table --}}
    <table style="width:100%; border-collapse: collapse; margin-bottom:30px; font-size:14px;">
        <thead>
            <tr style="background:#4f46e5; color:#fff;">
                <th style="padding:10px; text-align:left;">Product</th>
                <th style="padding:10px; text-align:center;">Qty</th>
                <th style="padding:10px; text-align:right;">Unit Price</th>
                <th style="padding:10px; text-align:right;">CGST</th>
                <th style="padding:10px; text-align:right;">SGST</th>
                <th style="padding:10px; text-align:right;">IGST</th>
                <th style="padding:10px; text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotal = 0;
                $totalCGST = 0;
                $totalSGST = 0;
                $totalIGST = 0;
            @endphp
            @foreach($cartItems as $item)
                @php
                    $qty = $item['quantity'] ?? 1;
                    $price = $item['price'] ?? 0;
                    $cgst = $item['cgst_amount'] ?? 0;
                    $sgst = $item['sgst_amount'] ?? 0;
                    $igst = $item['igst_amount'] ?? 0;
                    $total = ($qty * $price) + $cgst + $sgst + $igst;

                    $subtotal += $qty * $price;
                    $totalCGST += $cgst;
                    $totalSGST += $sgst;
                    $totalIGST += $igst;
                @endphp
            <tr style="border-bottom:1px solid #ddd; background: {{ $loop->even ? '#f9f9f9' : '#fff' }};">
                <td style="padding:10px;">
                    {{ $item['product']['name'] ?? 'N/A' }}
                    @if(isset($item['color']) || isset($item['size']))
                        <br><small>
                            @if(isset($item['color'])) Color: {{ $item['color'] }} @endif
                            @if(isset($item['size'])) Size: {{ $item['size'] }} @endif
                        </small>
                    @endif
                </td>
                <td style="padding:10px; text-align:center;">{{ $qty }}</td>
                <td style="padding:10px; text-align:right;">₹{{ number_format($price, 2) }}</td>
                <td style="padding:10px; text-align:right;">₹{{ number_format($cgst, 2) }}</td>
                <td style="padding:10px; text-align:right;">₹{{ number_format($sgst, 2) }}</td>
                <td style="padding:10px; text-align:right;">₹{{ number_format($igst, 2) }}</td>
                <td style="padding:10px; text-align:right;">₹{{ number_format($total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Price Summary --}}
    <div style="text-align:right; font-size:16px; margin-bottom:30px;">
        <p><strong>Subtotal:</strong> ₹{{ number_format($subtotal, 2) }}</p>
        <p><strong>CGST Total:</strong> ₹{{ number_format($totalCGST, 2) }}</p>
        <p><strong>SGST Total:</strong> ₹{{ number_format($totalSGST, 2) }}</p>
        <p><strong>IGST Total:</strong> ₹{{ number_format($totalIGST, 2) }}</p>
        <p style="font-size:18px;"><strong>Total:</strong> ₹{{ number_format($subtotal + $totalCGST + $totalSGST + $totalIGST, 2) }}</p>
        <p><strong>Paid:</strong> ₹{{ number_format($order->paid_amount ?? 0, 2) }}</p>
        <p><strong>Due:</strong> ₹{{ number_format(max(($subtotal + $totalCGST + $totalSGST + $totalIGST) - ($order->paid_amount ?? 0), 0), 2) }}</p>
    </div>

    {{-- QR Code --}}
    <div style="text-align:center; margin-bottom:20px;">
        <div id="invoiceQR"></div>
    </div>

    {{-- Action Buttons --}}
    <div style="text-align:center; margin-top:20px;">
        <button onclick="printInvoice()" style="padding:12px 25px; background:#4f46e5; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:500; margin-right:10px;">Print Invoice</button>
        <button onclick="downloadPDF()" style="padding:12px 25px; background:#10b981; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:500;">Download PDF</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
function printInvoice() {
    window.print();
}

function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');
    const invoice = document.getElementById('invoiceContainer');

    html2canvas(invoice, { scale: 2 }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const imgProps = doc.getImageProperties(imgData);
        const pdfWidth = doc.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
        doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        doc.save('Invoice-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}.pdf');
    });
}

// QR Code
new QRCode(document.getElementById("invoiceQR"), {
    text: "Invoice ID: {{ $order->id }} | Total: ₹{{ number_format($subtotal + $totalCGST + $totalSGST + $totalIGST, 2) }} | Paid: ₹{{ number_format($order->paid_amount ?? 0, 2) }} | Due: ₹{{ number_format(max(($subtotal + $totalCGST + $totalSGST + $totalIGST) - ($order->paid_amount ?? 0), 0), 2) }}",
    width: 100,
    height: 100
});
</script>
@endsection
