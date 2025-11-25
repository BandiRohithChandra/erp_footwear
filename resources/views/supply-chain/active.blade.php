@extends('layouts.app')

@section('content')
<div class="supply-chain-container">
    <div class="custom-card">
        <div class="custom-card-header">
            <h2>Active Supplier Orders</h2>
            <p>Overview of all raw material / supplier orders and their receiving status</p>
        </div>

        <div class="custom-card-body">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>PO Number</th>
                        <th>Ordered Qty</th>
                        <th>Received Qty</th>
                        <th>Remaining Qty</th>
                        <th>Current Stage</th>
                        <th>Start Date</th>
                        <!-- <th>Status</th> -->
                    </tr>
                </thead>

                <tbody>
@forelse($activeItems as $order)

@php
    $orderedQty = 0;
    $receivedQty = 0;

    foreach ($order->items as $item) {
        if ($item['type'] === 'sole') {

            foreach ($item['sizes_qty'] as $size => $qty) {

                // Add to total ordered qty
                $orderedQty += $qty;

                // Fetch all stock arrival entries for this item + size + supplier
                $arrivals = \App\Models\StockArrival::where('supplier_id', $order->supplier_id)
                    ->where('item_id', $item['id'])
                    ->where('size', $size)
                    ->orderBy('id')
                    ->get();

                // The remaining quantity is simply the last arrival's "quantity"
                $remainingForSize = $arrivals->sum('quantity');

                // Received qty = Ordered qty - Remaining qty
                $receivedForSize = max($qty - $remainingForSize, 0);

                $receivedQty += $receivedForSize;
            }
        }
    }

    // Final calculation
    $remainingQty = $orderedQty - $receivedQty;

    // Determine Stage
    if ($receivedQty == 0) {
        $stage = 'Awaiting Stock';
    } elseif ($receivedQty < $orderedQty) {
        $stage = 'Partially Received';
    } else {
        $stage = 'Completed';
    }
@endphp

<tr>
    {{-- Supplier --}}
    <td>{{ $order->supplier->name ?? 'Unknown Supplier' }}</td>

    {{-- PO Number --}}
    <td>{{ $order->po_number }}</td>

    {{-- Ordered Qty --}}
    <td class="font-semibold">{{ $orderedQty }}</td>

    {{-- Received Qty --}}
    <td class="text-green-600 font-semibold">{{ $receivedQty }}</td>

    {{-- Remaining Qty --}}
    <td class="text-red-600 font-semibold">{{ $remainingQty }}</td>

    {{-- Stage --}}
   <td>
    <span class="stage-badge 
        @if($stage === 'Awaiting Stock') awaiting 
        @elseif($stage === 'Partially Received') partial 
        @elseif($stage === 'Completed') completed 
        @endif">
        {{ $stage }}
    </span>
</td>


    {{-- Start Date --}}
    <td>{{ $order->order_date ? $order->order_date->format('Y-m-d') : 'N/A' }}</td>

    <!-- {{-- Status --}}
    <td>
        <span class="status-badge {{ $order->status }}">
            {{ ucfirst($order->status) }}
        </span>
    </td> -->
</tr>

@empty
<tr>
    <td colspan="8" class="empty-msg">No active supplier orders found.</td>
</tr>
@endforelse
</tbody>


            </table>
        </div>
    </div>
</div>

<style>
    body {
        font-family: "Segoe UI", "Roboto", sans-serif;
        background: linear-gradient(135deg, #e3f2fd, #f8f9fa);
        margin: 0;
        padding: 20px;
    }

    .supply-chain-container {
        max-width: 1100px;
        margin: auto;
    }

    .custom-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform 0.2s ease-in-out;
    }

    .custom-card:hover {
        transform: translateY(-4px);
    }

    /* Stage Badges */
.stage-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: capitalize;
}

/* Awaiting Stock */
.stage-badge.awaiting {
    background: #ffc107;
    color: #000;
    box-shadow: 0 2px 6px rgba(255,193,7,0.4);
}

/* Partially Received */
.stage-badge.partial {
    background: #17a2b8;
    color: #fff;
    box-shadow: 0 2px 6px rgba(23,162,184,0.4);
}

/* Completed */
.stage-badge.completed {
    background: #28a745;
    color: #fff;
    box-shadow: 0 2px 6px rgba(40,167,69,0.4);
}


    .custom-card-header {
        padding: 20px 25px;
        border-bottom: 1px solid #e9ecef;
        background: linear-gradient(135deg, #007bff, #00c6ff);
        color: #fff;
    }

    .custom-card-header h2 {
        margin: 0;
        font-size: 1.5rem;
    }

    .custom-card-header p {
        margin: 5px 0 0;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .custom-card-body {
        padding: 20px;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 12px;
        overflow: hidden;
    }

    .custom-table thead {
        background: #f1f5f9;
    }

    .custom-table th, 
    .custom-table td {
        padding: 14px 18px;
        text-align: left;
        font-size: 0.95rem;
    }

    .custom-table th {
        font-weight: 600;
        color: #495057;
    }

    .custom-table tbody tr {
        transition: background 0.3s;
    }

    .custom-table tbody tr:hover {
        background: #f8f9fa;
    }

    .empty-msg {
        text-align: center;
        color: #6c757d;
        padding: 20px 0;
        font-style: italic;
    }

    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-badge.pending {
        background: #ffc107;
        color: #000;
    }

    .status-badge.partially_received {
        background: #17a2b8;
        color: #fff;
    }

    .status-badge.received {
        background: #28a745;
        color: #fff;
    }

    .status-badge.processing {
        background: #007bff;
        color: #fff;
    }
</style>
@endsection
