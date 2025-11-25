@extends('layouts.app')


@section('content')
<div class="p-8">

    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-extrabold text-gray-800">{{ __('Finance Dashboard') }}</h1>

        <div class="flex items-center gap-3">
            <a href="{{ route('finance.report') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                {{ __('Download Financial Report') }}
            </a>

            <a href="{{ route('finance.export') }}"
               class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                {{ __('Export CSV') }}
            </a>
        </div>
    </div>

    <!-- TOP CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <div class="bg-white p-6 rounded-2xl shadow">
            <h2 class="text-lg font-semibold text-gray-700">{{ __('Accounts Payable') }}</h2>
            <p class="text-3xl font-bold text-gray-900 mt-2">
                {{ $formattedTotalPayable ?? \App\Helpers\FormatMoney::format(0) }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow">
            <h2 class="text-lg font-semibold text-gray-700">{{ __('Accounts Receivable') }}</h2>
            <p class="text-3xl font-bold text-gray-900 mt-2">
                {{ $formattedTotalReceivable ?? \App\Helpers\FormatMoney::format(0) }}
            </p>
        </div>

    </div>

    <!-- RECENT TRANSACTIONS -->
    <div class="bg-white p-6 rounded-2xl shadow mb-10">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ __('Recent Transactions') }} (Pending or Approved)
        </h2>

        @if ($recentTransactions->isEmpty())
            <p class="text-gray-600 mt-3">{{ __('No recent transactions found.') }}</p>
        @else
            <div class="overflow-x-auto mt-4">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 border text-left">{{ __('Date') }}</th>
                            <th class="py-3 px-4 border text-left">{{ __('Type') }}</th>
                            <th class="py-3 px-4 border text-left">{{ __('Amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 border">
                                    {{ $transaction->created_at->format('Y-m-d') }}
                                </td>
                                <td class="py-3 px-4 border">
                                    {{ ucfirst($transaction->type) }}
                                </td>
                                <td class="py-3 px-4 border">
                                    {{ $transaction->formatted_amount }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- PAYABLE CHART -->
    <div class="bg-white p-6 rounded-2xl shadow mb-10">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Payables (Historical & Forecasted)') }}</h2>
        <canvas id="payableChart" height="100" class="mt-4"></canvas>
    </div>

    <!-- RECEIVABLE CHART -->
    <div class="bg-white p-6 rounded-2xl shadow mb-10">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Receivables (Historical & Forecasted)') }}</h2>
        <canvas id="receivableChart" height="100" class="mt-4"></canvas>
    </div>

    <!-- HISTORICAL PAYABLES -->
    <div class="bg-white p-6 rounded-2xl shadow mb-10">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Historical Payables (Last 12 Months)') }}</h2>

        <div class="overflow-x-auto mt-4">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        @foreach($months as $month)
                            <th class="py-3 px-4 border text-left">{{ $month }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($payableData as $amount)
                            <td class="py-3 px-4 border">{{ \App\Helpers\FormatMoney::format($amount) }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- HISTORICAL RECEIVABLES -->
    <div class="bg-white p-6 rounded-2xl shadow mb-10">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Historical Receivables (Last 12 Months)') }}</h2>

        <div class="overflow-x-auto mt-4">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        @foreach($months as $month)
                            <th class="py-3 px-4 border">{{ $month }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($receivableData as $amount)
                            <td class="py-3 px-4 border">{{ \App\Helpers\FormatMoney::format($amount) }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- FORECASTED PAYABLES -->
    <div class="bg-white p-6 rounded-2xl shadow mb-10">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Forecasted Payables (Next 6 Months)') }}</h2>

        <div class="overflow-x-auto mt-4">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        @foreach($futureMonths as $month)
                            <th class="py-3 px-4 border">{{ $month }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($forecastedPayable as $amount)
                            <td class="py-3 px-4 border">{{ $amount }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- FORECASTED RECEIVABLES -->
    <div class="bg-white p-6 rounded-2xl shadow mb-10">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Forecasted Receivables (Next 6 Months)') }}</h2>

        <div class="overflow-x-auto mt-4">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        @foreach($futureMonths as $month)
                            <th class="py-3 px-4 border">{{ $month }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($forecastedReceivable as $amount)
                            <td class="py-3 px-4 border">{{ $amount }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>




    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Payable Chart
        const payableCtx = document.getElementById('payableChart').getContext('2d');
        new Chart(payableCtx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Payables',
                    data: @json($chartPayableData),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount ({{ $defaultCurrency }})'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                }
            }
        });

        // Receivable Chart
        const receivableCtx = document.getElementById('receivableChart').getContext('2d');
        new Chart(receivableCtx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Receivables',
                    data: @json($chartReceivableData),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount ({{ $defaultCurrency }})'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                }
            }
        });
    </script>
@endsection