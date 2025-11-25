@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
        @if(auth()->user()->hasRole('Sales Manager') || auth()->user()->hasRole('Sales Employee'))
            <div class="button-container">
    <a href="{{ route('clients.create') }}" class="create-client-btn">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3H6a1 1 0 100 2h3v3a1 1 0 102 0v-3h3a1 1 0 100-2h-3V7z" clip-rule="evenodd" />
        </svg>
        {{ __('Create Client') }}
    </a>
</div>

        @endif

        <style>
            /* Container aligns button to the right */
.button-container {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 16px; /* similar to mb-4 */
}

/* Button styling */
.create-client-btn {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background-color: #6b21a8; /* purple-700 equivalent */
    color: #ffffff;
    font-size: 14px;
    font-weight: 500;
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    transition: background-color 0.2s ease, transform 0.2s ease;
}

/* Hover effect */
.create-client-btn:hover {
    background-color: #581c87; /* purple-800 equivalent */
    transform: translateY(-1px);
}

/* Icon inside button */
.create-client-btn .icon {
    width: 20px;
    height: 20px;
    margin-right: 8px;
}

        </style>

        <h1 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-900">{{ __('Sales Dashboard') }}</h1>

        <!-- Chart Type Toggle -->
        <div class="flex justify-end mb-6">
            <label for="chartType" class="mr-2 text-gray-700 font-medium self-center">{{ __('Chart Type') }}:</label>
            <select id="chartType" class="border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
                <option value="line">{{ __('Line Chart') }}</option>
                <option value="bar">{{ __('Bar Chart') }}</option>
            </select>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <a href="{{ route('admin.sales.total') }}" class="block">
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow flex items-center space-x-4 hover:bg-gray-50 transition">
        <div class="p-3 bg-blue-100 rounded-full">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v2h6v-2c0-1.657-1.343-3-3-3zm0-4a7 7 0 00-7 7v2h14v-2a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-base sm:text-lg font-medium text-gray-700">{{ __('Total Sales') }}</h3>
            <p class="text-xl sm:text-2xl font-bold text-gray-900">₹{{ number_format($personalTotalSales, 2) }}</p>
        </div>
    </div>
</a>


            <!-- Pending Payments Card -->
            <a href="{{ route('admin.orders.pending_payments') }}" class="block">
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow flex items-center space-x-4 hover:bg-gray-50 transition">
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-medium text-gray-700">{{ __('Pending Payments') }}</h3>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">₹{{ number_format($personalPendingPayments, 2) }}</p>
                    </div>
                </div>
            </a>

            <!-- Total Orders Card -->
            <a href="{{ route('production-orders.index') }}" class="block">
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow flex items-center space-x-4 hover:bg-gray-50 transition">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m-3-9V5m-6 6h12"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-medium text-gray-700">{{ __('Total Orders') }}</h3>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $personalOrdersCount }}</p>
                    </div>
                </div>
            </a>

            <!-- Total Clients Card -->
            <a href="{{ route('clients.index') }}" class="block">
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow flex items-center space-x-4 hover:bg-gray-50 transition">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-medium text-gray-700">{{ __('Total Clients') }}</h3>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $personalClientsCount }}</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <!-- Invoice Overview Chart -->
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow">
                <h3 class="text-base sm:text-lg font-semibold mb-4 text-gray-900">{{ __('Invoice Overview') }}</h3>
                <div class="chart-container h-64 sm:h-72 w-full">
                    <canvas id="invoiceOverviewChart"></canvas>
                </div>
            </div>

            <!-- Total Sales Chart -->
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow">
                <h3 class="text-base sm:text-lg font-semibold mb-4 text-gray-900">{{ __('Total Sales') }}</h3>
                <div class="chart-container h-64 sm:h-72 w-full">
                    <canvas id="totalSalesChart"></canvas>
                </div>
            </div>

            <!-- Total Orders Chart -->
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow">
                <h3 class="text-base sm:text-lg font-semibold mb-4 text-gray-900">{{ __('Total Orders') }}</h3>
                <div class="chart-container h-64 sm:h-72 w-full">
                    <canvas id="totalOrdersChart"></canvas>
                </div>
            </div>

            <!-- Total Clients Chart -->
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow">
                <h3 class="text-base sm:text-lg font-semibold mb-4 text-gray-900">{{ __('Total Clients') }}</h3>
                <div class="chart-container h-64 sm:h-72 w-full">
                    <canvas id="totalClientsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Invoice Overview Card -->
        <div class="mb-8">
            <a href="{{ route('invoices.index') }}" class="block">
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow hover:bg-gray-50 transition">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-medium text-gray-700">{{ __('Invoice Overview') }}</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 text-sm">
                        <div>
                            <p class="flex items-center space-x-2">
                                <span class="font-semibold text-red-600">{{ __('Overdue') }}:</span>
                                <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $personalInvoiceOverviewData['overdue'] ? ($personalInvoiceOverviewData['overdue'] / max(array_values($personalInvoiceOverviewData)) * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm">₹{{ number_format($personalInvoiceOverviewData['overdue'], 2) }}</span>
                            </p>
                        </div>
                        <div>
                            <p class="flex items-center space-x-2">
                                <span class="font-semibold text-orange-600">{{ __('Not Paid') }}:</span>
                                <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-orange-600 h-2.5 rounded-full" style="width: {{ $personalInvoiceOverviewData['not_paid'] ? ($personalInvoiceOverviewData['not_paid'] / max(array_values($personalInvoiceOverviewData)) * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm">₹{{ number_format($personalInvoiceOverviewData['not_paid'], 2) }}</span>
                            </p>
                        </div>
                        <div>
                            <p class="flex items-center space-x-2">
                                <span class="font-semibold text-yellow-600">{{ __('Partially Paid') }}:</span>
                                <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-yellow-600 h-2.5 rounded-full" style="width: {{ $personalInvoiceOverviewData['partially_paid'] ? ($personalInvoiceOverviewData['partially_paid'] / max(array_values($personalInvoiceOverviewData)) * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm">₹{{ number_format($personalInvoiceOverviewData['partially_paid'], 2) }}</span>
                            </p>
                        </div>
                        <div>
                            <p class="flex items-center space-x-2">
                                <span class="font-semibold text-green-600">{{ __('Fully Paid') }}:</span>
                                <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $personalInvoiceOverviewData['fully_paid'] ? ($personalInvoiceOverviewData['fully_paid'] / max(array_values($personalInvoiceOverviewData)) * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm">₹{{ number_format($personalInvoiceOverviewData['fully_paid'], 2) }}</span>
                            </p>
                        </div>
                        <div>
                            <p class="flex items-center space-x-2">
                                <span class="font-semibold text-gray-600">{{ __('Draft') }}:</span>
                                <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-gray-600 h-2.5 rounded-full" style="width: {{ $personalInvoiceOverviewData['draft'] ? ($personalInvoiceOverviewData['draft'] / max(array_values($personalInvoiceOverviewData)) * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm">₹{{ number_format($personalInvoiceOverviewData['draft'], 2) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    

  <script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded.');
        return;
    }

    // Prepare PHP variables with defaults
    @php
        $months = $months ?? ['Mar','Apr','May','Jun','Jul','Aug'];
        $invoiceOverviewLabels = $invoiceOverviewLabels ?? ['Overdue','Not Paid','Partially Paid','Fully Paid','Draft'];
        $invoiceOverviewData = $invoiceOverviewData ?? [0,0,0,0,0];
        $totalSalesData = $totalSalesData ?? [0,0,0,0,0,0];
        $totalOrdersData = $totalOrdersData ?? [0,0,0,0,0,0];
        $totalClientsData = $totalClientsData ?? [0,0,0,0,0,0];
    @endphp

    // Convert PHP arrays to JS
    const months = @json($months);
    const invoiceOverviewLabels = @json($invoiceOverviewLabels);
    const invoiceOverviewData = @json($invoiceOverviewData);
    const totalSalesData = @json($totalSalesData);
    const totalOrdersData = @json($totalOrdersData);
    const totalClientsData = @json($totalClientsData);

    console.log('Chart Data:', {
        invoiceOverview: { labels: invoiceOverviewLabels, data: invoiceOverviewData },
        totalSales: { labels: months, data: totalSalesData },
        totalOrders: { labels: months, data: totalOrdersData },
        totalClients: { labels: months, data: totalClientsData }
    });

    let invoiceOverviewChart, totalSalesChart, totalOrdersChart, totalClientsChart;

    const createChartConfig = (type, labels, data, label, backgroundColor, borderColor, yAxisTitle) => ({
        type: type,
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data.length === labels.length ? data : Array(labels.length).fill(0),
                backgroundColor: type === 'line' ? backgroundColor.replace('0.5','0.2') : backgroundColor,
                borderColor: borderColor,
                borderWidth: 2,
                fill: type === 'line',
                tension: type === 'line' ? 0.4 : 0,
                pointRadius: window.innerWidth < 640 ? 3 : 5,
                pointHoverRadius: window.innerWidth < 640 ? 5 : 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true, 
                    title: { display: true, text: yAxisTitle, font: { size: window.innerWidth < 640 ? 12 : 14 } },
                    ticks: { font: { size: window.innerWidth < 640 ? 10 : 12 } }
                },
                x: { 
                    title: { display: labels.length > 1 ? '{{ __("Month") }}' : '', font: { size: window.innerWidth < 640 ? 12 : 14 } },
                    ticks: { font: { size: window.innerWidth < 640 ? 10 : 12 } }
                }
            },
            plugins: { 
                legend: { 
                    position: 'top', 
                    labels: { font: { size: window.innerWidth < 640 ? 10 : 12 } }
                }
            }
        }
    });

    const initializeCharts = (chartType) => {
        // Destroy existing charts to prevent duplicates
        [invoiceOverviewChart, totalSalesChart, totalOrdersChart, totalClientsChart].forEach(chart => {
            if (chart) chart.destroy();
        });

        try {
            // Invoice Overview Chart
            invoiceOverviewChart = new Chart(
                document.getElementById('invoiceOverviewChart').getContext('2d'),
                createChartConfig(
                    chartType,
                    invoiceOverviewLabels,
                    invoiceOverviewData,
                    '{{ __("Amount (₹)") }}',
                    chartType === 'line' ? 'rgba(255, 99, 132, 0.2)' : [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                        'rgba(255, 205, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ],
                    chartType === 'line' ? '#FF6384' : ['#FF6384', '#FF9F40', '#FFCD56', '#4BC0C0', '#9966FF'],
                    '{{ __("Amount (₹)") }}'
                )
            );

            // Total Sales Chart
            totalSalesChart = new Chart(
                document.getElementById('totalSalesChart').getContext('2d'),
                createChartConfig(chartType, months, totalSalesData, '{{ __("Amount (₹)") }}', 'rgba(54, 162, 235, 0.5)', '#36A2EB', '{{ __("Amount (₹)") }}')
            );

            // Total Orders Chart
            totalOrdersChart = new Chart(
                document.getElementById('totalOrdersChart').getContext('2d'),
                createChartConfig(chartType, months, totalOrdersData, '{{ __("Count") }}', 'rgba(75, 192, 192, 0.5)', '#4BC0C0', '{{ __("Count") }}')
            );

            // Total Clients Chart
            totalClientsChart = new Chart(
                document.getElementById('totalClientsChart').getContext('2d'),
                createChartConfig(chartType, months, totalClientsData, '{{ __("Count") }}', 'rgba(153, 102, 255, 0.5)', '#9966FF', '{{ __("Count") }}')
            );

        } catch (error) {
            console.error('Error initializing charts:', error);
        }
    };

    // Initialize all charts as 'line' by default
    initializeCharts('line');

    // Change chart type dynamically
    document.getElementById('chartType').addEventListener('change', function() {
        console.log('Chart type changed to:', this.value);
        initializeCharts(this.value);
    });

    // Re-initialize charts on window resize
    window.addEventListener('resize', () => {
        initializeCharts(document.getElementById('chartType').value);
    });
});
</script>







@endsection