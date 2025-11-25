
<style>
/* Container */
.order-card {
    max-width: 1000px;
    margin: 20px auto;
    padding: 25px;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    font-family: 'Roboto', sans-serif;
    color: #1f2937;
}

/* Header */
.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.order-header h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
}
.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    color: #fff;
    text-transform: uppercase;
}
.status-badge.completed { background-color: #10b981; }
.status-badge.pending { background-color: #f59e0b; }
.status-badge.other { background-color: #6b7280; }

/* Order Details */
.order-details {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 25px;
}
.order-details div p {
    margin: 4px 0;
    font-size: 0.95rem;
}

/* Products Table */
.products-section h3 {
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 10px;
}
.table-wrapper {
    overflow-x: auto;
}
.order-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}
.order-table th, .order-table td {
    padding: 10px 12px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}
.order-table th {
    background-color: #f3f4f6;
    font-weight: 600;
    text-transform: uppercase;
}
.order-table td.center { text-align: center; }
.order-table td.right { text-align: right; }
.order-table .empty {
    text-align: center;
    color: #6b7280;
    padding: 15px 0;
}

/* Summary */
.order-summary {
    margin-top: 20px;
    font-size: 1rem;
}
.order-summary p {
    margin: 5px 0;
}
.order-summary span { display: inline-block; width: 160px; }
.order-summary .total span { font-weight: 700; }
.order-summary .total strong { font-size: 1.1rem; }

/* Footer */
.order-footer {
    margin-top: 25px;
    text-align: center;
    font-weight: 500;
    color: #374151;
}

/* Responsive */
@media (max-width: 768px) {
    .order-header, .order-details {
        flex-direction: column;
        align-items: flex-start;
    }
    .order-details div p span {
        display: inline;
        width: auto;
    }
    .order-summary span {
        width: 120px;
    }
    .order-table th, .order-table td {
        padding: 8px 10px;
        font-size: 0.85rem;
    }
}


/* Table Wrapper */
.table-wrapper {
    overflow-x: auto;
}

/* Order Table */
.order-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

/* Table Header */
.order-table th {
    background-color: #4f46e5; /* deep purple header */
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

/* Table Body */
.order-table td {
    padding: 12px;
    border: 1px solid #e5e7eb;
    vertical-align: middle;
}

/* Alternating Row Colors */
.order-table tbody tr:nth-child(odd) {
    background-color: #f9fafb; /* light gray */
}
.order-table tbody tr:nth-child(even) {
    background-color: #ffffff; /* white */
}

/* Center & Right alignment */
.order-table td.center { text-align: center; }
.order-table td.right { text-align: right; }

/* Empty Row */
.order-table .empty {
    text-align: center;
    color: #6b7280;
    padding: 20px 0;
    font-style: italic;
}

/* Responsive */
@media (max-width: 768px) {
    .order-table th, .order-table td {
        padding: 8px 10px;
        font-size: 0.85rem;
    }
}
</style>

@php
// Products collection
$products = collect();

// Determine source of products
if ($order->products?->count()) {
    $products = $order->products;
} elseif ($type === 'production' && $order->relationLoaded('quotation') && $order->quotation?->products?->count()) {
    $products = $order->quotation->products;
} elseif ($order->cart_items) {
    $cartItems = json_decode($order->cart_items, true) ?? [];
    foreach ($cartItems as $item) {
        $p = \App\Models\Product::find($item['product_id']);
        if ($p) {
            $products->push((object)[
                'name' => $p->name,
                'sku' => $p->sku,
                'pivot' => (object)[
                    'quantity' => $item['quantity'] ?? 1,
                    'unit_price' => $item['unit_price'] ?? $p->price ?? 0,
                    'variations' => $item['variations'] ?? [],
                ]
            ]);
        }
    }
}

// Subtotal
$subtotal = 0;

// Customer info
if ($type === 'production') {
    $customer = $order->clientOrder?->client ?? null;
    $createdBy = $order->user ?? null;
    $dueDate = $order->due_date;
} else {
    $customer = $order->client ?? null;
    $createdBy = $order->user ?? null;
    $dueDate = $order->due_date;
}

// Paid amount
$paidAmount = $order->paid_amount ?? 0;
@endphp

<div class="order-card">
    <!-- Header -->
    <div class="order-header">
        <h2>Order #{{ $order->id }}</h2>
        @php
            $statusClass = $paidAmount >= ($subtotal ?? 0) ? 'completed' : ($paidAmount > 0 ? 'pending' : 'other');
            $statusText = $paidAmount >= ($subtotal ?? 0) ? 'Paid' : ($paidAmount > 0 ? 'Partially Paid' : 'Pending');
        @endphp
        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
    </div>

    <!-- Order Details -->
    <div class="order-details">
        <div>

<p><strong>Customer:</strong> {{ $customer->business_name ?? $customer->name ?? 'N/A' }}</p>
<p><strong>Email:</strong> {{ $customer->email ?? 'N/A' }}</p>
<p><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>






        </div>
        <div>
            <p><strong>Created By:</strong> {{ $createdBy->name ?? 'N/A' }}</p>
            <p><strong>Due Date:</strong> {{ $dueDate?->format('d-m-Y') ?? 'N/A' }}</p>
            <p><strong>Created At:</strong> {{ $order->created_at?->format('d-m-Y H:i') }}</p>
        </div>
    </div>

    <!-- Products Table -->
    <div class="products-section">
        <h3>Products</h3>
        <div class="table-wrapper">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Qty</th>
                        <th>Color</th>
                        <th>Size</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>

@forelse($products as $p)
    @php
        $variations = $p->pivot->variations ?? [];

        if (is_string($variations)) {
            $variations = json_decode($variations, true);

            // Decode again if still string (double-encoded JSON)
            if (is_string($variations)) {
                $variations = json_decode($variations, true) ?? [];
            }
        }

        if (empty($variations)) {
            $variations[] = [
                'color' => $p->pivot->color ?? '-',
                'sizes' => ['default' => $p->pivot->quantity ?? 1],
            ];
        }
    @endphp

    @foreach($variations as $v)
        @php
            $color = $v['color'] ?? '-';
            $sizes = $v['sizes'] ?? [];
        @endphp

        @foreach($sizes as $size => $qty)
            @php
                $qty = (int) $qty;
                if ($qty <= 0) continue;
                $unitPrice = $p->pivot->unit_price ?? $p->price ?? 0;
                $lineTotal = $qty * $unitPrice;
                $subtotal += $lineTotal;
            @endphp
            <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->sku }}</td>
                <td class="center">{{ $qty }}</td>
                <td>{{ $color }}</td>
                <td>{{ $size }}</td>
                <td class="right">₹{{ number_format($unitPrice, 2) }}</td>
                <td class="right">₹{{ number_format($lineTotal, 2) }}</td>
            </tr>
        @endforeach
    @endforeach
@empty
    <tr>
        <td colspan="7" class="empty">No products found.</td>
    </tr>
@endforelse


</tbody>

            </table>
        </div>
    </div>

    <!-- Summary -->
    @php
        $gstRate = 18;
        $gstAmount = ($subtotal * $gstRate) / 100;
        $grandTotal = $subtotal + $gstAmount;
        $balance = max($grandTotal - $paidAmount, 0);
    @endphp

    <div class="order-summary">
        <p><span>Subtotal:</span> <strong>₹{{ number_format($subtotal, 2) }}</strong></p>
        <p><span>GST ({{ $gstRate }}%):</span> <strong>₹{{ number_format($gstAmount, 2) }}</strong></p>
        <p><span>Total:</span> <strong>₹{{ number_format($grandTotal, 2) }}</strong></p>
        <p><span>Paid:</span> <strong>₹{{ number_format($paidAmount, 2) }}</strong></p>
        <p class="total"><span>Balance:</span> <strong>₹{{ number_format($balance, 2) }}</strong></p>
    </div>

    <div class="order-footer">
        <p>Thank you for your order.</p>
    </div>
</div>
