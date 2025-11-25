@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold mb-6">{{ __('Generate Invoice for Order #') }}{{ $order->id }}</h1>

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

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Display Quotation Details -->
    <div class="bg-white p-6 rounded-lg shadow space-y-4">
        <h2 class="text-xl font-semibold mb-4">{{ __('Quotation Details') }}</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">{{ __('Article') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Total Qty') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Invoiced Qty') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Remaining Qty') }}</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @php
                        $totalRemaining = 0;
                    @endphp
                    @foreach ($order->quotation->products as $product)
                        @php
                            // Calculate previously invoiced qty
                            $invoicedQty = 0;
                            foreach ($order->quotation->invoices as $invoice) {
                                $items = json_decode($invoice->items, true) ?? [];
                                foreach ($items as $item) {
                                    if ($item['product_id'] == $product->id) {
                                        $invoicedQty += $item['quantity'];
                                    }
                                }
                            }
                            $remainingQty = $product->pivot->quantity - $invoicedQty;
                            $totalRemaining += $remainingQty;
                        @endphp
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $product->name }}</td>
                            <td class="py-3 px-6 text-right">{{ $product->pivot->quantity }}</td>
                            <td class="py-3 px-6 text-right">{{ $invoicedQty }}</td>
                            <td class="py-3 px-6 text-right text-{{ $remainingQty > 0 ? 'green' : 'red' }}-600">
                                {{ $remainingQty }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Invoice Generation Form -->
    @if ($totalRemaining > 0)
    <form method="POST" action="{{ route('sales.invoices.store', $order) }}" class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf
        <h2 class="text-xl font-semibold mb-4">{{ __('Invoice Details') }}</h2>

        <input type="hidden" name="po_no" value="{{ request('po_no') }}">


        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">{{ __('Article') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Remaining Qty') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Assign Qty for Invoice') }}</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @foreach ($order->quotation->products as $product)
                        @php
                            $invoicedQty = 0;
                            foreach ($order->quotation->invoices as $invoice) {
                                $items = json_decode($invoice->items, true) ?? [];
                                foreach ($items as $item) {
                                    if ($item['product_id'] == $product->id) {
                                        $invoicedQty += $item['quantity'];
                                    }
                                }
                            }
                            $remainingQty = $product->pivot->quantity - $invoicedQty;
                        @endphp
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $product->name }}</td>
                            <td class="py-3 px-6 text-right">{{ $remainingQty }}</td>
                            <td class="py-3 px-6 text-right">
                                @if ($remainingQty > 0)
                                    <input type="number"
                                           name="assigned_qty[{{ $product->id }}]"
                                           min="0"
                                           max="{{ $remainingQty }}"
                                           value="0"
                                           class="w-24 p-1 border rounded-lg text-right focus:ring-2 focus:ring-blue-500">
                                @else
                                    <input type="number" disabled class="w-24 p-1 border rounded-lg bg-gray-100 text-right" value="0">
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            <label for="payment_type" class="block text-gray-700 font-medium mb-2">{{ __('Payment Type') }}</label>
            <select id="payment_type" name="payment_type" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('payment_type') border-red-500 @enderror" required>
                <option value="immediate" {{ old('payment_type') === 'immediate' ? 'selected' : '' }}>{{ __('Immediate') }}</option>
                <option value="grace" {{ old('payment_type') === 'grace' ? 'selected' : '' }}>{{ __('Grace Period') }}</option>
            </select>
            @error('payment_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div id="grace_period_container" class="hidden">
            <label for="grace_period_days" class="block text-gray-700 font-medium mb-2">{{ __('Grace Period (Days)') }}</label>
            <input type="number" id="grace_period_days" name="grace_period_days" value="{{ old('grace_period_days') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('grace_period_days') border-red-500 @enderror" min="1">
            @error('grace_period_days')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
            {{ __('Generate Invoice') }}
        </button>
    </form>
    @else
        <div class="bg-red-100 text-red-700 p-4 rounded mt-6">
            {{ __('All products have been fully invoiced. No remaining quantity to invoice.') }}
        </div>
    @endif
</div>

<script>
    document.getElementById('payment_type').addEventListener('change', function() {
        const gracePeriodContainer = document.getElementById('grace_period_container');
        if (this.value === 'grace') {
            gracePeriodContainer.classList.remove('hidden');
            document.getElementById('grace_period_days').setAttribute('required', 'required');
        } else {
            gracePeriodContainer.classList.add('hidden');
            document.getElementById('grace_period_days').removeAttribute('required');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const paymentType = document.getElementById('payment_type').value;
        const gracePeriodContainer = document.getElementById('grace_period_container');
        if (paymentType === 'grace') {
            gracePeriodContainer.classList.remove('hidden');
            document.getElementById('grace_period_days').setAttribute('required', 'required');
        }
    });
</script>
@endsection
