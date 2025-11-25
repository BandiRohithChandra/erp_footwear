@extends('layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ __('Finance Report') }}</h1>

    {{-- Export Button --}}
    <div class="flex justify-end mb-5">
        <a href="{{ route('reports.finance.export') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white shadow rounded-lg hover:bg-blue-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Export Report
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
            <p class="text-sm text-gray-500">Total Transactions</p>
            <p class="text-2xl font-bold text-gray-800">{{ $transactions->count() }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
            <p class="text-sm text-gray-500">Total Income</p>
            <p class="text-2xl font-bold text-green-600">
                ₹{{ number_format($transactions->where('type', 'income')->sum('amount'), 2) }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
            <p class="text-sm text-gray-500">Total Expenses</p>
            <p class="text-2xl font-bold text-red-600">
                ₹{{ number_format($transactions->where('type', 'expense')->sum('amount'), 2) }}
            </p>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="bg-white shadow-xl rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">ID</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Type</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Amount</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Description</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Date</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 font-medium">{{ $transaction->id }}</td>

                        <td class="px-6 py-3">
                            @if($transaction->type === 'income')
                                <span class="text-green-600 font-semibold">Income</span>
                            @else
                                <span class="text-red-600 font-semibold">Expense</span>
                            @endif
                        </td>

                        <td class="px-6 py-3 font-semibold">
                            @if($transaction->type === 'income')
                                <span class="text-green-600">₹{{ number_format($transaction->amount, 2) }}</span>
                            @else
                                <span class="text-red-600">₹{{ number_format($transaction->amount, 2) }}</span>
                            @endif
                        </td>

                        <td class="px-6 py-3 text-gray-700">
                            {{ $transaction->description ?? '—' }}
                        </td>

                        <td class="px-6 py-3 text-gray-700">
                            {{ $transaction->created_at->format('d M Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                            No transactions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
