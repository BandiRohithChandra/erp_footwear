@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>Pending Orders</h1>
        <div class="flex space-x-2 flex-wrap header-buttons">
            <a href="{{ url()->previous() }}" class="back-btn">Back</a>
            <a href="{{ route('admin.online') }}" class="back-btn bg-blue-500 hover:bg-blue-600">Back to Dashboard</a>
        </div>
    </div>

    <!-- Client Filter -->
    <form method="GET" class="filter-form">
        <label for="client_id">Filter by Client:</label>
        <select name="client_id" id="client_id">
            <option value="">All Clients</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="apply-btn">Apply Filter</button>
        <a href="{{ route('admin.orders.pending') }}" class="reset-btn">Reset</a>
    </form>

    <!-- Desktop Orders Table -->
    <div class="orders-table desktop-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Ordered By</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                @php
                    $items = is_array($order->cart_items) ? $order->cart_items : (json_decode($order->cart_items, true) ?? []);
                @endphp
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->client->name ?? $order->customer_name ?? 'N/A' }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>
                        @foreach($items as $item)
                            <div>{{ $item['name'] ?? '' }} x {{ $item['quantity'] ?? '' }}</div>
                        @endforeach
                        @if(empty($items))
                            <em>No items</em>
                        @endif
                    </td>
                    <td>${{ number_format($order->total, 2) }}</td>
                    <td>{{ ucfirst($order->payment_method) }}</td>
                    <td><span class="status-badge status-{{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</span></td>
                    <td>{{ $order->created_at->format('d M, Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Orders Cards -->
    <div class="mobile-orders">
        @foreach($orders as $order)
        @php
            $items = is_array($order->cart_items) ? $order->cart_items : (json_decode($order->cart_items, true) ?? []);
        @endphp
        <div class="order-card">
            <h3>Order #{{ $order->id }}</h3>
            <p><strong>Customer:</strong> {{ $order->client->name ?? $order->customer_name ?? 'N/A' }}</p>
            <p><strong>Ordered By:</strong> {{ $order->user->name ?? 'N/A' }}</p>
            <p><strong>Items:</strong></p>
            <ul>
                @forelse($items as $item)
                    <li>{{ $item['name'] ?? '' }} x {{ $item['quantity'] ?? '' }}</li>
                @empty
                    <li><em>No items</em></li>
                @endforelse
            </ul>
            <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
            <p><strong>Payment:</strong> {{ ucfirst($order->payment_method) }}</p>
            <p><strong>Status:</strong> <span class="status-badge status-{{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</span></p>
            <p><strong>Created:</strong> {{ $order->created_at->format('d M, Y H:i') }}</p>
        </div>
        @endforeach
    </div>
</div>

<style>
/* Container */
.container {
    max-width: 1300px;
    width: 100%;
    margin: 0 auto;
    padding: 20px;
    font-family: "Inter", sans-serif;
}

/* Header */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 25px;
}
.dashboard-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
}

/* Buttons */
.back-btn {
    background-color: #3b82f6;
    color: #fff;
    padding: 10px 22px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    margin-top: 5px;
    transition: all 0.2s ease-in-out;
}
.back-btn:hover {
    background-color: #2563eb;
    transform: translateY(-2px);
}

/* Filter Form */
.filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    margin-bottom: 20px;
    padding: 12px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}
.filter-form label { font-weight: 500; }
.filter-form select, .filter-form button, .filter-form a {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
}
.apply-btn { background-color: #2563eb; color: #fff; border: none; }
.apply-btn:hover { background-color: #1d4ed8; }
.reset-btn { background-color: #e5e7eb; color: #374151; text-decoration: none; }
.reset-btn:hover { background-color: #d1d5db; }

/* Desktop Table */
.desktop-table { display: block; overflow-x: auto; }
.orders-table table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}
.orders-table th, .orders-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
    vertical-align: top;
    font-size: 0.9rem;
}
.orders-table th {
    background: #f8fafc;
    font-weight: 600;
    font-size: 0.85rem;
}
.orders-table tbody tr:hover { background-color: #f1f5f9; }

/* Status badges - Professional UI */
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
    text-align: center;
    min-width: 90px;
    transition: all 0.2s ease;
}
.status-pending {
    background: linear-gradient(90deg, #fef3c7, #fde68a);
    color: #92400e;
}
.status-completed {
    background: linear-gradient(90deg, #dcfce7, #bbf7d0);
    color: #166534;
}
.status-cancelled {
    background: linear-gradient(90deg, #fee2e2, #fecaca);
    color: #991b1b;
}
.status-processing {
    background: linear-gradient(90deg, #dbeafe, #bfdbfe);
    color: #1e40af;
}
.status-shipped {
    background: linear-gradient(90deg, #e0f2fe, #bae6fd);
    color: #0369a1;
}

/* Mobile Cards */
.mobile-orders { display: none; }
.order-card {
    background: #fff;
    padding: 18px;
    margin-bottom: 16px;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}
.order-card h3 { margin-bottom: 10px; color: #1e293b; font-size: 1.1rem; }
.order-card p { margin-bottom: 6px; font-size: 0.9rem; }
.order-card ul { padding-left: 18px; margin-bottom: 8px; }

/* Responsive */
@media (max-width: 1024px) {
    .dashboard-header h1 { font-size: 1.5rem; }
    .filter-form { gap: 8px; }
}

@media (max-width: 768px) {
    .desktop-table { display: none; }
    .mobile-orders { display: block; }
}
</style>
@endsection
