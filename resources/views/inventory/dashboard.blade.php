@extends('layouts.app')

@section('content')
<div x-data="{ filterType: 'all' }" class="container mx-auto p-6">

    {{-- Dashboard Header --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-4 md:mb-0" style="font-size: 21px;">📦 Dashboard</h1>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">

        {{-- Total Materials --}}
        <div @click="filterType = 'material'" class="cursor-pointer bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition">
            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Materials</h2>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalMaterials }}</p>
            <span class="inline-block px-2 py-1 mt-2 text-xs font-semibold rounded-full
                {{ $lowRawMaterials === $totalMaterials ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                {{ $lowRawMaterials === $totalMaterials ? 'Low Stock' : 'In Stock' }}
            </span>
        </div>

        {{-- Total Liquid Materials (commented out) --}}
        {{--
        <div @click="filterType = 'liquid'" class="cursor-pointer bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition">
            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Liquid Materials</h2>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalLiquids }}</p>
            <span class="inline-block px-2 py-1 mt-2 text-xs font-semibold rounded-full
                {{ $lowLiquidMaterials === $totalLiquids ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                {{ $lowLiquidMaterials === $totalLiquids ? 'Low Stock' : 'In Stock' }}
            </span>
        </div>
        --}}

        {{-- Total Soles --}}
        <div @click="filterType = 'sole'" class="cursor-pointer bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500 hover:shadow-xl transition">
            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Soles</h2>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalSoles }}</p>
            <span class="inline-block px-2 py-1 mt-2 text-xs font-semibold rounded-full
                {{ $lowSoles === $totalSoles ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                {{ $lowSoles === $totalSoles ? 'Low Stock' : 'In Stock' }}
            </span>
        </div>

        {{-- Low Stock Items --}}
        <div @click="filterType = 'low'" class="cursor-pointer bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition">
            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Low Stock Items</h2>
            <p class="text-3xl font-bold text-gray-800 mt-2">
                {{ $lowRawMaterials + $lowSoles }}
            </p>
            <span class="inline-block px-2 py-1 mt-2 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                View Low Stock
            </span>
        </div>

    </div>

    {{-- Materials Table --}}
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">

                {{-- Raw Materials --}}
                @foreach($rawMaterials as $material)
                <tr 
                    x-show="filterType === 'all' || filterType === 'material' || (filterType === 'low' && {{ $material->quantity }} < {{ $lowStockThreshold }})" 
                    class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">Material</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $material->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $material->quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $material->quantity < $lowStockThreshold ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ $material->quantity < $lowStockThreshold ? 'Low Stock' : 'In Stock' }}
                        </span>
                    </td>
                </tr>
                @endforeach

                {{-- Liquid Materials (commented out) --}}
                {{--
                @foreach($liquidMaterials as $liquid)
                <tr 
                    x-show="filterType === 'all' || filterType === 'liquid' || (filterType === 'low' && {{ $liquid->quantity }} < {{ $lowStockThreshold }})" 
                    class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">Liquid Material</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $liquid->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $liquid->quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $liquid->quantity < $lowStockThreshold ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ $liquid->quantity < $lowStockThreshold ? 'Low Stock' : 'In Stock' }}
                        </span>
                    </td>
                </tr>
                @endforeach
                --}}

                {{-- Soles --}}
                @foreach($soles as $sole)
                <tr 
                    x-show="filterType === 'all' || filterType === 'sole' || (filterType === 'low' && {{ $sole->quantity }} < {{ $lowStockThreshold }})" 
                    class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">Sole</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $sole->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $sole->quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $sole->quantity < $lowStockThreshold ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ $sole->quantity < $lowStockThreshold ? 'Low Stock' : 'In Stock' }}
                        </span>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    {{-- Stock Chart --}}
    <div class="bg-white rounded-lg shadow-lg p-6 mt-10">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Stock Distribution</h2>
        <canvas id="stockChart" class="w-full h-64"></canvas>
    </div>

</div>

{{-- Alpine.js --}}
<script src="//unpkg.com/alpinejs" defer></script>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('stockChart').getContext('2d');

    // Totals
    const totalMaterials = {{ $totalMaterials }};
    // const totalLiquids = {{ $totalLiquids }}; // commented out
    const totalSoles = {{ $totalSoles }};

    // Low stock counts
    const lowMaterials = {{ $lowRawMaterials }};
    // const lowLiquids = {{ $lowLiquidMaterials }}; // commented out
    const lowSoles = {{ $lowSoles }};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Materials', /* 'Liquid Materials', */ 'Soles'],
            datasets: [
                {
                    label: 'In Stock',
                    data: [
                        totalMaterials - lowMaterials,
                        // totalLiquids - lowLiquids,
                        totalSoles - lowSoles
                    ],
                    backgroundColor: '#10B981', // green
                    borderRadius: 10
                },
                {
                    label: 'Low Stock',
                    data: [
                        lowMaterials,
                        // lowLiquids,
                        lowSoles
                    ],
                    backgroundColor: '#EF4444', // red
                    borderRadius: 10
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    backgroundColor: '#1F2937',
                    titleColor: '#F9FAFB',
                    bodyColor: '#F9FAFB',
                    padding: 10,
                    cornerRadius: 8,
                }
            },
            scales: {
                y: { beginAtZero: true, stacked: true },
                x: { stacked: true }
            }
        }
    });
</script>
@endsection
