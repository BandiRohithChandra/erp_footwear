@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">{{ __('Pending Payments') }}</h1>

    <!-- Back Button -->
<a href="{{ url()->previous() }}" class="back-btn">
    <span class="back-icon">←</span> Back
</a>

<style>
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(90deg, #9747FF 0%, #92D3F5 100%);
    color: #fff;
    font-weight: 600;
    font-size: 16px;
    border-radius: 30px;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    margin-bottom: 25px;
}

.back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    background: linear-gradient(90deg, #92D3F5 0%, #9747FF 100%);
}

.back-icon {
    font-size: 18px;
}
</style>


    <!-- Invoice Status Graph -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-xl font-semibold mb-4">{{ __('Invoice Status Overview') }}</h2>
        @if ($invoices->isEmpty())
            <p class="text-gray-600">{{ __('No pending invoices available.') }}</p>
        @else
            <canvas id="invoiceStatusChart" height="100"></canvas>
        @endif
    </div>

    <!-- Orders Ready for Invoicing -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">{{ __('Orders Ready for Invoicing') }}</h2>
        @if ($orders->isEmpty())
            <p class="text-gray-600">{{ __('No orders ready for invoicing.') }}</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm">
                            <th class="py-3 px-6 text-left">{{ __('Order ID') }}</th>
                            <th class="py-3 px-6 text-left">{{ __('Customer') }}</th>
                            <th class="py-3 px-6 text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        @foreach ($orders as $order)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $order->id }}</td>
                            <td class="py-3 px-6">
                                {{ $order->quotation->client_id ? $order->quotation->client->name : 'N/A' }}
                            </td>
                            <td class="py-3 px-6 text-center">
                                <a href="{{ route('sales.invoices.create', $order) }}"
                                   class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                   {{ __('Create Invoice') }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Pending Invoices -->
    <div>
        <h2 class="text-xl font-semibold mb-2">{{ __('Pending Invoices') }}</h2>
        @if ($invoices->isEmpty())
            <p class="text-gray-600">{{ __('No pending invoices found.') }}</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm">
                            <th class="py-3 px-6 text-left">{{ __('Invoice ID') }}</th>
                            <th class="py-3 px-6 text-left">{{ __('Order ID') }}</th>
                            <th class="py-3 px-6 text-left">{{ __('Customer') }}</th>
                            <th class="py-3 px-6 text-left">{{ __('Amount') }}</th>
                            <th class="py-3 px-6 text-left">{{ __('Amount Paid') }}</th>
                            <th class="py-3 px-6 text-left">{{ __('Remaining Balance') }}</th>
                            <th class="py-3 px-6 text-left">{{ __('Status') }}</th>
                            <th class="py-3 px-6 text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        @foreach ($invoices as $invoice)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $invoice->id }}</td>
                            <td class="py-3 px-6">{{ $invoice->order_id }}</td>
                            <td class="py-3 px-6">
                                {{ $invoice->order->quotation->client_id ? $invoice->order->quotation->client->name : 'N/A' }}
                            </td>
                            <td class="py-3 px-6">₹{{ number_format($invoice->amount, 2) }}</td>
                            <td class="py-3 px-6">₹{{ number_format($invoice->amount_paid, 2) }}</td>
                            <td class="py-3 px-6">₹{{ number_format($invoice->remaining_balance, 2) }}</td>
                            <td class="py-3 px-6">{{ ucfirst($invoice->status) }}</td>
                            <td class="py-3 px-6 text-center space-x-2">
                                <a href="{{ route('sales.invoices.show', $invoice) }}"
                                   class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600">
                                   {{ __('View') }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') return;

    @if ($invoices->isNotEmpty())
    try {
        const ctx = document.getElementById('invoiceStatusChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($invoiceStatusLabels),
                datasets: [{
                    label: '{{ __('Remaining Balance (₹)') }}',
                    data: [
                        @json($invoiceStatusData['pending']),
                        @json($invoiceStatusData['partially_paid'])
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(255, 206, 86, 0.5)'
                    ],
                    borderColor: ['#FF6384','#FFCE56'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: '{{ __('Amount (₹)') }}' } },
                    x: { title: { display: true, text: '{{ __('Status') }}' } }
                },
                plugins: { legend: { position: 'top' } }
            }
        });
    } catch (error) {
        console.error('Chart error:', error);
    }
    @endif
});
</script>
@endsection
