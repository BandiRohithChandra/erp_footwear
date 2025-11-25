@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">{{ __('Edit Sale') }}</h1>

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


        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('sales.update', $sale) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">{{ __('Product') }}</label>
                    <select name="product_id" id="product_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                        <option value="">{{ __('Select Product') }}</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id', $sale->product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                    @error('product_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="warehouse_id" class="block text-sm font-medium text-gray-700">{{ __('Warehouse') }}</label>
                    <select name="warehouse_id" id="warehouse_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                        <option value="">{{ __('Select Warehouse') }}</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $sale->warehouse_id) == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                    @error('warehouse_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">{{ __('Quantity') }}</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $sale->quantity) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required min="1">
                    @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="unit_price" class="block text-sm font-medium text-gray-700">{{ __('Unit Price') }}</label>
                    <input type="number" name="unit_price" id="unit_price" value="{{ old('unit_price', $sale->unit_price) }}" step="0.01" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('unit_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="tax_rate_select" class="block text-sm font-medium text-gray-700">{{ __('Tax Rate') }}</label>
                    <select name="tax_rate_select" id="tax_rate_select" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                        <option value="0" {{ old('tax_rate_select', $sale->tax_rate) == '0' ? 'selected' : '' }}>{{ __('Tax Exempt') }}</option>
                        <option value="5" {{ old('tax_rate_select', $sale->tax_rate) == '5' ? 'selected' : '' }}>5% (VAT)</option>
                        <option value="15" {{ old('tax_rate_select', $sale->tax_rate) == '15' ? 'selected' : '' }}>15% (GST)</option>
                        <option value="18" {{ old('tax_rate_select', $sale->tax_rate) == '18' ? 'selected' : '' }}>18%</option>
                        <option value="20" {{ old('tax_rate_select', $sale->tax_rate) == '20' ? 'selected' : '' }}>20%</option>
                        <option value="custom" {{ old('tax_rate_select') == 'custom' || (!in_array($sale->tax_rate, [0, 5, 15, 18, 20]) && $sale->tax_rate != null) ? 'selected' : '' }}>{{ __('Custom') }}</option>
                    </select>
                    @error('tax_rate_select') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="custom_tax_rate" class="{{ in_array($sale->tax_rate, [0, 5, 15, 18, 20]) ? 'hidden' : '' }}">
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700">{{ __('Custom Tax Rate (%)') }}</label>
                    <input type="number" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', $sale->tax_rate) }}" step="0.01" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    @error('tax_rate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="sale_date" class="block text-sm font-medium text-gray-700">{{ __('Sale Date') }}</label>
                    <input type="date" name="sale_date" id="sale_date" value="{{ old('sale_date', $sale->sale_date) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('sale_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700">{{ __('Customer Name') }}</label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $sale->customer_name) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('customer_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="customer_email" class="block text-sm font-medium text-gray-700">{{ __('Customer Email') }}</label>
                    <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email', $sale->customer_email) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    @error('customer_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                    <textarea name="notes" id="notes" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">{{ old('notes', $sale->notes) }}</textarea>
                    @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ __('Update Sale') }}
            </button>
        </form>
    </div>

    <script>
        document.getElementById('tax_rate_select').addEventListener('change', function () {
            const customTaxRateDiv = document.getElementById('custom_tax_rate');
            if (this.value === 'custom') {
                customTaxRateDiv.classList.remove('hidden');
                document.getElementById('tax_rate').setAttribute('required', 'required');
            } else {
                customTaxRateDiv.classList.add('hidden');
                document.getElementById('tax_rate').removeAttribute('required');
            }
        });
    </script>
@endsection