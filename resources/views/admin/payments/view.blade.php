<!-- resources/views/admin/payments/view.blade.php -->
@extends('layouts.app')

@section('content')
<style>
    .invoice-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 25px;
        background: #fefefe;
        border-radius: 15px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .invoice-header h2 {
        color: #34495e;
        font-size: 28px;
        font-weight: 600;
    }

    .invoice-header span {
        background: #6c63ff;
        color: #fff;
        padding: 8px 18px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 500;
    }

    table.invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    table.invoice-table th,
    table.invoice-table td {
        padding: 12px 18px;
        border-bottom: 1px solid #e0e0e0;
        text-align: left;
        font-size: 15px;
    }

    table.invoice-table th {
        background: #34495e;
        color: #fff;
        font-weight: 600;
    }

    table.invoice-table td {
        color: #2c3e50;
    }

    .amount {
        font-weight: bold;
        color: #6c63ff;
    }

    .paid {
        color: #28a745;
        font-weight: bold;
    }

    .pending {
        color: #dc3545;
        font-weight: bold;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        color: #fff;
    }

    .status-pending {
        background: #ffc107;
        color: #000;
    }

    .status-partial {
        background: #17a2b8;
    }

    .status-paid {
        background: #28a745;
    }

    .back-btn {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 25px;
        background: #20c997;
        color: #fff;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: 0.2s ease-in-out;
    }

    .back-btn:hover {
        background: #17a085;
    }

    .badge-overdue {
        background: #dc3545;
        color: #fff;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        margin-left: 8px;
    }
</style>

<div class="invoice-container">
    <div class="invoice-header">
        <h2>Invoice Details: #{{ $invoice->id }}</h2>
        <span>{{ ucfirst($invoice->status) }}</span>
    </div>

    <table class="invoice-table">
        <tr>
    <th>Order #</th>
    <td>#{{ $invoice->order_id ?? 'N/A' }}</td>
</tr>

        <tr>
            <th>Client</th>
            <td>{{ $invoice->client->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Total Amount</th>
            <td class="amount">₹{{ number_format($invoice->total_amount, 2) }}</td>
        </tr>
        <tr>
            <th>Amount Paid</th>
            <td class="paid">₹{{ number_format($invoice->amount_paid, 2) }}</td>
        </tr>
        <tr>
            <th>Pending Amount</th>
            <td class="pending">₹{{ number_format($invoice->total_amount - $invoice->amount_paid, 2) }}</td>
        </tr>
        <tr>
            <th>Due Date</th>
            <td>
                {{ $invoice->due_date ?? 'N/A' }}
                @if($invoice->due_date && \Carbon\Carbon::parse($invoice->due_date)->isPast())
                    <span class="badge-overdue">Overdue</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $invoice->created_at }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td>{{ $invoice->updated_at }}</td>
        </tr>
    </table>

    <a href="{{ route('admin.payments.pending') }}" class="back-btn">← Back to Pending Payments</a>
</div>
@endsection
