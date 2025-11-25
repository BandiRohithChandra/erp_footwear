@extends('layouts.app')

@section('content')
<style>
    .payments-container {
        max-width: 1200px;
        margin: 30px auto;
        padding: 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    .payments-container h2 {
        color: #2c3e50;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 26px;
    }

    .alert-success {
        background: #eafaf1;
        border-left: 5px solid #28a745;
        padding: 15px 20px;
        border-radius: 8px;
        color: #2d8659;
        font-size: 15px;
    }

    table.custom-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        border-radius: 10px;
        overflow: hidden;
    }

    table.custom-table thead {
        background: #2c3e50;
        color: #fff;
    }

    table.custom-table th,
    table.custom-table td {
        padding: 12px 15px;
        text-align: center;
        font-size: 14px;
        border-bottom: 1px solid #ddd;
    }

    table.custom-table tr:hover {
        background: #f5f7fa;
    }

    .badge {
        display: inline-block;
        padding: 5px 12px;
        font-size: 12px;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge-pending {
        background: #ffc107;
        color: #000;
    }

    .badge-partial {
        background: #17a2b8;
        color: #fff;
    }

    .badge-paid {
        background: #28a745;
        color: #fff;
    }

    .btn {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        text-decoration: none;
        transition: 0.2s ease-in-out;
        display: inline-block;
    }

    .btn-paid {
        background: #20c997;
        color: #fff;
    }

    .btn-paid:hover {
        background: #17a085;
    }

    .completed-row {
        background: #e6f7ff;
    }
</style>

<div class="payments-container">
    <h2>💰 Pending & Recent Payments</h2>

    @if($pendingOrders->isEmpty())
        <div class="alert-success">
            ✅ No pending or recent payments found.
        </div>
    @else
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Client</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingOrders as $order)
                    <tr class="{{ $order->status === 'completed' ? 'completed-row' : '' }}">
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td style="color:#007bff;">₹{{ number_format($order->total, 2) }}</td>
                        <td>
                            @if($order->status === 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @elseif($order->status === 'placed')
                                <span class="badge badge-partial">Placed</span>
                            @elseif($order->status === 'completed')
                                <span class="badge badge-paid">Paid ✔</span>
                            @endif
                        </td>
                        <td>
                            @if($order->status !== 'completed')
                                <form action="{{ route('admin.payments.markPaid', $order->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-paid">Mark Paid</button>
                                </form>
                            @else
                                <span class="badge badge-paid">Already Paid</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
