@extends('layouts.app')

@section('content')
<style>
/* ===== Container ===== */
.container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* ===== Heading ===== */
h2 {
    margin-bottom: 20px;
    font-size: 28px;
    color: #333;
    border-bottom: 2px solid #007BFF;
    padding-bottom: 8px;
}

/* ===== Form Groups ===== */
.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    color: #444;
}

input[type="text"], input[type="number"], input[type="date"], select, textarea {
    width: 100%;
    padding: 7px 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    transition: all 0.2s ease;
    font-size: 14px;
}

input:focus, select:focus, textarea:focus {
    border-color: #007BFF;
    outline: none;
    box-shadow: 0 0 6px rgba(0,123,255,0.25);
}

/* ===== Table ===== */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

thead {
    background-color: #007BFF;
    color: #fff;
    font-weight: 600;
}

thead th, tbody td {
    padding: 12px;
    text-align: left;
}

tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

tbody tr:hover {
    background-color: #e9f5ff;
}

/* ===== Buttons ===== */
.btn {
    padding: 10px 18px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-success {
    background-color: #28a745;
    color: #fff;
}

.btn-success:hover {
    background-color: #218838;
}

.btn-secondary {
    background-color: #6c757d;
    color: #fff;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

/* ===== Action Bar ===== */
.action-bar {
    margin-top: 20px;
    text-align: right;
    border-top: 1px solid #ddd;
    padding-top: 15px;
}
</style>

<div class="container">

<a href="{{ url()->previous() }}" class="btn btn-back">
    &#8592; Back
</a>

<style>
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background-color: #f4f6f8;
    color: #1f4e79;
    font-weight: 500;
    font-size: 14px;
    border: 1px solid #c3cfd9;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-back:hover {
    background-color: #1f4e79;
    color: #fff;
    border-color: #1f4e79;
}
</style>


    <h2>Deliver Order #{{ $order->id }}</h2>

    <!-- Order Info -->
    <div class="form-group">
        <label>Client Name</label>
        <input type="text" value="{{ $order->clientOrder->user->name ?? $order->quotation->client->name ?? 'N/A' }}" readonly>

    </div>
    <div class="form-group">
        <label>Order Date</label>
        <input type="date" value="{{ $order->created_at?->format('Y-m-d') }}" readonly>
    </div>
    <div class="form-group">
        <label>Due Date</label>
        <input type="date" value="{{ $order->due_date?->format('Y-m-d') ?? '' }}" readonly>
    </div>
    <div class="form-group">
        <label>Status</label>
        <input type="text" value="{{ ucfirst($order->status) }}" readonly>
    </div>

    <!-- Delivery Form -->
    <form action="{{ route('production-orders.deliver.process', $order) }}" method="POST">
        @csrf

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Ordered Qty</th>
                    <th>Delivered Qty</th>
                    <th>Pending Qty</th>
                    <th>Warehouse</th>
                    <th>Batch</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku ?? 'N/A' }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>
                        <input type="number" name="deliver_quantities[{{ $product->name }}]" 
                            max="{{ $product->pivot->quantity }}" min="0" value="0" 
                            oninput="updatePendingQty(this, '{{ $product->name }}')">
                    </td>
                    <td id="pending-{{ $product->name }}">{{ $product->pivot->quantity }}</td>
                    <td>
                        <select name="warehouse[{{ $product->name }}]">
                            <option value="Main">Main</option>
                            <option value="Secondary">Secondary</option>
                        </select>
                    </td>
                    <td><input type="text" name="batch[{{ $product->name }}]" placeholder="Batch/Serial"></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="form-group">
            <label>Delivery Date</label>
            <input type="date" name="delivery_date" value="{{ date('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label>Delivery Person</label>
            <input type="text" name="delivery_person" placeholder="Enter employee name" required>
        </div>
        <div class="form-group">
            <label>Notes / Comments</label>
            <textarea name="notes" rows="3" placeholder="Any additional details..."></textarea>
        </div>

        <div class="action-bar">
            <a href="{{ route('production-orders.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-success">Submit Delivery</button>
        </div>
    </form>
</div>



<script>
function updatePendingQty(input, name) {
    let orderedQty = parseInt(input.max);
    let deliveredQty = parseInt(input.value) || 0;
    let pendingCell = document.getElementById('pending-' + name);
    pendingCell.innerText = orderedQty - deliveredQty;
}
</script>

@endsection
