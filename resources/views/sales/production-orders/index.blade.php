@extends('layouts.app')

@section('content')
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f8;
}

/* Layout */
.container {
    max-width: 100%;
    width: 100%;
    margin: 0 auto;
    padding: 20px;
    overflow-x: hidden;
}

/* Header */
h2 {
    color: #1f4e79;
    margin-bottom: 25px;
    font-size: 26px;
    text-align: center;
    font-weight: 700;
}

/* Alerts */
.alert { padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
.alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

/* ✅ Auto-fit Table without scrolling */
.table-responsive {
    width: 100%;
    overflow-x: hidden;
}
table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    font-size: 13px;
    table-layout: auto; /* let columns size to their content */
    word-wrap: break-word;
}
/* Column helpers to avoid mixing long 'Articles' column with 'Total Qty' */
.col-articles { width: 40%; }
.col-total { width: 8%; text-align: center; }
thead {
    background-color: #1f4e79;
    color: #fff;
    font-weight: 600;
}
thead th {
    padding: 10px 6px;
    text-align: left;
    white-space: normal; /* wrap header text */
}
tbody td {
    padding: 8px 6px;
    vertical-align: middle;
    white-space: normal; /* wrap long text */
    word-break: break-word;
}
tbody tr { border-bottom: 1px solid #e0e0e0; transition: background-color 0.2s; }
tbody tr:hover { background-color: #eef3f8; }

/* Badges */
.badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    color: #fff;
    margin: 2px;
    white-space: nowrap;
}
.badge-warning { background-color: #f0ad4e; }
.badge-primary { background-color: #007bff; }
.badge-success { background-color: #28a745; }
.badge-secondary { background-color: #6c757d; }
.badge-lightgreen { background-color: #90ee90; }
.badge-red { background-color: #dc3545; }
.badge-sky { background-color: #0dcaf0; }
.badge-yellow { background-color: #ffc107; }

/* Buttons */
.btn {
    padding: 6px 10px;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s;
    color: #fff;
    font-weight: 500;
}
.btn-primary { background-color: #007bff; }
.btn-primary:hover { background-color: #0056b3; }

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background-color: #f4f6f8;
    color: #1f4e79;
    font-weight: 500;
    font-size: 13px;
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

/* Filter form */
.filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    padding: 15px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    margin-bottom: 25px;
}
.filter-form select { min-width: 150px; }

/* Group header */
.group-header {
    background-color: #e9f3ff;
    color: #1f4e79;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.group-header:hover { background-color: #dceeff; }
.group-header td { padding: 10px; }
.group-header .toggle-icon {
    float: right;
    font-size: 16px;
    transition: transform 0.2s;
}
.group-header.collapsed .toggle-icon {
    transform: rotate(-90deg);
}

/* ✅ Responsive tweaks for small screens */
@media (max-width: 992px) {
    table { font-size: 12px; }
    thead th, tbody td { padding: 6px 4px; }
}
@media (max-width: 768px) {
    table, tbody, tr, td { display: block; width: 100%; }
    thead { display: none; }
    tbody tr { margin-bottom: 12px; border-bottom: 2px solid #e0e0e0; }
    td {
        text-align: right;
        padding-left: 50%;
        position: relative;
    }
    td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        width: calc(50% - 12px);
        text-align: left;
        font-weight: 600;
        color: #1f4e79;
    }
}
</style>

<div class="container">
    @if(auth()->user()->hasRole('Admin'))
        <a href="{{ route('admin.online') }}" class="btn btn-back">&#8592; Back</a>
    @else
        <a href="{{ url()->previous() }}" class="btn btn-back">&#8592; Back</a>
    @endif

    <h2>Orders</h2>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('production-orders.index') }}" class="filter-form">
        <div class="flex flex-col">
            <label for="status" class="font-semibold">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="">All</option>
                @foreach(['pending','processing','accepted','rejected','shipping','delivered'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col">
            <label for="client" class="font-semibold">Party</label>
            <select name="client" id="client" class="form-control">
                <option value="">All</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    {{-- Bulk Accept --}}
    @if(auth()->user()->hasRole('Admin'))
        <form id="bulkAcceptForm" method="POST" action="{{ route('production-orders.bulk-accept') }}" class="mb-3">
            @csrf
            <input type="hidden" name="selected_orders" id="selectedOrdersInput">
            <button type="submit" class="btn btn-primary">Accept Selected Orders</button>
        </form>
    @endif

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($groupedOrders->isEmpty())
        <p>No production orders found.</p>
    @else
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Order ID</th>
                        <th>Type</th>
                        <th>Ordered By</th>
                        <th>Customer</th>
                        <th class="col-articles">Articles</th>
                        <th class="col-total">Total Qty</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Due Date</th>
                        <th>Transport Name</th>
                        <th>Transport Address</th>
                        <th>Transport ID</th>
                        <th>Transport Phone</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
@foreach($groupedOrders as $articleName => $orders)
    {{-- Group Header --}}
    <tr class="group-header" data-article="{{ Str::slug($articleName) }}">
        <td colspan="15">
            🧩 <strong>Article:</strong> {{ $articleName }}
            <span class="toggle-icon">▼</span>
        </td>
    </tr>

    {{-- Group Orders --}}
    @foreach($orders as $wrapper)
        @php
            $order = $wrapper->data;
            $type = $wrapper->type;
            $products = $order->quotation?->products ?? collect();
            $orderedBy = $order->admin_name ?? 'N/A';
            $customerName = $order->quotation?->client?->name ?? 'N/A';
            $totalQuantity = $products->sum(fn($p) => $p->pivot->quantity ?? 0);
            $statusClass = match($order->status) {
                'pending' => 'badge-warning',
                'processing' => 'badge-yellow',
                'accepted' => 'badge-lightgreen',
                'rejected' => 'badge-red',
                'shipping' => 'badge-sky',
                'delivered' => 'badge-success',
                default => 'badge-secondary',
            };
        @endphp

        <tr class="order-row" data-article="{{ Str::slug($articleName) }}" style="display:none;">
            <td data-label="Select"><input type="checkbox" class="order-checkbox" value="{{ $order->id }}"></td>
            <td data-label="Order ID">{{ $order->id }}</td>
            <td data-label="Type">{{ ucfirst($type) }}</td>
            <td data-label="Ordered By">{{ $orderedBy }}</td>
            <td data-label="Customer">{{ $customerName }}</td>
            <td data-label="Articles" class="col-articles">
                @forelse($products as $product)
                    <span class="badge badge-secondary">
                        {{ $product->name }} ({{ $product->pivot->quantity ?? 0 }})
                    </span>
                @empty
                    <span class="badge badge-secondary">No Products</span>
                @endforelse
            </td>
            <td data-label="Total Qty" class="col-total">{{ $totalQuantity }}</td>
            <td data-label="Order Status"><span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span></td>
            <td data-label="Payment Status">{{ ucfirst($order->payment_status ?? 'N/A') }}</td>
            <td data-label="Due Date">{{ $order->due_date?->format('d-m-Y') ?? 'N/A' }}</td>
            <td data-label="Transport Name">{{ $order->transport_name ?? '-' }}</td>
            <td data-label="Transport Address">{{ $order->transport_address ?? '-' }}</td>
            <td data-label="Transport ID">{{ $order->transport_id ?? '-' }}</td>
            <td data-label="Transport Phone">{{ $order->transport_phone ?? '-' }}</td>
            <!-- <td data-label="Actions"><a href="{{ route('production-orders.show', $order) }}" class="btn btn-primary btn-sm">View</a></td> -->
        </tr>
    @endforeach
@endforeach
</tbody>

            </table>
        </div>
    @endif
</div>

<script>
// ✅ Select All functionality
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.order-checkbox').forEach(cb => cb.checked = this.checked);
});

// ✅ Bulk accept submit
document.getElementById('bulkAcceptForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const selected = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        alert('Please select at least one order to accept.');
        return;
    }
    document.getElementById('selectedOrdersInput').value = selected.join(',');
    this.submit();
});

// ✅ Independent Group Toggle (each group toggles separately)
document.querySelectorAll('.group-header').forEach(header => {
    header.addEventListener('click', () => {
        const articleKey = header.getAttribute('data-article');
        const isCollapsed = header.classList.toggle('collapsed');

        // toggle only rows belonging to this article
        document.querySelectorAll(`tr[data-article="${articleKey}"].order-row`).forEach(row => {
            row.style.display = isCollapsed ? 'none' : 'table-row';
        });

        // rotate the arrow
        const icon = header.querySelector('.toggle-icon');
        if (icon) icon.style.transform = isCollapsed ? 'rotate(-90deg)' : 'rotate(0deg)';
    });
});
</script>

@endsection
