@extends('layouts.app')

@section('content')
    <div class="min-h-screen p-6">
        <!-- Main Dashboard Card -->
        <div class="max-w-7xl mx-auto bg-white rounded-3xl shadow-2xl p-8 mb-6">

            <!-- Header -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Hello, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-gray-500 mt-1">This is what's happening in your store this month.</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">{{ now()->format('l, M d') }}</span>
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Revenue Card -->
                <a href="{{ route('invoices.index') }}" class="block">
                    <div
                        class="bg-gradient-to-br from-purple-600 to-blue-600 rounded-2xl p-6 text-white relative overflow-hidden hover:shadow-2xl transition-all cursor-pointer transform hover:scale-105">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                <span class="text-sm opacity-90">{{ __('Total Revenue') }}</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <div class="flex items-baseline space-x-2">
                                <h3 class="text-3xl font-bold">₹{{ number_format($totalIncome ?? 0, 0) }}</h3>
                                <span class="text-sm bg-green-400 text-white px-2 py-0.5 rounded-full">+8.2%</span>
                            </div>
                            <p class="text-xs opacity-75 mt-2">{{ __('From invoices') }}</p>
                        </div>
                    </div>
                </a>

                <!-- Total Orders Card -->
                <a href="{{ route('production-orders.index') }}" class="block">
                    <div
                        class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-all cursor-pointer transform hover:scale-105">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-sm text-gray-600">{{ __('Total Orders') }}</span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div class="flex items-baseline space-x-2">
                            <h3 class="text-3xl font-bold text-gray-900">{{ $totalProductionOrders ?? 0 }}</h3>
                            @php
                                $orderChange = $totalCompletedOrders > 0 ? round((($totalCompletedOrders - $totalPendingOrders) / $totalCompletedOrders) * 100, 1) : 0;
                            @endphp
                            <span
                                class="text-xs {{ $orderChange >= 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} px-2 py-0.5 rounded-full">
                                {{ $orderChange >= 0 ? '+' : '' }}{{ $orderChange }}%
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">{{ __('Production orders') }}</p>
                    </div>
                </a>

                <!-- Total Parties Card -->
                <a href="{{ route('clients.index') }}" class="block">
                    <div
                        class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-all cursor-pointer transform hover:scale-105">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-sm text-gray-600">{{ __('Total Parties') }}</span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="flex items-baseline space-x-2">
                            <h3 class="text-3xl font-bold text-gray-900">{{ $totalClients ?? 0 }}</h3>
                            <span
                                class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full">{{ __('Active') }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">{{ __('Registered clients') }}</p>
                    </div>
                </a>

                <!-- Net Profit Card -->
                <a href="{{ route('transactions.index') }}" class="block">
                    <div
                        class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-all cursor-pointer transform hover:scale-105">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-sm text-gray-600">{{ __('Net Profit') }}</span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex items-baseline space-x-2">
                            <h3 class="text-3xl font-bold text-gray-900">
                                ₹{{ number_format(($totalIncome ?? 0) - ($totalExpenses ?? 0), 0) }}</h3>
                            @php
                                $profitMargin = $totalIncome > 0 ? round(((($totalIncome - $totalExpenses) / $totalIncome) * 100), 1) : 0;
                            @endphp
                            <span
                                class="text-xs {{ $profitMargin >= 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} px-2 py-0.5 rounded-full">
                                {{ $profitMargin >= 0 ? '+' : '' }}{{ $profitMargin }}%
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">{{ __('Revenue - Expenses') }}</p>
                    </div>
                </a>
            </div>

            <!-- Payroll & HR Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Payroll Paid -->
                <div
                    class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-all cursor-pointer transform hover:scale-105">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-sm text-gray-600">{{ __('Total Payroll Paid') }}</span>
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex items-baseline space-x-2">
                        <h3 class="text-2xl font-bold text-gray-900">₹{{ number_format($totalPaid ?? 0, 0) }}</h3>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">{{ __('Paid to employees') }}</p>
                </div>

                <!-- Remaining Payroll Due -->
                <div
                    class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-all cursor-pointer transform hover:scale-105">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-sm text-gray-600">{{ __('Remaining Payroll Due') }}</span>
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex items-baseline space-x-2">
                        <h3 class="text-2xl font-bold text-gray-900">₹{{ number_format($totalRemaining ?? 0, 0) }}</h3>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">{{ __('Pending payments') }}</p>
                </div>

                <!-- Total Payroll Liability -->
                <div
                    class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-all cursor-pointer transform hover:scale-105">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-sm text-gray-600">{{ __('Total Payroll Liability') }}</span>
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="flex items-baseline space-x-2">
                        <h3 class="text-2xl font-bold text-gray-900">₹{{ number_format($totalSalary ?? 0, 0) }}</h3>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">{{ __('Total salary obligation') }}</p>
                </div>

                <!-- Total Employees -->
                <a href="{{ route('employees.index') }}" class="block">
                    <div
                        class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-all cursor-pointer transform hover:scale-105">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-sm text-gray-600">{{ __('Total Employees') }}</span>
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="flex items-baseline space-x-2">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $totalEmployees ?? 0 }}</h3>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">{{ __('Active staff members') }}</p>
                    </div>
                </a>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Revenue Chart -->
                <div class="lg:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('Revenue') }}</h3>
                        <span class="text-sm text-gray-500">{{ __('This month vs last') }}</span>
                    </div>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Sales by Category -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('Sales by Category') }}</h3>
                        <span class="text-sm text-gray-500">{{ __('This month vs last') }}</span>
                    </div>
                    <div class="h-64 flex items-center justify-center">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bottom Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center justify-between p-6 bg-gray-50 rounded-2xl">
                    <div>
                        <h4 class="text-4xl font-bold text-gray-900">{{ $totalProductionOrders ?? 0 }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ __('orders') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $totalPendingOrders ?? 0 }} {{ __('orders are pending') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gray-900 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>

                <div class="flex items-center justify-between p-6 bg-gray-50 rounded-2xl">
                    <div>
                        <h4 class="text-4xl font-bold text-gray-900">{{ $totalClients ?? 0 }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ __('parties') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('Registered clients') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-900 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order List Card -->
        <div class="max-w-7xl mx-auto bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Production Orders') }}</h2>

            <!-- Tabs -->
            <div class="flex space-x-2 mb-6">
                <button class="px-6 py-2 bg-blue-500 text-white rounded-lg font-medium">{{ __('All orders') }} <span
                        class="ml-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $totalProductionOrders ?? 0 }}</span></button>
                <button class="px-6 py-2 bg-orange-100 text-orange-700 rounded-lg font-medium">{{ __('Pending') }} <span
                        class="ml-2 bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $totalPendingOrders ?? 0 }}</span></button>
                <button class="px-6 py-2 bg-yellow-100 text-yellow-700 rounded-lg font-medium">{{ __('In Progress') }} <span
                        class="ml-2 bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $activeProcesses ?? 0 }}</span></button>
                <button class="px-6 py-2 bg-green-100 text-green-700 rounded-lg font-medium">{{ __('Completed') }} <span
                        class="ml-2 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $totalCompletedOrders ?? 0 }}</span></button>
            </div>

            <!-- Search and Filters -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="{{ __('Search orders...') }}"
                            class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <a href="{{ route('production-orders.index') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-900 text-sm">{{ __('View all orders') }} →</a>
                </div>
                <a href="{{ route('orders.index') }}"
                    class="px-6 py-2 bg-gray-900 text-white rounded-lg font-medium hover:bg-gray-800 transition-colors">+
                    {{ __('Add order') }}</a>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">{{ __('Order ID') }}</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">{{ __('Party') }}</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">{{ __('Article') }}</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">{{ __('Quantity') }}</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">{{ __('Due Date') }}</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">{{ __('Status') }}</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productionOrders as $order)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4 px-4 text-sm text-gray-900">#{{ $order->id }}</td>
                                <td class="py-4 px-4 text-sm text-gray-900">{{ $order->client->name ?? 'N/A' }}</td>
                                <td class="py-4 px-4 text-sm text-gray-600">{{ $order->product->name ?? 'N/A' }}</td>
                                <td class="py-4 px-4 text-sm text-gray-900">{{ $order->quantity }}</td>
                                <td class="py-4 px-4 text-sm text-gray-600">
                                    {{ $order->due_date ? $order->due_date->format('d.m.Y') : 'N/A' }}
                                </td>
                                <td class="py-4 px-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-orange-100 text-orange-700',
                                            'in_progress' => 'bg-yellow-100 text-yellow-700',
                                            'completed' => 'bg-green-100 text-green-700',
                                            'delayed' => 'bg-red-100 text-red-700',
                                        ];
                                        $statusColor = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="px-3 py-1 text-xs rounded-full {{ $statusColor }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <a href="{{ route('production-orders.show', $order->id) }}"
                                        class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 px-4 text-center text-gray-500">
                                    {{ __('No production orders found.') }} <a href="{{ route('orders.index') }}"
                                        class="text-blue-600 hover:underline">{{ __('View all orders') }}</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Info -->
            <div class="flex justify-between items-center mt-6">
                <span class="text-sm text-gray-600">{{ __('Showing') }} {{ $productionOrders->count() }} {{ __('of') }}
                    {{ $totalProductionOrders ?? 0 }} {{ __('orders') }}</span>
                <a href="{{ route('production-orders.index') }}"
                    class="text-sm text-blue-600 hover:underline">{{ __('View all') }}
                    →</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Revenue Chart - Using real income data
            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx) {
                new Chart(revenueCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($transactionDates->take(8)->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M'))->values()) !!},
                        datasets: [{
                            label: 'Revenue',
                            data: {!! json_encode($incomeData->take(8)->values()) !!},
                            backgroundColor: '#6366F1',
                            borderRadius: 8,
                            barThickness: 40,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                        },
                        scales: {
                            x: { grid: { display: false } },
                            y: {
                                grid: { color: 'rgba(0,0,0,0.05)' },
                                ticks: {
                                    callback: function (value) {
                                        return '₹' + (value / 1000) + 'k';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Category Chart - Using real top products data
            const categoryCtx = document.getElementById('categoryChart');
            if (categoryCtx) {
                new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($topProducts->keys()->take(5)) !!},
                        datasets: [{
                            data: {!! json_encode($topProducts->values()->take(5)) !!},
                            backgroundColor: ['#6366F1', '#F97316', '#FBBF24', '#10B981', '#EC4899'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                        },
                        cutout: '70%',
                    }
                });
            }
        });
    </script>
@endsection