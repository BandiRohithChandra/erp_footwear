@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Edit Quotation') }}</h1>
        <a href="{{ url()->previous() }}" class="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-purple-600 to-blue-400 text-white font-semibold shadow-lg hover:shadow-xl">
            ← {{ __('Back') }}
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


    <form method="POST" action="{{ route('quotations.update', $quotation) }}" class="space-y-8 bg-white rounded-2xl shadow-xl p-8">
        @csrf
        @method('PUT')

        <!-- Client Section -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
            <label class="block font-semibold text-gray-700 mb-2">{{ __('Client') }} <span class="text-red-500">*</span></label>
            <select name="client_id" class="w-full p-3 border-2 border-gray-200 rounded-xl @error('client_id') border-red-500 @enderror" required>
                <option value="">{{ __('Choose a client...') }}</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" {{ $quotation->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->business_name }} - {{ $client->name }}
                    </option>
                @endforeach
            </select>
            @error('client_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Products Section -->
        <div id="products-container" class="space-y-4">
            @foreach ($quotation->products as $index => $product)
                @php
                    $productVariations = is_array($product->pivot->variations) ? $product->pivot->variations : json_decode($product->pivot->variations ?? '[]', true);

                @endphp

                <div class="product-row bg-white border-2 border-gray-200 rounded-xl p-6 shadow-sm" data-row-index="{{ $index }}">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Product {{ $index + 1 }}</h3>
                        <button type="button" class="remove-product-row text-red-500 hover:text-red-700 {{ $index == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $index == 0 ? 'disabled' : '' }}>Remove</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Article</label>
                            <select name="products[{{ $index }}][id]" class="product-id w-full p-3 border-2 border-gray-200 rounded-xl" required data-price="{{ $product->pivot->unit_price }}">
                                <option value="">{{ __('Select Article...') }}</option>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}" {{ $product->id == $p->id ? 'selected' : '' }}
                                        data-price="{{ $p->price }}"
                                        data-variations='@json($p->variations)'>
                                        {{ $p->name }} - ₹{{ number_format($p->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                            <input type="number" name="products[{{ $index }}][unit_price]" class="unit-price w-full p-3 border-2 border-gray-200 rounded-xl" value="{{ $product->pivot->unit_price }}" min="0" step="0.01" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                            <input type="number" name="products[{{ $index }}][quantity]" class="quantity w-full p-3 border-2 border-gray-200 rounded-xl" value="{{ $product->pivot->quantity }}" min="1" required>
                        </div>
                    </div>

                    <!-- Variations Table -->
                    @if(!empty($productVariations))
                        <table class="variations-table w-full mb-4 border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-3 py-2">Color</th>
                                    @for($size = 35; $size <= 44; $size++)
                                        <th class="border px-2 py-2 text-center">{{ $size }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productVariations as $vIndex => $var)
                                    <tr>
                                        <td class="border px-3 py-2">
                                            <input type="text" name="products[{{ $index }}][variations][{{ $vIndex }}][color]" value="{{ $var['color'] }}" class="color-input w-full px-2 py-1 border rounded text-sm">
                                        </td>
                                        @foreach($var['sizes'] as $size => $qty)
                                            <td class="border px-2 py-2 text-center">
                                                <input type="number" min="0" name="products[{{ $index }}][variations][{{ $vIndex }}][sizes][{{ $size }}]" value="{{ $qty }}" class="size-qty w-16 h-12 text-center border rounded text-sm">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <input type="hidden" name="products[{{ $index }}][total]" class="product-total-input" value="{{ $product->pivot->quantity * $product->pivot->unit_price }}">
                </div>
            @endforeach
        </div>

        <button type="button" id="add-product-row" class="w-full md:w-auto inline-flex items-center justify-center px-6 py-3 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium shadow-sm hover:scale-105">
            + {{ __('Add Another Article') }}
        </button>

        <!-- Totals Section -->
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100 mt-6">
            <div class="flex justify-between py-2">
                <span>Subtotal</span>
                <span id="subtotal">₹0.00</span>
            </div>
            <div class="flex justify-between py-2">
                <span>Tax (15%)</span>
                <span id="tax">₹0.00</span>
            </div>
            <div class="flex justify-between py-3 border-t-2 border-gray-200 font-bold">
                <span>Grand Total</span>
                <span id="grand-total">₹0.00</span>
            </div>

             <!-- Tax Type -->
    <div class="mt-4">
        <label for="tax_type" class="block text-sm font-medium text-gray-700 mb-1">Tax Type <span class="text-red-500">*</span></label>
        <select name="tax_type" id="tax_type" class="w-full p-3 border-2 border-gray-200 rounded-xl @error('tax_type') border-red-500 @enderror" required>
            <option value="">Select Tax Type</option>
            <option value="cgst" {{ old('tax_type', $quotation->tax_type) == 'cgst' ? 'selected' : '' }}>CGST</option>
            <option value="igst" {{ old('tax_type', $quotation->tax_type) == 'igst' ? 'selected' : '' }}>IGST</option>
        </select>
        @error('tax_type')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>


            <input type="hidden" name="subtotal" id="subtotal-input" value="0">
            <input type="hidden" name="tax" id="tax-input" value="0">
            <input type="hidden" name="grand_total" id="grand-total-input" value="0">
        </div>

        <div class="flex gap-4 mt-6">
            <a href="{{ route('quotations.index') }}" class="flex-1 px-6 py-3 rounded-xl bg-gray-600 text-white text-center hover:bg-gray-700">Cancel</a>
            <button type="submit" id="submit-btn" class="flex-1 px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Update Quotation</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Simple totals calculation
    const calculateTotals = () => {
        let subtotal = 0;
        document.querySelectorAll('.product-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.quantity')?.value || 0);
            const price = parseFloat(row.querySelector('.unit-price')?.value || 0);
            subtotal += qty * price;
        });
        const tax = subtotal * 0.15;
        const grand = subtotal + tax;

        document.getElementById('subtotal').textContent = `₹${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent = `₹${tax.toFixed(2)}`;
        document.getElementById('grand-total').textContent = `₹${grand.toFixed(2)}`;

        document.getElementById('subtotal-input').value = subtotal.toFixed(2);
        document.getElementById('tax-input').value = tax.toFixed(2);
        document.getElementById('grand-total-input').value = grand.toFixed(2);
    };

    document.querySelectorAll('.quantity, .unit-price, .size-qty').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });

    calculateTotals();
});
</script>
@endsection
