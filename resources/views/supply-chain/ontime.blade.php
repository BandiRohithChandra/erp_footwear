@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="page-title">On-Time Delivery Performance</h2>

    <!-- KPI CARDS -->
    <div class="kpi-cards">
        <div class="card">
            <h6>Average Lead Time</h6>
            <h3>{{ $avgLeadTime ?? 0 }} days</h3>
        </div>
        <div class="card">
            <h6>On-Time Delivery %</h6>
            <h3 class="text-success">{{ $onTimePercentage }}%</h3>
        </div>
    </div>

    <!-- TWO-COLUMN LAYOUT -->
    <div class="grid-2col">

        <!-- LEFT SIDE — ON TIME -->
        <div>
            <h4 class="section-title">Delivered On Time</h4>
            <div class="table-wrapper">
                <table class="performance-table">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Delivered At</th>
                            <th>Expected Delivery</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($onTimeItems as $item)
                            <tr>
                                <td>{{ $item->po_number }}</td>
                                <td>{{ $item->supplier_name ?? 'N/A' }}</td>
                                <td>{{ $item->updated_at }}</td>
                                <td>{{ $item->expected_delivery }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty">No on-time deliveries yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RIGHT SIDE — DELAYED + OVERDUE -->
        <div>

            <!-- Delayed -->
            <h4 class="section-title">Delayed Deliveries</h4>
            <div class="table-wrapper">
                <table class="performance-table">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Delivered At</th>
                            <th>Expected Delivery</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($delayedItems as $item)
                            <tr>
                                <td>{{ $item->po_number }}</td>
                                <td>{{ $item->supplier_name ?? 'N/A' }}</td>
                                <td>{{ $item->updated_at }}</td>
                                <td>{{ $item->expected_delivery }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty">No delayed deliveries.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Overdue -->
            <h4 class="section-title">Overdue (Pending Orders)</h4>
            <div class="table-wrapper">
                <table class="performance-table">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Order Date</th>
                            <th>Expected Delivery</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overdueItems as $item)
                            <tr>
                                <td>{{ $item->po_number }}</td>
                                <td>{{ $item->supplier_name ?? 'N/A' }}</td>
                                <td>{{ $item->order_date }}</td>
                                <td>{{ $item->expected_delivery }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty">No overdue orders.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- STYLE SECTION -->
<style>
body {
    font-family: "Segoe UI", Arial, sans-serif;
    background: #f5f7fa;
}
.container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 20px;
}

.page-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 25px;
}

/* KPI CARDS */
.kpi-cards {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}
.card {
    flex: 1;
    background: #fff;
    padding: 22px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}
.card h6 {
    font-size: 0.9rem;
    color: #6b7280;
    margin-bottom: 6px;
}
.card h3 {
    font-size: 1.6rem;
    margin: 0;
}
.text-success { color: #16a34a; }

/* GRID LAYOUT */
.grid-2col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

/* Tables */
.section-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #374151;
    margin: 20px 0 10px;
}
.table-wrapper {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow-x: auto;
}
.performance-table {
    width: 100%;
    border-collapse: collapse;
}
.performance-table th {
    padding: 14px;
    text-align: left;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    color: #374151;
    font-weight: 600;
}
.performance-table td {
    padding: 14px;
    border-bottom: 1px solid #f1f1f1;
    font-size: 0.95rem;
}
.performance-table tbody tr:hover {
    background: #f3f4f6;
}
.empty {
    padding: 20px;
    text-align: center;
    color: #9ca3af;
    font-style: italic;
}

/* Responsive */
@media(max-width: 900px) {
    .grid-2col {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
