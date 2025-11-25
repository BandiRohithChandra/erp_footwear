@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">{{ __('Add Sale') }}</h1>

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

        <form method="POST" action="{{ route('sales.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">{{ __('Product') }}</label>
                    <select name="product_id" id="product_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                        <option value="">{{ __('Select a product') }}</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="warehouse_id" class="block text-sm font-medium text-gray-700">{{ __('Warehouse') }}</label>
                    <select name="warehouse_id" id="warehouse_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                        <option value="">{{ __('Select a warehouse') }}</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('warehouse_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="available_quantity" class="block text-sm font-medium text-gray-700">{{ __('Available Quantity') }}</label>
                    <input type="number" id="available_quantity" value="0" readonly class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm bg-gray-100">
                </div>
                <div>
                    <label for="unit_price" class="block text-sm font-medium text-gray-700">{{ __('Unit Price') }}</label>
                    <input type="number" name="unit_price" id="unit_price" value="0" readonly class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm bg-gray-100">
                    @error('unit_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">{{ __('Quantity') }}</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required min="1">
                    @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="discount" class="block text-sm font-medium text-gray-700">{{ __('Discount') }}</label>
                    <input type="number" name="discount" id="discount" value="{{ old('discount', 0) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" min="0" step="0.01">
                    @error('discount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="tax_rate_select" class="block text-sm font-medium text-gray-700">{{ __('Tax Rate') }}</label>
                    <select name="tax_rate_select" id="tax_rate_select" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                        <option value="0" {{ old('tax_rate_select') == '0' ? 'selected' : '' }}>{{ __('No Tax') }}</option>
                        <option value="5" {{ old('tax_rate_select') == '5' ? 'selected' : '' }}>5%</option>
                        <option value="15" {{ old('tax_rate_select') == '15' ? 'selected' : '' }}>15%</option>
                        <option value="18" {{ old('tax_rate_select') == '18' ? 'selected' : '' }}>18%</option>
                        <option value="20" {{ old('tax_rate_select') == '20' ? 'selected' : '' }}>20%</option>
                        <option value="custom" {{ old('tax_rate_select') == 'custom' ? 'selected' : '' }}>{{ __('Custom') }}</option>
                    </select>
                    @error('tax_rate_select') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="custom_tax_rate" class="{{ old('tax_rate_select') == 'custom' ? '' : 'hidden' }}">
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700">{{ __('Custom Tax Rate (%)') }}</label>
                    <input type="number" name="tax_rate" id="tax_rate" value="{{ old('tax_rate') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" min="0" max="100" step="0.01" {{ old('tax_rate_select') == 'custom' ? 'required' : '' }}>
                    @error('tax_rate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="sale_date" class="block text-sm font-medium text-gray-700">{{ __('Sale Date') }}</label>
                    <input type="date" name="sale_date" id="sale_date" value="{{ old('sale_date') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('sale_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700">{{ __('Customer Name') }}</label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('customer_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="customer_email" class="block text-sm font-medium text-gray-700">{{ __('Customer Email') }}</label>
                    <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    @error('customer_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                    <textarea name="notes" id="notes" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                    @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                {{ __('Add Sale') }}
            </button>
        </form>
    </div>

    <script>
        // Tax rate toggle
        document.getElementById('tax_rate_select').addEventListener('change', function () {
            const customTaxRateDiv = document.getElementById('custom_tax_rate');
            const taxRateInput = document.getElementById('tax_rate');
            if (this.value === 'custom') {
                customTaxRateDiv.classList.remove('hidden');
                taxRateInput.setAttribute('required', 'required');
            } else {
                customTaxRateDiv.classList.add('hidden');
                taxRateInput.removeAttribute('required');
            }
        });

        // Script for fetching available quantity and unit price
        document.addEventListener('DOMContentLoaded', function () {
            const productSelect = document.querySelector('#product_id');
            const warehouseSelect = document.querySelector('#warehouse_id');
            const availableQuantityInput = document.querySelector('#available_quantity');
            const unitPriceInput = document.querySelector('#unit_price');

            if (!productSelect || !warehouseSelect || !availableQuantityInput || !unitPriceInput) {
                console.error('One or more form elements not found:', {
                    productSelect: !!productSelect,
                    warehouseSelect: !!warehouseSelect,
                    availableQuantityInput: !!availableQuantityInput,
                    unitPriceInput: !!unitPriceInput
                });
                return;
            }

            const fetchProductDetails = async () => {
                const productId = productSelect.value;
                const warehouseId = warehouseSelect.value;

                console.log('Fetching product details for:', { productId, warehouseId });

                if (!productId || !warehouseId) {
                    console.log('Product or warehouse not selected');
                    availableQuantityInput.value = 0;
                    unitPriceInput.value = 0;
                    return;
                }

                try {
                    const url = `/sales/product-details?product_id=${productId}&warehouse_id=${warehouseId}`;
                    console.log('Request URL:', url);
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin' // Ensure cookies are sent with the request
                    });

                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(`HTTP error! Status: ${response.status}, Response: ${errorText}`);
                    }

                    const data = await response.json();
                    console.log('Fetched product details:', data);

                    if (data.error) {
                        throw new Error(`Server error: ${data.error}`);
                    }

                    availableQuantityInput.value = data.available_quantity ?? 0;
                    unitPriceInput.value = data.unit_price ?? 0;

                    // Make unit_price editable after fetching
                    unitPriceInput.removeAttribute('readonly');
                } catch (error) {
                    console.error('Error fetching product details:', error.message);
                    availableQuantityInput.value = 0;
                    unitPriceInput.value = 0;
                }
            };

            productSelect.addEventListener('change', fetchProductDetails);
            warehouseSelect.addEventListener('change', fetchProductDetails);

            // Initial fetch if values are pre-selected
            if (productSelect.value && warehouseSelect.value) {
                fetchProductDetails();
            }
        });
    </script>
@endsection