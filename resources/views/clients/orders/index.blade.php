@extends('layouts.app')

@section('content')
<style>
/* Container */
.container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 0 20px;
    font-family: 'Arial', sans-serif;
}

/* Heading */
h1 {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 40px;
    color: #111827;
}

/* Order Card */
.order-card {
    background: #fff;
    border-radius: 15px;
    padding: 20px 25px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}

/* Flex Layout inside Card */
.order-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

/* Column widths */
.col-order { width: 10%; font-weight: 700; color:#111827; }
.col-date { width: 20%; color:#6b7280; }
.col-total { width: 15%; font-weight:600; }
.col-status { width: 15%; }
.col-invoice { width: 20%; text-align: right; }

/* Transport row */
.transport-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 8px;
    font-size: 0.9rem;
    color: #374151;
}
.transport-item { flex: 1 1 200px; }

/* Status Badge */
.status {
    padding: 5px 12px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.85rem;
    color: #fff;
    display: inline-block;
}
.status.pending { background-color: #f59e0b; }
.status.completed { background-color: #10b981; }
.status.cancelled { background-color: #ef4444; }
.status.processing { background-color: #3b82f6; }
.status.accepted { background-color: #6366f1; }

/* Invoice Link */
.invoice-link {
    text-decoration: none;
    color: #fff;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 8px;
    background-color: #4f46e5;
    transition: background 0.3s ease;
}
.invoice-link:hover {
    background-color: #4338ca;
}

/* Pagination */
.pagination {
    text-align: center;
    margin-top: 30px;
}

/* Back button */
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
.back-icon { font-size: 18px; }

/* Responsive */
@media (max-width: 768px) {
    .order-row { flex-direction: column; align-items: flex-start; gap: 5px; }
    .col-order, .col-date, .col-total, .col-status, .col-invoice { width: 100%; text-align: left; }
}

* Status filter */
.status-filter {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
}
.status-filter select {
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    font-size: 1rem;
}
.status-filter button {
    margin-left: 10px;
    padding: 8px 16px;
    background-color: #4f46e5;
    color: #fff;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
}
.status-filter button:hover {
    background-color: #4338ca;
}
</style>

<div class="container">
   <!-- Back Button -->
<button type="button" onclick="history.back()" 
        class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition mb-6">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    Back
</button>

    <h1>📦 My Orders</h1>
    
<div class="status-filter">
    <form method="GET" action="{{ route('clients.orders.index') }}">
        @php
    $statuses = ['pending','accepted','processing','delivered','rejected','paid','partially paid'];
@endphp

<select name="status">
    <option value="">All Statuses</option>
    @foreach($statuses as $statusOption)
        <option value="{{ $statusOption }}" {{ strtolower(request('status')) == $statusOption ? 'selected' : '' }}>
            {{ ucfirst($statusOption) }}
        </option>
    @endforeach
</select>

        <button type="submit">Filter</button>
    </form>
</div>


    @if($orders->count())
        <!-- Headings -->
        <div class="order-card" style="background:#f3f4f6; margin-bottom:10px;">
            <div class="order-row">
                <span class="col-order">Order No</span>
                <span class="col-date">Placed At</span>
                <span class="col-total">Total Amount</span>
                <span class="col-status">Status</span>
                <span class="col-invoice">Invoice</span>
            </div>
        </div>

        @foreach($orders as $order)
            @php
                $status = strtolower($order->status ?? '');
                $statusClass = in_array($status, ['pending', 'completed', 'cancelled', 'processing', 'accepted']) ? $status : 'pending';
            @endphp

            <div class="order-card" onclick="window.location='{{ route('client.orders.show', $order->id) }}';" style="cursor:pointer;">
                <div class="order-row">
                    <span class="col-order">#{{ $order->id }}</span>
                    <span class="col-date">{{ $order->created_at->format('d M Y H:i') }}</span>
                    <span class="col-total">₹{{ number_format($order->total, 2) }}</span>
                    <span class="col-status">
                        <span class="status {{ $statusClass }}">{{ ucfirst($statusClass) }}</span>
                    </span>
                    <span class="col-invoice">
                        @if(in_array($status, ['accepted', 'processing', 'completed']))
                            <a href="{{ route('client.orders.invoice', $order->id) }}" 
                               target="_blank"
                               class="invoice-link"
                               onclick="event.stopPropagation();">
                                View Invoice
                            </a>
                        @else
                            <span style="color:#9ca3af; font-weight:500;">Not available</span>
                        @endif
                    </span>
                </div>

                <!-- Transport / Shipping Info -->
                <div class="transport-row">
                    <div class="transport-item"><strong>Transport Name:</strong> {{ $order->transport_name ?? 'N/A' }}</div>
                    <div class="transport-item"><strong>Address:</strong> {{ $order->transport_address ?? 'N/A' }}</div>
                    <div class="transport-item"><strong>ID:</strong> {{ $order->transport_id ?? 'N/A' }}</div>
                    <div class="transport-item"><strong>Phone:</strong> {{ $order->transport_phone ?? 'N/A' }}</div>
                </div>

            </div>
        @endforeach

        <div class="pagination">
            {{ $orders->links() }}
        </div>
    @else
        <p style="text-align:center; font-size:1.2rem; color:#6b7280;">No orders found.</p>
    @endif
</div>
@endsection
