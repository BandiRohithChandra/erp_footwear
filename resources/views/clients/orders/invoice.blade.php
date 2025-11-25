@extends('layouts.app')

@section('content')
<style>
/* ===== Invoice Container ===== */
.invoice-container {
    max-width: 850px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    font-family: 'Arial', sans-serif;
    color: #000;
    border: 1px solid #000;
    border-radius: 8px;
    box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
}

/* ===== Header ===== */
.invoice-header { text-align: center; margin-bottom: 20px; }
.invoice-header h1 { font-size: 1.8rem; font-weight: 700; margin: 0; }
.invoice-header p { font-size: 1.1rem; margin: 2px 0; }

/* ===== Client & Company Details ===== */
.invoice-details { display: flex; justify-content: space-between; margin-bottom: 20px; border: 1px solid #000; padding: 15px; border-radius: 6px; }
.invoice-details .client, .invoice-details .company { width: 48%; }
.invoice-details h4 { font-weight: 600; margin-bottom: 5px; }
.invoice-details p { margin: 2px 0; font-size: 0.85rem; }

/* ===== Table ===== */
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
table th, table td { border: 1px solid #000; padding: 8px; font-size: 0.85rem; text-align: center; }
table th { background-color: #f2f2f2; font-weight: 600; }

/* ===== Bottom Section ===== */
.bottom-section { display: flex; justify-content: space-between; margin-top: 20px; }
.bottom-box { width: 48%; border: 1px solid #000; padding: 10px; border-radius: 6px; }
.bottom-box h4 { margin-bottom: 10px; font-weight: 600; }

/* ===== Totals Section ===== */
.totals p { margin: 4px 0; font-weight: 600; font-size: 0.9rem; }
.amount-words { font-style: italic; font-size: 0.85rem; margin-top: 5px; }

/* ===== Signature Boxes ===== */
.signature-boxes { display: flex; justify-content: space-between; margin-top: 40px; }
.signature-box { border-top: 1px solid #000; width: 45%; text-align: center; padding-top: 5px; font-weight: 600; }

/* ===== Print Styling ===== */
@media print { body * { visibility: hidden; } .invoice-container, .invoice-container * { visibility: visible; } .invoice-container { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; } }
</style>

<div class="invoice-container" id="invoiceContainer">

    {{-- Header --}}
    <div class="invoice-header">
        <h1>CREATIVE SHOES</h1>
        <p>INVOICE</p>
    </div>

    {{-- Client & Company Details --}}
   <div class="invoice-details">
        <div class="client">
            <h4>Party / Client Details</h4>
            <p><strong>{{ $order->user->business_name ?? $order->user->name ?? 'N/A' }}</strong></p>
            <p>GST: {{ $order->user->gst_no ?? 'N/A' }}</p>
            <p>Address: {{ $order->user->address ?? 'N/A' }}</p>
            <p>Phone: {{ $order->user->phone ?? 'N/A' }}</p>
            <p>Contact Person: {{ $order->user->contact_person ?? 'N/A' }}</p>
            <p><strong>PO No:</strong> {{ $order->po_no ?? '-' }}</p>
            <p><strong>Order No:</strong> {{ $order->id }}</p>

            <h4>Shipping / Transport Details</h4>
            <p><strong>{{ $order->transport_name ?? '-' }}</strong></p>
            <p>Address: {{ $order->transport_address ?? '-' }}</p>
            <p>ID: {{ $order->transport_id ?? '-' }}</p>
            <p>Phone: {{ $order->transport_phone ?? '-' }}</p>
        </div>

        <div class="company">
            <h4>Company Details</h4>
            <p><strong>CREATIVE SHOES</strong></p>
            <p>GSTIN: 27AMRPK6699L1ZV</p>
            <p>Ground Floor, Room No.5, Municipal Chawl No.6,<br>Transit Camp Road, Byculla</p>
            <p>Phone: +++++++++9</p>
            <p>Email: kiran@gmail.com</p>
            <p>Contact Person: SIRATULLAH JAMIRULLAH KHAN</p>
        </div>
    </div>

    {{-- Order Date --}}
    <p><strong>Order Date:</strong> {{ $order->created_at ? $order->created_at->format('d M Y') : 'N/A' }}</p>

    {{-- Items Table --}}
    <h3>Items</h3>
    @php
    $subtotal = 0;
    $total_cgst = 0;
    $total_sgst = 0;
    $total_igst = 0;
    $companyState = $company->state ?? 'Maharashtra';

    $items = [];

    if($order->products && $order->products->count()) {
        foreach($order->products as $product) {
            $cart_item = collect($order->cart_items)->firstWhere('product_id', $product->id) ?? [];
            $items[] = [
                'name' => $product->name,
                'sku'  => $product->sku,
                'hsn'  => $product->hsn_code ?? 'N/A',
                'qty'  => $cart_item['quantity'] ?? 0,
                'price'=> $cart_item['price'] ?? $product->price,
            ];
        }
    } elseif($order->cart_items) {
        $cart_array = json_decode($order->cart_items, true);
        foreach($cart_array as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $items[] = [
                'name' => $product->name ?? 'N/A',
                'sku'  => $product->sku ?? 'N/A',
                'hsn'  => $product->hsn_code ?? 'N/A',
                'qty'  => $item['quantity'],
                'price'=> $item['price'],
            ];
        }
    }

    function numberToWords($number) {
        $formatter = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        return $formatter->format($number);
    }
    @endphp

    @if(count($items))
    <table>
        <thead>
            <tr>
                <th>SR NO</th>
                <th>NAME OF PRODUCT</th>
                <th>ARTICLE NO</th>
                <th>HSN CODE</th>
                <th>UNIT</th>
                <th>QTY</th>
                <th>RATE</th>
                <th>AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
                @php
                    $item_total = $item['qty'] * $item['price'];

                    if(strtolower($order->state ?? '') === strtolower($companyState)){
                        $cgst = $item_total * 0.09;
                        $sgst = $item_total * 0.09;
                        $igst = 0;
                        $total_cgst += $cgst;
                        $total_sgst += $sgst;
                    } else {
                        $cgst = 0;
                        $sgst = 0;
                        $igst = $item_total * 0.18;
                        $total_igst += $igst;
                    }

                    $subtotal += $item_total;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['sku'] }}</td>
                    <td>{{ $item['hsn'] }}</td>
                    <td>Pcs</td>
                    <td>{{ $item['qty'] }}</td>
                    <td>{{ number_format($item['price'], 2) }}</td>
                    <td>{{ number_format($item_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Bottom Section: Payment & Totals --}}
    <div class="bottom-section">
        <div class="bottom-box">
            <h4>Payment Details</h4>
            <p>Bank Name: {{ $company->bank_name ?? 'N/A' }}</p>
            <p>Account No: {{ $company->bank_account ?? 'N/A' }}</p>
            <p>IFSC: {{ $company->ifsc_code ?? 'N/A' }}</p>
            <p>Branch: {{ $company->branch ?? 'N/A' }}</p>
        </div>

        <div class="bottom-box">
            <h4>Total Details</h4>
            <p>Total Amount Before Tax: ₹{{ number_format($subtotal, 2) }}</p>
            @if($total_igst > 0)
                <p>IGST (18%): ₹{{ number_format($total_igst, 2) }}</p>
                @php $grand_total = $subtotal + $total_igst; @endphp
            @else
                <p>CGST (9%): ₹{{ number_format($total_cgst, 2) }}</p>
                <p>SGST (9%): ₹{{ number_format($total_sgst, 2) }}</p>
                @php $grand_total = $subtotal + $total_cgst + $total_sgst; @endphp
            @endif
            <p><strong>Total Amount After Tax: ₹{{ number_format($grand_total, 2) }}</strong></p>
            <p class="amount-words">Amount in Words: <em>{{ ucfirst(numberToWords(round($grand_total))) }} Only</em></p>
        </div>
    </div>

    {{-- QR Code --}}
    <div id="invoiceQR" style="margin-top:20px;"></div>

    {{-- Signature Boxes --}}
    <div class="signature-boxes">
        <div class="signature-box">Authorized Signatory</div>
        <div class="signature-box">Receiver's Sign</div>
    </div>

    {{-- Action Buttons --}}
    <div style="text-align:center; margin-top:20px;">
        <button onclick="printInvoice()" class="btn btn-primary">Print Invoice</button>
        <button onclick="downloadPDF()" class="btn btn-success">Download PDF</button>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
function printInvoice() { window.print(); }
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
        doc.save('Invoice-{{ $order->id }}.pdf');
    });
}

// QR Code
new QRCode(document.getElementById("invoiceQR"), {
    text: "Invoice ID: {{ $order->id }} | Total: ₹{{ number_format($grand_total, 2) }}",
    width: 80,
    height: 80
});
</script>
@endsection
