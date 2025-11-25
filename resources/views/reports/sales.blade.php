@extends('layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ __('Sales Report') }}</h1>

    {{-- Export Button --}}
    <div class="flex justify-end mb-5">
        <a href="{{ route('reports.sales.export') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white shadow rounded-lg hover:bg-blue-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Export Report
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
            <p class="text-sm text-gray-500">Total Sales</p>
            <p class="text-2xl font-bold text-gray-800">{{ $sales->count() }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <p class="text-2xl font-bold text-green-600">
                ₹{{ number_format($sales->sum('amount'), 2) }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
            <p class="text-sm text-gray-500">Average Sale</p>
            <p class="text-2xl font-bold text-blue-600">
                ₹{{ number_format($sales->avg('amount'), 2) }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
            <p class="text-sm text-gray-500">Highest Sale</p>
            <p class="text-2xl font-bold text-purple-600">
                ₹{{ number_format($sales->max('amount'), 2) }}
            </p>
        </div>

    </div>

    {{-- Sales Table --}}
    <div class="bg-white shadow-xl rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">ID</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Customer</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Amount</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Date</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($sales as $sale)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-3 font-medium">{{ $sale->id }}</td>

                        {{-- Customer column (if exists) --}}
                        <td class="px-6 py-3">
                            {{ $sale->customer->name ?? 'Walk-in Customer' }}
                        </td>

                        {{-- Amount --}}
                        <td class="px-6 py-3 font-semibold text-green-700">
                            ₹{{ number_format($sale->amount, 2) }}
                        </td>

                        {{-- Status (optional) --}}
                        <td class="px-6 py-3">
                            @if($sale->status === 'paid')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Paid</span>
                            @elseif($sale->status === 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Pending</span>
                            @else
                                <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-xs font-semibold">N/A</span>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="px-6 py-3 text-gray-700">
                            {{ $sale->created_at->format('d M Y') }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                            No sales found.
                        </td>
                    </tr>
                @endforelse
            </tbody>

            {{-- Footer Totals --}}
            <tfoot class="bg-gray-50 border-t">
                <tr>
                    <td colspan="2" class="px-6 py-3 font-semibold text-gray-800">Total Revenue:</td>
                    <td colspan="3" class="px-6 py-3 font-bold text-green-700">
                        ₹{{ number_format($sales->sum('amount'), 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
@endsection
