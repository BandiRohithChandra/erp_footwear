@extends('layouts.app')

@section('content')
<div class="payments-container">
    <h2>💰 Completed Orders</h2>

    @if($orders->isEmpty())
        <div class="alert-success">
            ✅ No completed orders found.
        </div>
    @else
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Client</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->client->name ?? 'N/A' }}</td>
                        <td style="color:#007bff;">₹{{ number_format($order->total, 2) }}</td>
                        <td>
                            <span class="badge badge-paid">Completed</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<style>
/* Reuse the same styles as your pending payments table */
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

.badge-paid {
    background: #28a745;
    color: #fff;
}
</style>
@endsection
