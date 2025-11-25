@extends('layouts.app')

@section('content')
<!-- Tailwind + Chart.js dashboard -->
<div class="container mx-auto p-6 font-sans space-y-8">

    <h2 class="text-3xl font-bold text-gray-800 mb-6">Supply Chain Dashboard</h2>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <div class="bg-white shadow rounded-xl p-6 border border-gray-200">
            <p class="text-gray-500 font-semibold">Total Orders</p>
            <h3 class="text-2xl font-bold">{{ $totalOrders }}</h3>
        </div>
        <div class="bg-white shadow rounded-xl p-6 border border-gray-200">
            <p class="text-gray-500 font-semibold">Active Orders</p>
            <h3 class="text-2xl font-bold">{{ $activeOrders }}</h3>
        </div>
        <div class="bg-white shadow rounded-xl p-6 border border-gray-200">
            <p class="text-gray-500 font-semibold">Pending Orders</p>
            <h3 class="text-2xl font-bold">{{ $pendingOrders }}</h3>
        </div>
        <div class="bg-white shadow rounded-xl p-6 border border-gray-200">
            <p class="text-gray-500 font-semibold">Total Suppliers</p>
            <h3 class="text-2xl font-bold">{{ \App\Models\Supplier::count() }}</h3>
        </div>
        <div class="bg-white shadow rounded-xl p-6 border border-gray-200">
            <p class="text-gray-500 font-semibold">Average Lead Time (Days)</p>
            <h3 class="text-2xl font-bold">{{ round($averageLeadTime, 1) }}</h3>
        </div>
    </div>

    <!-- Graphs Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Orders by Status -->
        <div class="bg-white shadow rounded-xl p-6 border border-gray-200">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">Orders by Status</h3>
            <canvas id="ordersStatusChart" height="250"></canvas>
        </div>

        <!-- Supplier Contribution -->
        <div class="bg-white shadow rounded-xl p-6 border border-gray-200">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">Supplier Contribution</h3>
            <canvas id="supplierContributionChart" height="250"></canvas>
        </div>

        <!-- Monthly Orders -->
        <div class="bg-white shadow rounded-xl p-6 border border-gray-200 md:col-span-2">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">Monthly Orders Trend</h3>
            <canvas id="monthlyOrdersChart" height="300"></canvas>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white shadow rounded-xl p-6 border border-gray-200">
        <h3 class="text-xl font-semibold mb-4 text-gray-700">Recent Orders</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-2 text-left">Order ID</th>
                        <th class="px-4 py-2 text-left">Customer</th>
                        <th class="px-4 py-2 text-left">Quotation</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Due Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(App\Models\ProductionOrder::latest()->take(10)->get() as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2">{{ 'PO-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-2">{{ $order->quotation->client->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $order->quotation->quotation_number ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            @php
                                $statusClass = 'bg-gray-400';
                                if($order->status=='pending') $statusClass='bg-yellow-400';
                                elseif($order->status=='processing') $statusClass='bg-blue-400';
                                elseif($order->status=='delivered') $statusClass='bg-green-400';
                            @endphp
                            <span class="px-2 py-1 rounded text-white text-xs {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="px-4 py-2">{{ $order->due_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Suppliers Table -->
    <div class="bg-white shadow rounded-xl p-6 border border-gray-200">
        <h3 class="text-xl font-semibold mb-4 text-gray-700">Recent Suppliers</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-2">Supplier Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Phone</th>
                        <th class="px-4 py-2">Materials Provided</th>
                        <th class="px-4 py-2">GST Number</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(App\Models\Supplier::latest()->take(5)->get() as $supplier)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2">{{ $supplier->name }}</td>
                        <td class="px-4 py-2">{{ $supplier->email ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $supplier->phone ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $supplier->material_types ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $supplier->gst_number ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('suppliers.index') }}" class="text-blue-600 hover:underline text-sm mt-2 inline-block">Manage All Suppliers</a>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Orders by Status Pie Chart
    const ctxStatus = document.getElementById('ordersStatusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Processing', 'Delivered'],
            datasets: [{
                data: [{{ $pendingOrders }}, {{ $processingOrders }}, {{ $deliveredOrders }}],
                backgroundColor: ['#f0ad4e','#007bff','#28a745'],
            }]
        },
        options: { responsive:true, plugins:{ legend:{ position:'bottom' } } }
    });

    // Supplier Contribution Bar Chart
    const ctxSupplier = document.getElementById('supplierContributionChart').getContext('2d');
    new Chart(ctxSupplier, {
        type: 'bar',
        data: {
            labels: [
                @foreach(App\Models\Supplier::all() as $supplier)
                    "{{ $supplier->name }}",
                @endforeach
            ],
            datasets: [{
                label: 'Number of Orders',
                data: [
                    @foreach(App\Models\Supplier::all() as $supplier)
                        {{ $supplier->supplierOrders->count() }},
                    @endforeach
                ],
                backgroundColor: '#1f4e79'
            }]
        },
        options: { responsive:true, plugins:{ legend:{ display:false } } }
    });

    // Monthly Orders Line Chart
    const ctxMonthly = document.getElementById('monthlyOrdersChart').getContext('2d');
    new Chart(ctxMonthly, {
        type: 'line',
        data: {
            labels: [
                @foreach($monthlyOrders as $month => $count)
                    "{{ $month }}",
                @endforeach
            ],
            datasets: [{
                label: 'Orders',
                data: [
                    @foreach($monthlyOrders as $month => $count)
                        {{ $count }},
                    @endforeach
                ],
                backgroundColor: 'rgba(31,78,121,0.2)',
                borderColor: '#1f4e79',
                borderWidth: 2,
                fill:true,
                tension:0.3
            }]
        },
        options: { responsive:true, plugins:{ legend:{ display:false } } }
    });
</script>
@endsection
