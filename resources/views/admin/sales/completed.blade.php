@extends('layouts.app')

@section('content')
<!-- Include Alpine.js for modal -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="container mx-auto px-4 py-8" x-data="{ openModal: false, selectedOrder: null }">

    <!-- Header with Back Button -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Completed Orders</h1>
        <a href="{{ route('admin.sales.total') }}" 
           class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block bg-white shadow-lg rounded-xl p-6 overflow-x-auto">
        <table class="min-w-full table-auto text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                <tr>
                    <th class="px-4 py-2">Client Name</th>
                    <th class="px-4 py-2">Sales Person</th>
                    <th class="px-4 py-2">Total Amount</th>
                    <th class="px-4 py-2">Paid Amount</th>
                    <th class="px-4 py-2">Order Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($completedOrders as $order)
                    <tr class="border-b hover:bg-gray-50 cursor-pointer"
                        @click="selectedOrder = {{ json_encode($order) }}; openModal = true">
                        <td class="px-4 py-2 font-medium text-gray-700">{{ $order->customer_name }}</td>
                        <td class="px-4 py-2">{{ $order->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 font-bold text-gray-800">₹{{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-2 text-green-500 font-bold">₹{{ number_format($order->paid_amount, 2) }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">No completed orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden space-y-4">
        @forelse($completedOrders as $order)
            <div class="bg-white shadow-lg rounded-xl p-4 cursor-pointer"
                 @click="selectedOrder = JSON.parse('{{ addslashes(json_encode($order)) }}'); openModal = true">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold text-gray-800">{{ $order->customer_name }}</span>
                    <span class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</span>
                </div>
                <div><span class="text-gray-600">Sales Person: </span>{{ $order->user->name ?? 'N/A' }}</div>
                <div><span class="text-gray-600">Total Amount: </span><span class="font-bold text-gray-800">₹{{ number_format($order->total, 2) }}</span></div>
                <div><span class="text-gray-600">Paid Amount: </span><span class="text-green-500 font-bold">₹{{ number_format($order->paid_amount, 2) }}</span></div>
                <div><span class="text-gray-600">Due Amount: </span><span class="text-red-500 font-bold">₹{{ number_format($order->total - $order->paid_amount, 2) }}</span></div>
            </div>
        @empty
            <div class="text-center text-gray-500">No completed orders found.</div>
        @endforelse
    </div>

    <!-- Modal for Order Details -->
    <div x-show="openModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[85vh] overflow-y-auto p-6 relative">
            
            <!-- Header -->
            <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Order Details</h2>
                <button @click="openModal = false" class="text-gray-400 hover:text-gray-800 text-3xl font-bold">&times;</button>
            </div>
            
            <!-- Client & Sales Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-gray-500 font-semibold text-sm">Client Name</p>
                    <p class="text-gray-800 text-lg font-medium" x-text="selectedOrder.customer_name ?? 'N/A'"></p>
                </div>
                <div>
                    <p class="text-gray-500 font-semibold text-sm">Sales Person</p>
                    <p class="text-gray-800 text-lg font-medium" x-text="selectedOrder.user?.name ?? 'N/A'"></p>
                </div>
                <div>
                    <p class="text-gray-500 font-semibold text-sm">Order Date</p>
                    <p class="text-gray-800 text-lg font-medium" x-text="new Date(selectedOrder.created_at).toLocaleDateString()"></p>
                </div>
                <div>
                    <p class="text-gray-500 font-semibold text-sm">Status</p>
                    <p class="text-gray-800 text-lg font-medium capitalize" x-text="selectedOrder.status"></p>
                </div>
            </div>
            
            <hr class="border-gray-200 my-4">

            <!-- Amounts Table -->
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full text-left divide-y divide-gray-200">
                    <tbody class="text-gray-700">
                        <tr class="border-b">
                            <td class="py-3 font-semibold text-gray-600">Total Amount</td>
                            <td class="py-3 text-gray-800 font-medium">
                                ₹<span x-text="parseFloat(selectedOrder.total ?? 0).toFixed(2)"></span>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 font-semibold text-gray-600">Paid Amount</td>
                            <td class="py-3 text-green-600 font-semibold">
                                ₹<span x-text="parseFloat(selectedOrder.paid_amount ?? 0).toFixed(2)"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 font-semibold text-gray-600">Due Amount</td>
                            <td class="py-3 text-red-600 font-semibold">
                                ₹<span x-text="(parseFloat(selectedOrder.total ?? 0) - parseFloat(selectedOrder.paid_amount ?? 0)).toFixed(2)"></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="flex justify-end">
                <button @click="openModal = false" class="px-5 py-2 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition font-medium">
                    Close
                </button>
            </div>

        </div>
    </div>

</div>
@endsection
