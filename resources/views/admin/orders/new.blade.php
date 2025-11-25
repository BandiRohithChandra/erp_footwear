@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="dashboard-header flex justify-between items-center mb-6">
        <h1>New Orders</h1>
        <a href="{{ route('admin.online') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Back to Dashboard
        </a>
    </div>

    <!-- Client Filter -->
    <form method="GET" class="mb-4 flex space-x-2 items-center">
        <label for="client_id" class="font-semibold">Filter by Client:</label>
        <select name="client_id" id="client_id" class="p-2 border rounded">
            <option value="">All Clients</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Filter
        </button>
    </form>

    <!-- Orders Table -->
    <div class="orders-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client Name</th>
                    <th>Ordered By</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>

                        {{-- Client / Customer --}}
                        <td>{{ $order->client->name ?? $order->customer_name ?? 'N/A' }}</td>

                        {{-- Ordered By --}}
                        <td>{{ $order->user->name ?? 'N/A' }}</td>

                        {{-- Items --}}
                        <td>
                            <div class="items-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $items = $order->cart_items ?? [];
                                        @endphp

                                        @forelse($items as $item)
                                            <tr>
                                                <td>{{ $item['name'] ?? '' }}</td>
                                                <td>{{ $item['quantity'] ?? '' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2"><em>No items</em></td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </td>

                        <td>${{ number_format($order->total, 2) }}</td>
                        <td>{{ ucfirst($order->payment_method) }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($order->status) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d M, Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
.container {
    max-width: 1300px;
    margin: 0 auto;
    padding: 20px;
    font-family: "Inter", sans-serif;
}

/* Header */
.dashboard-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
}

/* Orders Table */
.orders-table table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.orders-table th,
.orders-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
    font-size: 0.95rem;
    vertical-align: top;
}

.orders-table th {
    background-color: #f8fafc;
    color: #334155;
    font-weight: 700;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

.orders-table tbody tr:hover {
    background-color: #f1f5f9;
}

/* Status badges */
.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: capitalize;
}
.status-pending { background-color: #fef3c7; color: #92400e; }
.status-new { background-color: #dbeafe; color: #1d4ed8; }
.status-completed { background-color: #dcfce7; color: #166534; }
.status-cancelled { background-color: #fee2e2; color: #991b1b; }

/* Items sub-table */
.items-table table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 5px;
}

.items-table th,
.items-table td {
    padding: 4px 6px;
    border: 1px solid #e2e8f0;
    font-size: 0.85rem;
}

.items-table th {
    background-color: #f1f5f9;
    color: #475569;
    font-weight: 600;
}

/* Buttons */
a, button {
    transition: all 0.2s ease;
}
a:hover, button:hover {
    opacity: 0.85;
}
</style>
@endsection
