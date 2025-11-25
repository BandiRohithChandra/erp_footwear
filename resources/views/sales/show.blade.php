@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">{{ __('Sale Details') }}</h1>

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


        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-700">{{ __('Product') }}</p>
                <p class="mt-1 text-gray-900">{{ $sale->product->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700">{{ __('Warehouse') }}</p>
                <p class="mt-1 text-gray-900">{{ $sale->warehouse->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700">{{ __('Quantity') }}</p>
                <p class="mt-1 text-gray-900">{{ $sale->quantity }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700">{{ __('Unit Price') }}</p>
                <p class="mt-1 text-gray-900">{{ \App\Helpers\FormatMoney::format($sale->unit_price) }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700">{{ __('Discount') }}</p>
                <p class="mt-1 text-gray-900">{{ $sale->discount ? \App\Helpers\FormatMoney::format($sale->discount) : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700">{{ __('Tax Rate') }}</p>
                <p class="mt-1 text-gray-900">{{ $sale->tax_rate ? $sale->tax_rate . '%' : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700">{{ __('Tax Amount') }}</p>
                <p class="mt-1 text-gray-900">{{ \App\Helpers\FormatMoney::format($sale->tax_amount) }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700">{{ __('Total Amount') }}</p>
                <p class="mt-1 text-gray-900">{{ \App\Helpers\FormatMoney::format($sale->total_amount) }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700">{{ __('Sale Date') }}</p>
                <p class="mt-1 text-gray-900">{{ $sale->sale_date }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700">{{ __('Customer Name') }}</p>
                <p class="mt-1 text-gray-900">{{ $sale->customer_name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700">{{ __('Customer Email') }}</p>
                <p class="mt-1 text-gray-900">{{ $sale->customer_email ?? 'N/A' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm font-medium text-gray-700">{{ __('Notes') }}</p>
                <p class="mt-1 text-gray-900">{{ $sale->notes ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                {{ __('Back to Sales') }}
            </a>
            <a href="{{ route('sales.edit', $sale) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition ml-2">
                {{ __('Edit Sale') }}
            </a>
        </div>
    </div>
@endsection