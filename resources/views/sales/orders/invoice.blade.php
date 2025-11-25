@extends('layouts.app')

@section('content')
<div class="container" id="invoiceContainer" style="max-width: 900px; margin: 40px auto; font-family: 'Poppins', sans-serif; border:1px solid #ddd; padding:30px; border-radius:10px; box-shadow:0 4px 20px rgba(0,0,0,0.1); background:#fff;">

    {{-- Header --}}
    <div style="text-align:center; margin-bottom:40px;">
        <img src="{{ $user->logo ?? '' }}" alt="Company Logo" style="max-height:60px; margin-bottom:10px;">
        <h1 style="font-size:28px; color:#4f46e5;">Invoice</h1>
        <p style="color:#555;">Thank you for your purchase!</p>
    </div>

    {{-- Invoice Number & Date --}}
    <div style="display:flex; justify-content:space-between; margin-bottom:20px;">
        <p><strong>Invoice No:</strong> INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
        <p><strong>Date:</strong> {{ now()->format('d/m/Y') }}</p>
    </div>

    {{-- Client & Company Info --}}
    <div style="display:flex; justify-content:space-between; margin-bottom:30px;">
        {{-- Client Info --}}
        <div style="width:48%;">
            <h4>Bill To (Company):</h4>
    <p><strong>{{ $order->user->business_name ?? $order->user->name ?? 'N/A' }}</strong></p>
    <p>GST: {{ $order->user->gst_no ?? 'N/A' }}</p>
    <p>{{ $order->user->address ?? 'N/A' }}</p>
    <p>Phone: {{ $order->user->phone ?? 'N/A' }}</p>
    <p>Email: {{ $order->user->email ?? 'N/A' }}</p>
    <p>Website: {{ $order->user->website ?? 'N/A' }}</p>
    <p>Contact Person: {{ $order->user->contact_person ?? 'N/A' }}</p>
    <p>Designation: {{ $order->user->designation ?? 'N/A' }}</p>

    <h4 class="mt-4">Shipping Details:</h4>
    <p>{{ $order->transport_name ?? '-' }}</p>
    <p>{{ $order->transport_address ?? '-' }}</p>
    <p>{{ $order->transport_id ?? '-' }}</p>
        </div>

        {{-- Company Info --}}
        <div style="width:48%; text-align:right;">
            <h4>Company Details:</h4>
    <p><strong>CREATIVE SHOES</strong></p>
    <p>27AMRPK6699L1ZV</p>
    <p>Ground Floor, Room No. 5, Municipal Chawl No. 6,<br>Transit Camp Road, Byculla</p>
    <p>+++++++++9</p>
    <p>kiran@gmail.com</p>
    <!-- <p>Website: <a href="https://www.microsoft.com" target="_blank" style="color:#1d4ed8; text-decoration:underline;">https://www.microsoft.com</a></p> -->
    <p>Contact Person: SIRATULLAH JAMIRULLAH KHAN</p>
        </div>
    </div>

    {{-- Products Table --}}
    <table style="width:100%; border-collapse: collapse; margin-bottom:30px; font-size:14px;">
        <thead>
            <tr style="background:#4f46e5; color:#fff;">
                <th style="padding:10px; text-align:left;">Product</th>
                <th style="padding:10px; text-align:center;">Qty</th>
                <th style="padding:10px; text-align:right;">Unit Price</th>
                <th style="padding:10px; text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cartItems as $item)
            <tr style="border-bottom:1px solid #ddd; background: {{ $loop->even ? '#f9f9f9' : '#fff' }};">
                <td style="padding:10px;">
                    {{ $item['name'] ?? 'N/A' }}
                    @if(isset($item['color']) || isset($item['size']))
                        <br>
                        <small>
                            @if(isset($item['color'])) Color: {{ $item['color'] }} @endif
                            @if(isset($item['size'])) Size: {{ $item['size'] }} @endif
                        </small>
                    @endif
                </td>
                <td style="padding:10px; text-align:center;">{{ $item['quantity'] ?? 1 }}</td>
                <td style="padding:10px; text-align:right;">₹{{ number_format($item['price'] ?? 0, 2) }}</td>
                <td style="padding:10px; text-align:right;">₹{{ number_format(($item['quantity'] ?? 1) * ($item['price'] ?? 0), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Price Summary --}}
    <div style="text-align:right; font-size:16px; margin-bottom:30px;">
        <p><strong>Subtotal:</strong> ₹{{ number_format($order->subtotal ?? 0, 2) }}</p>
        <p><strong>GST (18%):</strong> ₹{{ number_format($order->gst ?? 0, 2) }}</p>
        <p style="font-size:18px;"><strong>Total:</strong> ₹{{ number_format(($order->subtotal ?? 0) + ($order->gst ?? 0), 2) }}</p>
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

{{-- Scripts --}}
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
    text: "Invoice ID: {{ $order->id }} | Total: ₹{{ number_format(($order->subtotal ?? 0) + ($order->gst ?? 0), 2) }}",
    width: 100,
    height: 100
});
</script>

@endsection
