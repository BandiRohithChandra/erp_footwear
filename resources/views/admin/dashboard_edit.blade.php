@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

    <!-- ✅ Success Message -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- ❌ Error Messages -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1 class="text-2xl font-bold mb-6">📊 Edit Dashboard</h1>

    <!-- ✅ Back Button -->
    <a href="{{ route('admin.online') }}" 
       class="inline-block mb-6 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        ⬅ Back
    </a>

    <!-- ✅ Dashboard Form -->
    <form action="{{ route('admin.dashboard.store') }}" method="POST" class="bg-white shadow-md rounded p-6 border">
        @csrf

        <!-- Title -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Card Title</label>
            <input type="text" name="title" placeholder="Enter card title" 
                   class="w-full p-2 border rounded focus:ring focus:ring-blue-300">
        </div>

        <!-- Card Type Checkboxes -->
        <div class="mb-4">
            <label class="block font-semibold mb-2">Select Metrics</label>
            <div id="metrics-container" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="count_type[]" value="clients" class="form-checkbox">
                    <span>👥 Clients</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="count_type[]" value="orders_new" class="form-checkbox">
                    <span>🆕 New Orders</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="count_type[]" value="orders_pending" class="form-checkbox">
                    <span>📦 Pending Orders</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="count_type[]" value="orders_completed" class="form-checkbox">
                    <span>✅ Completed Orders</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="count_type[]" value="articles" class="form-checkbox">
                    <span>📰 Articles</span>
                </label>
                <label class="flex items-center space-x-2">
    <input type="checkbox" name="count_type[]" value="total_sales" class="form-checkbox">
    <span>₹ Total Sales</span>
</label>

                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="count_type[]" value="pending_payments" class="form-checkbox">
                    <span>🕒 Pending Payments</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="count_type[]" value="completed_payments" class="form-checkbox">
                    <span>✅ Completed Payments</span>
                </label>
            </div>
        </div>

        <!-- ✅ Custom Metrics -->
        <div class="mb-4">
            <label class="block font-semibold mb-2">Custom Metrics</label>
            <div id="custom-metrics">
                <input type="text" name="custom_metrics[]" placeholder="Enter custom metric" 
                       class="w-full p-2 border rounded mb-2">
            </div>
            <button type="button" id="add-metric" 
                    class="mt-2 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                ➕ Add More
            </button>
        </div>

        <!-- Submit -->
        <button type="submit" 
                class="mt-2 bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700">
            💾 Save Dashboard
        </button>
    </form>

    <!-- ✅ Existing Cards -->
    <h2 class="text-xl font-semibold mt-8 mb-3">📋 Existing Dashboard Cards</h2>
    <div class="bg-white shadow-md rounded border divide-y">
        @forelse($cards as $card)
            <div class="flex justify-between items-center p-3 hover:bg-gray-50">
                <span>
                    <strong>{{ $card->title }}</strong>
                    <small class="text-gray-500">({{ $card->count_type }})</small>
                </span>
                <form action="{{ route('admin.dashboard.delete', $card->id) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this card?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                </form>
            </div>
        @empty
            <div class="p-3 text-gray-500 text-center">No dashboard cards added yet.</div>
        @endforelse
    </div>
</div>

<!-- ✅ JavaScript for Add More -->
<script>
    document.getElementById('add-metric').addEventListener('click', function () {
        const container = document.getElementById('custom-metrics');

        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'custom_metrics[]';
        input.placeholder = 'Enter custom metric';
        input.classList.add('w-full', 'p-2', 'border', 'rounded', 'mb-2');

        container.appendChild(input);
    });
</script>
@endsection
