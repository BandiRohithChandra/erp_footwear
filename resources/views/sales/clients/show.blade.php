@extends('layouts.app')

@section('content')
<style>
/* General Container */
.container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 0 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
}

/* Header */
.header {
    background: linear-gradient(90deg, #4f46e5, #6366f1);
    color: #fff;
    padding: 25px 30px;
    border-radius: 10px 10px 0 0;
}
.header h2 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
}
.header p {
    margin-top: 5px;
    color: #e0e7ff;
    font-size: 1rem;
}

/* Tabs */
.tabs {
    display: flex;
    border-bottom: 2px solid #e5e7eb;
    margin-top: 20px;
}
.tab-btn {
    padding: 12px 20px;
    cursor: pointer;
    border: none;
    background: none;
    font-weight: 600;
    font-size: 0.95rem;
    color: #6b7280;
    transition: all 0.3s;
}
.tab-btn.active {
    border-bottom: 3px solid #4f46e5;
    color: #4f46e5;
}
.tab-btn:hover {
    color: #4f46e5;
}

/* Tab Content */
.tab-content {
    display: none;
    padding: 25px 0;
}
.tab-content.active {
    display: block;
}

/* Client Info Cards */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}
.info-card {
    background: #f9fafb;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.info-card strong {
    display: inline-block;
    margin-bottom: 5px;
}

/* Orders */
.order-card {
    border-left: 5px solid #4f46e5;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}
.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.order-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.order-header div {
    margin-bottom: 8px;
}
.invoice-btn {
    text-decoration: none;
    background: #4f46e5;
    color: #fff;
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    transition: background 0.3s;
}
.invoice-btn:hover {
    background: #4338ca;
}

/* Orders Table */
.order-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.order-table th, .order-table td {
    border: 1px solid #e5e7eb;
    padding: 12px 15px;
    text-align: left;
}
.order-table th {
    background: #f3f4f6;
    font-weight: 600;
    color: #111827;
}
.order-table tbody tr:hover {
    background: #f9fafb;
}

/* Payments Placeholder */
.payments-placeholder {
    text-align: center;
    color: #9ca3af;
    font-size: 1rem;
    padding: 40px 0;
}
</style>

<div class="container">

<!-- Back Button -->
<a href="{{ url()->previous() }}" class="back-btn">
    <span class="back-icon">←</span> Back
</a>

<style>
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(90deg, #9747FF 0%, #92D3F5 100%);
    color: #fff;
    font-weight: 600;
    font-size: 16px;
    border-radius: 30px;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    margin-bottom: 25px;
}

.back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    background: linear-gradient(90deg, #92D3F5 0%, #9747FF 100%);
}

.back-icon {
    font-size: 18px;
}
</style>

    <!-- Header -->
    <div class="header">
        <h2>{{ $client->business_name }} <span style="font-weight: 400;">({{ $client->name }})</span></h2>
        <!-- <p>Client dashboard with orders and details</p> -->
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-btn active" data-tab="info">Party Info</button>
        <button class="tab-btn" data-tab="orders">Orders</button>
        <button class="tab-btn" data-tab="payments">Payments</button>
    </div>

    <!-- Tab Contents -->
    <div id="info" class="tab-content active">
        <div class="info-grid">
            <div class="info-card"><strong>Email:</strong> {{ $client->email }}</div>
            <div class="info-card"><strong>Phone:</strong> {{ $client->phone ?? '-' }}</div>
            <div class="info-card"><strong>GST No:</strong> {{ $client->gst_no ?? '-' }}</div>
            <div class="info-card"><strong>Category:</strong> {{ ucfirst($client->category ?? '-') }}</div>
            <div class="info-card" style="grid-column: span 2;"><strong>Address:</strong> {{ $client->address ?? '-' }}</div>
        </div>
    </div>

    <div id="orders" class="tab-content">
        @forelse ($client->orders as $order)
        <div class="order-card">
            <div class="order-header">
                <div><strong>Order #:</strong> {{ $order->id }}</div>
                <div><strong>Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</div>
                <div><strong>Total:</strong> ₹{{ number_format($order->total, 2) }}</div>
                <a href="{{ route('orders.invoice', $order->id) }}" class="invoice-btn">View Invoice</a>
            </div>

            <table class="order-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>₹{{ number_format($product->pivot->price, 2) }}</td>
                        <td>₹{{ number_format($product->pivot->quantity * $product->pivot->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @empty
        <p class="payments-placeholder">No orders placed by this client yet.</p>
        @endforelse
    </div>

    <div id="payments" class="tab-content">
        <p class="payments-placeholder">Payments section coming soon...</p>
    </div>
</div>

<script>
const tabs = document.querySelectorAll('.tab-btn');
const contents = document.querySelectorAll('.tab-content');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        const target = tab.getAttribute('data-tab');
        contents.forEach(c => c.classList.remove('active'));
        document.getElementById(target).classList.add('active');
    });
});
</script>
@endsection
