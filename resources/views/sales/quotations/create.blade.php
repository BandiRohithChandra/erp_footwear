@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ __('Create Quotation') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('Fill in the details to create a new quotation') }}</p>
            </div>

            <a href="{{ url()->previous() }}" class="back-btn inline-flex items-center gap-2"
                aria-label="Go back to previous page">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                {{ __('Back') }}
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-8">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            @php
                use Illuminate\Support\Str;
            @endphp

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl mb-6 mx-8 mt-8">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-500 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <ul class="text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif


            <form method="POST" action="{{ route('quotations.store') }}" class="p-8 space-y-8" id="quotation-form"
                enctype="multipart/form-data">
                @csrf

                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        {{ __('Party Information') }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="client_id" class="block font-semibold text-gray-700 mb-2">
                                {{ __('Select Party') }} <span class="text-red-500">*</span>
                            </label>
                            <!-- Quotation Form Select -->
                            <select id="client_id" name="client_id" required>
                                <option value="">{{ __('Choose a Party...') }}</option>
                                <option value="add-new">{{ __('Add New Party...') }}</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->business_name }} - {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('client_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
    <label for="brand_name" class="block font-semibold text-gray-700 mb-2">
        {{ __('Brand') }}
    </label>

    <input 
        type="text" 
        id="brand_name" 
        name="brand_name"
        value="{{ old('brand_name') }}"
        class="w-full p-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200"
        placeholder="{{ __('Enter brand name...') }}"
    >

    @error('brand_name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

                    </div>

                </div>



                <div class="space-y-6">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            {{ __('Products & Variations') }}
                        </h2>

                        <div id="products-container" class="space-y-4">
                            <div class="product-row bg-white border-2 border-gray-200 rounded-xl p-6 shadow-sm transition-all duration-200 hover:shadow-md"
                                data-row-index="0">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-semibold text-gray-800" id="product-title-0">Article 1</h3>
                                    <button type="button"
                                        class="remove-product-row remove-product-btn opacity-50 cursor-not-allowed" disabled
                                        aria-label="Remove this product row" aria-disabled="true">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        {{ __('Remove') }}
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Article No</label>
                                        <input type="text" name="products[0][article_no]"
                                            class="article-no w-full p-3 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-600 font-mono"
                                            readonly>
                                    </div>

                                    <div>
                                        <label for="product-0" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ __('Article') }} <span class="text-red-500">*</span>
                                        </label>
                                        <select id="product-0" name="products[0][id]"
                                            class="product-id w-full p-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 @error('products.0.id') border-red-500 focus:ring-red-200 @enderror"
                                            required>
                                            <option value="">{{ __('Select Article...') }}</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                                    data-article="{{ $product->sku }}"
                                                    data-variations='@json($product->variations)'>
                                                    {{ $product->name }} - ₹{{ number_format($product->price, 2) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('products.0.id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Unit Price') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="number" step="0.01" name="products[0][unit_price]"
                                            class="unit-price w-full p-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200 text-right @error('products.0.unit_price') border-red-500 focus:ring-red-200 @enderror"
                                            min="0" required>
                                        @error('products.0.unit_price')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <input type="hidden" name="products[0][quantity]" class="product-total-quantity" value="0">

                                <div
                                    class="variations-table hidden bg-gray-50 rounded-xl p-4 border border-gray-200 overflow-x-auto">
                                    <div class="text-sm font-medium text-gray-700 mb-3 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4a1 1 0 011-1h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm5 2a1 1 0 00-1 1v8a1 1 0 001 1h6a1 1 0 001-1V7a1 1 0 00-1-1H9z">
                                                </path>
                                            </svg>
                                            Variations Details
                                        </div>
                                        <button type="button"
                                            class="add-variation-btn inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all duration-200"
                                            aria-label="Add new variation row">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Add Variation
                                        </button>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="w-full border-collapse bg-white rounded-lg shadow-sm">
                                            <thead class="bg-gradient-to-r from-blue-50 to-indigo-50">
                                                <tr>
                                                    <th
                                                        class="border border-gray-300 px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                        Color</th>
                                                    @for($i = 35; $i <= 44; $i++)
                                                        <th
                                                            class="border border-gray-300 px-2 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                            {{ $i }}
                                                        </th>
                                                    @endfor
                                                    <th
                                                        class="border border-gray-300 px-3 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                        Total</th>
                                                    <th
                                                        class="border border-gray-300 px-3 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                        Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <!-- Filled dynamically -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="add-product-row" class="add-product-btn w-full md:w-auto"
                            aria-label="Add another article to the quotation">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Add Another Article') }}
                        </button>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        {{ __('Summary') }}
                    </h2>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-700">Tax Type</span>
                            <select id="tax-type" name="tax_type"
                                class="border-2 border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                <option value="cgst" selected>CGST/SGST (2.5% + 2.5%)</option>
                                <option value="igst">IGST (5%)</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between py-2">
                                <span class="text-sm text-gray-600">Subtotal</span>
                                <span class="text-lg font-semibold text-gray-900" id="subtotal">₹0.00</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-sm text-gray-600" id="tax-label">Tax (CGST/SGST 2.5% + 2.5%)</span>
                                <span class="text-lg font-semibold text-gray-900" id="tax">₹0.00</span>
                            </div>
                            <div class="flex justify-between py-3 border-t-2 border-gray-200 bg-gray-50 rounded-xl px-4">
                                <span class="text-xl font-bold text-gray-900">Grand Total</span>
                                <span class="text-2xl font-bold text-blue-600" id="grand-total">₹0.00</span>
                            </div>
                        </div>

                        <input type="hidden" name="subtotal" id="subtotal-input" value="0">
                        <input type="hidden" name="tax" id="tax-input" value="0">
                        <input type="hidden" name="grand_total" id="grand-total-input" value="0">
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ route('quotations.index') }}" class="cancel-btn btn btn-outline"
                        aria-label="Cancel and return to quotations list">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                        {{ __('Cancel') }}
                    </a>

                    <button type="submit" class="create-btn btn btn-primary" id="submit-btn"
                        aria-label="Create new quotation">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                            </path>
                        </svg>
                        {{ __('Create Quotation') }}
                    </button>
                </div>
            </form>
        </div>
    </div>



    <!-- Modal for Adding New Party -->
    <div id="add-party-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden"
        style="overflow: scroll;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center sticky top-0 bg-white z-10 border-b border-gray-200 py-4 px-6">
                <h2 class="text-2xl font-bold text-blue-600">{{ __('Add New Party') }}</h2>
                <button type="button" id="cancel-party-btn"
                    class="text-gray-400 hover:text-gray-600 text-3xl font-bold transition-all">&times;</button>
            </div>
            <div class="flex-1 overflow-y-auto p-6">
                <form id="add-party-form" action="{{ route('clients.store') }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" style="padding-top: 20px;">
                    @csrf
                    <!-- Business/Company Name (Required) -->
                    <div class="col-span-1">
                        <label for="business_name" class="block text-sm font-medium text-gray-700">
                            {{ __('Business/Company Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="business_name" name="business_name"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                        <p id="business_name_error" class="text-red-500 text-sm mt-1 hidden"></p>
                        @error('business_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Person Name (Optional) -->
                    <div class="col-span-1">
                        <label for="name"
                            class="block text-sm font-medium text-gray-700">{{ __('Contact Person Name') }}</label>
                        <input type="text" id="name" name="name"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <p id="name_error" class="text-red-500 text-sm mt-1 hidden"></p>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address (Required) -->
                    <div class="col-span-1 md:col-span-2 lg:col-span-3">
                        <label for="address" class="block text-sm font-medium text-gray-700">
                            {{ __('Address') }}
                        </label>
                        <textarea id="address" name="address" rows="3"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                        <p id="address_error" class="text-red-500 text-sm mt-1 hidden"></p>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category (Required) -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">
                            {{ __('Category') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="category" name="category"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                            <option value="">-- {{ __('Select Category') }} --</option>
                            <option value="wholesale">{{ __('Wholesale') }}</option>
                            <option value="retail">{{ __('Retail') }}</option>
                        </select>
                        <p id="category_error" class="text-red-500 text-sm mt-1 hidden"></p>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- GST No (Required) -->
                    <div>
                        <label for="gst_no" class="block text-sm font-medium text-gray-700">
                            {{ __('GST No') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="gst_no" name="gst_no"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required>
                        <p id="gst_no_error" class="text-red-500 text-sm mt-1 hidden"></p>
                        @error('gst_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email (Optional for remote users) -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                        <input type="email" id="email" name="email"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <p id="email_error" class="text-red-500 text-sm mt-1 hidden"></p>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sales Rep (Optional) -->
                    <div>
                        <label for="sales_rep_id"
                            class="block text-sm font-medium text-gray-700">{{ __('Assign Sales Rep') }}</label>
                        <select id="sales_rep_id" name="sales_rep_id"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">-- {{ __('None') }} --</option>
                            @foreach($salesReps as $rep)
                                <option value="{{ $rep->id }}" {{ old('sales_rep_id') == $rep->id ? 'selected' : '' }}>
                                    {{ $rep->name }}
                                </option>
                            @endforeach
                        </select>
                        <p id="sales_rep_id_error" class="text-red-500 text-sm mt-1">
                            {{ old('sales_rep_id') ? $errors->first('sales_rep_id') : '' }}
                        </p>
                        @error('sales_rep_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone (Optional for remote users) -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('Phone') }}</label>
                        <input type="text" id="phone" name="phone"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <p id="phone_error" class="text-red-500 text-sm mt-1 hidden"></p>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Documents and Passwords: Only required for non-remote users -->
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Company Document</label>
                            <input type="file" name="company_document"
                                class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                accept="image/*,application/pdf">
                            @error('company_document')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Aadhar Certificate</label>
                            <input type="file" name="aadhar_certificate"
                                class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                accept="image/*,application/pdf">
                            @error('aadhar_certificate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">GST Certificate <span
                                    class="text-red-500">*</span></label>
                            <input type="file" name="gst_certificate"
                                class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                required accept="image/*,application/pdf">
                            @error('gst_certificate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Electricity Bill</label>
                            <input type="file" name="electricity_certificate"
                                class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                accept="image/*,application/pdf">
                            @error('electricity_certificate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password & Confirmation (Optional for remote users) -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                            Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 flex justify-end gap-3 mt-6">
                        <button type="button" id="cancel-party-btn"
                            class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">{{ __('Cancel') }}</button>
                        <button type="submit" id="save-party-btn"
                            class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">{{ __('Save Party') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <style>
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            margin-top: 2rem;
        }

        @media (min-width: 640px) {
            .action-buttons {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 1.5rem;
            font-weight: 600;
            border-radius: 0.75rem;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            color: #ffffff;
            font-size: 1rem;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            min-height: 3rem;
            width: 100%;
        }

        @media (min-width: 640px) {
            .btn {
                width: auto;
                flex: 1;
                max-width: 200px;
            }
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        }

        .btn-secondary:hover:not(:disabled) {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
        }

        .btn-danger:hover:not(:disabled) {
            background: linear-gradient(135deg, #fc8181 0%, #c53030 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 101, 101, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }

        .btn-outline:hover:not(:disabled) {
            background: #f7fafc;
            border-color: #cbd5e0;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 9999px;
            text-decoration: none;
            transition: all 0.2s ease;
            color: white;
            font-size: 0.875rem;
            border: none;
            background: linear-gradient(to right, #8b5cf6, #3b82f6);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .back-btn:hover {
            background: linear-gradient(to right, #7c3aed, #2563eb);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
        }

        .btn:disabled,
        .remove-product-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }

        .btn:disabled::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.2);
            pointer-events: none;
        }

        .btn.loading {
            position: relative;
            color: transparent;
        }

        .btn.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .btn svg,
        .back-btn svg {
            margin-right: 0.5rem;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .btn:hover:not(:disabled) svg,
        .back-btn:hover svg {
            transform: scale(1.1);
        }

        .btn::before,
        .back-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:active::before,
        .back-btn:active::before {
            width: 300px;
            height: 300px;
        }

        .remove-product-btn,
        .remove-variation-btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            min-height: auto;
            width: auto;
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 0.5rem;
        }

        .remove-product-btn:not(:disabled):hover,
        .remove-variation-btn:not(:disabled):hover {
            background: #fecaca;
            color: #b91c1c;
        }

        .add-product-btn,
        .add-variation-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 7px;
            margin: 6px;
            /* padding: 0.50rem 0.5rem; */
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .add-product-btn:hover:not(:disabled),
        .add-variation-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        @media (max-width: 640px) {

            .btn,
            .back-btn,
            .add-variation-btn {
                padding: 0.875rem 1.25rem;
                font-size: 0.9375rem;
            }

            .action-buttons {
                gap: 0.75rem;
            }

            .add-product-btn {
                width: 100%;
            }
        }

        .btn:focus,
        .back-btn:focus,
        .add-product-btn:focus,
        .add-variation-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }

        .btn:focus:not(:focus-visible),
        .back-btn:focus:not(:focus-visible),
        .add-product-btn:focus:not(:focus-visible),
        .add-variation-btn:focus:not(:focus-visible) {
            box-shadow: none;
        }

        @media (prefers-contrast: high) {

            .btn,
            .back-btn,
            .add-product-btn,
            .add-variation-btn {
                border: 2px solid;
            }

            .btn:disabled,
            .remove-product-btn:disabled,
            .remove-variation-btn:disabled {
                border-color: #9ca3af;
            }
        }

        .product-row {
            transition: all 0.3s ease;
        }

        .product-row.removing {
            opacity: 0;
            transform: translateX(20px);
        }

        .product-row.adding {
            opacity: 0;
            transform: translateY(-10px);
        }

        .variation-row.removing {
            opacity: 0;
            transform: translateX(20px);
        }

        .product-row.valid {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .product-row.warning {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .product-row.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Clear "0" or "0.00" when focused (for qty and price)
            document.addEventListener('focusin', function (e) {
                if (!e.target) return;
                // match number inputs, qty inputs, and unit-price fields
                if (e.target.matches('input[type="number"]') || e.target.classList.contains('qty-input') || e.target.classList.contains('unit-price')) {
                    const val = (e.target.value || '').toString().trim();
                    if (val === '0' || val === '0.0' || val === '0.00' || val === '0.000') {
                        e.target.value = '';
                    }
                    try { if (e.target.select) e.target.select(); } catch (err) { /* ignore */ }
                }
            });

            // Reset empty fields back to default value on blur and format unit-price
            document.addEventListener('focusout', function (e) {
                if (!e.target) return;
                if (e.target.matches('input[type="number"]') || e.target.classList.contains('qty-input') || e.target.classList.contains('unit-price')) {
                    let val = (e.target.value || '').toString().trim();
                    if (val === '') {
                        if (e.target.classList && e.target.classList.contains('unit-price')) {
                            e.target.value = '0.00';
                        } else {
                            e.target.value = '0';
                        }
                    } else {
                        // If it's a unit-price ensure two decimal places
                        if (e.target.classList && e.target.classList.contains('unit-price')) {
                            let n = parseFloat(val.replace(/[^0-9.-]+/g, ''));
                            if (isNaN(n)) n = 0;
                            e.target.value = n.toFixed(2);
                        }
                    }
                }
            });
        });
    </script>


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Select all quantity input fields dynamically (even those added later)
                document.addEventListener('focusin', function (e) {
                    if (e.target.matches('input[type="number"], input.qty-input')) {
                        if (e.target.value === '0') {
                            e.target.value = ''; // clear 0 on focus
                        }
                    }
                });

                // Optional: if user leaves it blank → reset to 0
                document.addEventListener('focusout', function (e) {
                    if (e.target.matches('input[type="number"], input.qty-input')) {
                        if (e.target.value.trim() === '') {
                            e.target.value = '0';
                        }
                    }
                });
            });
        </script>


        <script>
            const saveClientUrl = "{{ route('clients.store') }}";

            class PartyModal {
                constructor() {
                    this.modal = document.getElementById('add-party-modal');
                    this.form = document.getElementById('add-party-form');
                    this.saveBtn = document.getElementById('save-party-btn');
                    this.cancelBtns = document.querySelectorAll('#cancel-party-btn');
                    this.clientSelect = document.getElementById('client_id');
                    this.fields = [
                        'business_name', 'name', 'address', 'category', 'gst_no', 'email',
                        'sales_rep_id', 'phone', 'password', 'password_confirmation',
                        'company_document', 'aadhar_certificate', 'gst_certificate', 'electricity_certificate'
                    ];
                    this.isSavingClient = false;

                    // Bind once
                    this.savePartyBound = this.saveParty.bind(this);

                    this.init();
                }

                init() {
                    this.setupEventListeners();
                    console.log('PartyModal initialized');
                }

                setupEventListeners() {
                    if (this.clientSelect) {
                        this.clientSelect.addEventListener('change', (e) => {
                            if (e.target.value === 'add-new') {
                                this.showModal();
                                e.target.value = '';
                                this.isSavingClient = true;
                            }
                        });
                    }

                    this.cancelBtns.forEach(btn => {
                        btn.addEventListener('click', () => {
                            this.hideModal();
                            this.isSavingClient = false;
                        });
                    });

                    if (this.form && !this.form.dataset.listenerAttached) {
                        this.form.addEventListener('submit', this.savePartyBound);
                        this.form.dataset.listenerAttached = true;
                    }

                    const quotationFormEl = document.getElementById('quotation-form');
                    if (quotationFormEl && !quotationFormEl.dataset.listenerAttached) {
                        quotationFormEl.addEventListener('submit', (e) => {
                            if (this.isSavingClient) {
                                e.preventDefault();
                                alert('Please save the new party first.');
                            } else if (!this.clientSelect.value) {
                                e.preventDefault();
                                alert('Please select a valid party or add a new one.');
                            }
                        });
                        quotationFormEl.dataset.listenerAttached = true;
                    }
                }

                showModal() {
                    if (this.modal) {
                        this.modal.classList.remove('hidden');
                        const firstInput = this.form.querySelector('input, select, textarea');
                        if (firstInput) firstInput.focus();
                    }
                }

                hideModal() {
                    if (this.modal) {
                        this.modal.classList.add('hidden');
                        if (this.form) {
                            this.form.reset();
                            this.clearErrors();
                        }
                    }
                }

                clearErrors() {
                    this.fields.forEach(field => {
                        const errorEl = document.getElementById(`${field}_error`);
                        if (errorEl) {
                            errorEl.textContent = '';
                            errorEl.classList.add('hidden');
                        }
                    });
                }

                validateForm() {
                    let valid = true;
                    this.clearErrors();
                    this.fields.forEach(field => {
                        const input = document.getElementById(field);
                        if (!input) return;

                        if (input.hasAttribute('required') && !input.value.trim()) {
                            this.showError(field, `${this.formatFieldName(field)} is required.`);
                            valid = false;
                        }

                        if (field === 'email' && input.value) {
                            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (!regex.test(input.value)) {
                                this.showError(field, 'Enter a valid email address.');
                                valid = false;
                            }
                        }

                        if (field === 'password_confirmation') {
                            const password = document.getElementById('password').value;
                            if (input.value !== password) {
                                this.showError(field, 'Passwords do not match.');
                                valid = false;
                            }
                        }
                    });
                    return valid;
                }

                showError(field, message) {
                    const errorEl = document.getElementById(`${field}_error`);
                    if (errorEl) {
                        errorEl.textContent = message;
                        errorEl.classList.remove('hidden');
                    }
                }

                formatFieldName(field) {
                    return field.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                }

                async saveParty(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (!this.validateForm()) return;

                    const formData = new FormData(this.form);

                    if (this.saveBtn) {
                        this.saveBtn.disabled = true;
                        this.saveBtn.textContent = 'Saving...';
                    }

                    try {
                        const response = await fetch(saveClientUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            // Display Laravel validation errors clearly
                            if (response.status === 422 && data.errors) {
                                this.displayServerErrors(data.errors);

                                // Combine all error messages for alert
                                const messages = Object.values(data.errors).flat();
                                alert(messages.join('\n'));
                                return;
                            }

                            // Other server errors
                            throw new Error(data.message || "Server error occurred");
                        }

                        if (data.client && this.clientSelect) {
                            let exists = Array.from(this.clientSelect.options).some(opt => opt.value == data.client.id);
                            if (!exists) {
                                const option = new Option(
                                    `${data.client.business_name} - ${data.client.name || ""}`,
                                    data.client.id,
                                    true,
                                    true
                                );
                                this.clientSelect.add(option);
                            }
                            this.clientSelect.value = data.client.id;
                            this.isSavingClient = false;
                        }

                        this.hideModal();
                        alert("Client saved successfully!");

                    } catch (err) {
                        console.error("Error saving party:", err);
                        alert(err.message || "An unexpected error occurred while saving the party.");
                    } finally {
                        if (this.saveBtn) {
                            this.saveBtn.disabled = false;
                            this.saveBtn.textContent = "Save Party";
                        }
                    }
                }

                displayServerErrors(errors) {
                    this.clearErrors();
                    Object.keys(errors).forEach(field => {
                        this.showError(field, errors[field][0]);
                    });
                }
            }

            // Initialize
            document.addEventListener('DOMContentLoaded', () => {
                window.partyModal = new PartyModal();
            });
        </script>




        <script>
            class QuotationForm {
                constructor() {
                    this.productRowIndex = 1;
                    this.currentProductCount = 1;
                    this.partyModal = new PartyModal(this);
                    this.variationCounts = new Map(); // Track variation counts per product row
                    this.init();
                }

                init() {
                    this.formatCurrency = (amount) => `₹${parseFloat(amount || 0).toFixed(2)}`;
                    this.initializeEventListeners();
                    this.updateTaxLabel();
                    this.calculateTotals();
                    this.updateButtonStates();
                    console.log('Quotation form initialized');
                }

                calculateTotals() {
                    let subtotal = 0;
                    let hasValidProducts = false;
                    let validRows = 0;

                    document.querySelectorAll('.product-row').forEach((row, rowIndex) => {
                        const productSelect = row.querySelector('.product-id');
                        const productId = productSelect ? productSelect.value : '';

                        row.classList.remove('valid', 'warning', 'error');

                        if (!productId) {
                            this.clearRowQuantities(row);
                            return;
                        }

                        let rowTotalQty = 0;
                        let hasQuantities = false;

                        const variationsTable = row.querySelector('.variations-table');
                        if (variationsTable && !variationsTable.classList.contains('hidden')) {
                            const tbody = row.querySelector('tbody');
                            if (tbody && tbody.children.length > 0) {
                                tbody.querySelectorAll('tr').forEach(tr => {
                                    let totalQty = 0;
                                    tr.querySelectorAll('.size-qty').forEach(input => {
                                        const qty = parseFloat(input.value) || 0;
                                        totalQty += qty;
                                    });

                                    const totalCell = tr.querySelector('.total-size-qty');
                                    if (totalCell) {
                                        totalCell.textContent = totalQty;
                                        totalCell.classList.toggle('text-green-600', totalQty > 0);
                                        totalCell.classList.toggle('text-gray-400', totalQty === 0);
                                    }

                                    rowTotalQty += totalQty;
                                    if (totalQty > 0) hasQuantities = true;
                                });
                            }
                        } else {
                            row.querySelectorAll('.size-qty').forEach(input => {
                                const qty = parseFloat(input.value) || 0;
                                rowTotalQty += qty;
                                if (qty > 0) hasQuantities = true;
                            });
                        }

                        this.updateQuantityInput(row, rowIndex, rowTotalQty);

                        if (productId && hasQuantities && rowTotalQty > 0) {
                            row.classList.add('valid');
                            hasValidProducts = true;
                            validRows++;
                        } else if (productId && !hasQuantities) {
                            row.classList.add('warning');
                        }

                        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
                        subtotal += rowTotalQty * unitPrice;
                    });

                    const taxType = document.getElementById('tax-type')?.value || 'cgst';
                    const taxRate = 0.5;
                    const tax = subtotal * taxRate;
                    const grandTotal = subtotal + tax;

                    this.updateDisplayElements(subtotal, tax, grandTotal);
                    this.updateHiddenInputs(subtotal, tax, grandTotal);
                    this.updateButtonStates(validRows);

                    return { subtotal, tax, grandTotal, validRows };
                }

                clearRowQuantities(row) {
                    const qtyInput = row.querySelector('.product-total-quantity');
                    if (qtyInput) qtyInput.value = 0;
                    row.querySelectorAll('.size-qty').forEach(input => input.value = 0);
                }

                updateQuantityInput(row, rowIndex, quantity) {
                    let qtyInput = row.querySelector('.product-total-quantity');
                    if (!qtyInput) {
                        qtyInput = document.createElement('input');
                        qtyInput.type = 'hidden';
                        qtyInput.className = 'product-total-quantity';
                        row.appendChild(qtyInput);
                    }
                    qtyInput.name = `products[${rowIndex}][quantity]`;
                    qtyInput.value = Math.max(0, Math.round(quantity));
                }

                updateDisplayElements(subtotal, tax, grandTotal) {
                    document.getElementById('subtotal').textContent = this.formatCurrency(subtotal);
                    document.getElementById('tax').textContent = this.formatCurrency(tax);
                    document.getElementById('grand-total').textContent = this.formatCurrency(grandTotal);
                }

                updateHiddenInputs(subtotal, tax, grandTotal) {
                    document.getElementById('subtotal-input').value = subtotal.toFixed(2);
                    document.getElementById('tax-input').value = tax.toFixed(2);
                    document.getElementById('grand-total-input').value = grandTotal.toFixed(2);
                }

                updateButtonStates(validRows = 0) {
                    const submitBtn = document.getElementById('submit-btn');
                    const clientSelect = document.querySelector('#client_id');
                    const hasClient = clientSelect && clientSelect.value;
                    const isFormValid = hasClient && validRows > 0;

                    if (submitBtn) {
                        submitBtn.disabled = !isFormValid;
                        submitBtn.classList.toggle('loading', false);

                        let iconPath, buttonText, variant;

                        if (!hasClient) {
                            iconPath = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.464-1.333-3.233 0L3.04 16c-.77 1.333.192 3 1.732 3z';
                            buttonText = 'Select Client First';
                            variant = 'warning';
                        } else if (validRows === 0) {
                            iconPath = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.464-1.333-3.233 0L3.04 16c-.77 1.333.192 3 1.732 3z';
                            buttonText = 'Add Products First';
                            variant = 'warning';
                        } else {
                            iconPath = 'M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4';
                            buttonText = 'Create Quotation';
                            variant = 'primary';
                        }

                        this.updateButtonAppearance(submitBtn, variant, buttonText, iconPath);
                    }

                    this.updateRemoveButtons();
                    this.updateVariationRemoveButtons();
                }

                updateButtonAppearance(button, variant, text, iconPath) {
                    button.className = `create-btn btn btn-${variant}`;

                    const svg = button.querySelector('svg path');
                    if (svg && iconPath) {
                        svg.setAttribute('d', iconPath);
                    }

                    const currentText = button.innerHTML.replace(/<svg.*?<\/svg>/, '').trim();
                    if (currentText !== text) {
                        button.innerHTML = button.innerHTML.replace(currentText, text);
                    }
                }

                updateTaxLabel() {
                    const taxType = document.getElementById('tax-type')?.value || 'cgst';
                    const taxLabel = document.getElementById('tax-label');

                    if (taxLabel) {
                        taxLabel.textContent = taxType === 'cgst'
                            ? 'Tax (CGST/SGST 2.5% + 2.5%)'
                            : 'Tax (IGST 18%)';
                    }
                }

                initializeProductRow(row, index) {
                    row.querySelectorAll('input, select').forEach(el => {
                        if (!el.classList.contains('product-total-quantity')) {
                            let name = el.name;
                            if (name) {
                                name = name.replace(/\[(\d+)\]/g, `[${index}]`);
                                el.name = name;
                            }
                        }
                    });

                    let qtyInput = row.querySelector('.product-total-quantity');
                    if (!qtyInput) {
                        qtyInput = document.createElement('input');
                        qtyInput.type = 'hidden';
                        qtyInput.className = 'product-total-quantity';
                        row.appendChild(qtyInput);
                    }
                    qtyInput.name = `products[${index}][quantity]`;
                    qtyInput.value = 0;

                    const articleNo = row.querySelector('.article-no');
                    const unitPrice = row.querySelector('.unit-price');
                    const productSelect = row.querySelector('.product-id');
                    const variationsTable = row.querySelector('.variations-table');

                    if (articleNo) articleNo.value = '';
                    if (unitPrice) unitPrice.value = '';
                    if (productSelect) productSelect.selectedIndex = 0;
                    if (variationsTable) {
                        variationsTable.querySelector('tbody').innerHTML = '';
                        variationsTable.classList.add('hidden');
                    }

                    const title = row.querySelector('h3');
                    if (title) {
                        const titleId = title.id || `product-title-${index}`;
                        title.id = titleId;
                        title.textContent = `Article ${index + 1}`;
                    }

                    row.dataset.rowIndex = index;
                    row.classList.remove('valid', 'warning', 'error', 'adding');

                    // Initialize variation count for this product row
                    this.variationCounts.set(index, 0);

                    return row;
                }

                handleProductSelection(event) {
                    const row = event.target.closest('.product-row');
                    const selectedOption = event.target.selectedOptions[0];
                    const tbody = row.querySelector('.variations-table tbody');
                    const rowIndex = parseInt(row.dataset.rowIndex);

                    if (tbody) tbody.innerHTML = '';

                    const articleNoInput = row.querySelector('.article-no');
                    const unitPriceInput = row.querySelector('.unit-price');

                    if (articleNoInput) {
                        articleNoInput.value = selectedOption.dataset.article || '';
                    }

                    if (unitPriceInput) {
                        const price = parseFloat(selectedOption.dataset.price || 0);
                        unitPriceInput.value = price.toFixed(2);
                    }

                    let variationsData = [];
                    try {
                        variationsData = selectedOption.dataset.variations ?
                            JSON.parse(selectedOption.dataset.variations) : [];
                    } catch (error) {
                        console.error('Error parsing variations data:', error);
                        variationsData = [];
                    }

                    const variationsTable = row.querySelector('.variations-table');

                    this.variationCounts.set(rowIndex, variationsData.length || 1);

                    if (variationsData.length > 0) {
                        variationsData.forEach((variation, vIndex) => {
                            const rowElement = this.createVariationRow(rowIndex, vIndex, variation);
                            tbody.appendChild(rowElement);
                        });
                        variationsTable.classList.remove('hidden');
                    } else {
                        const simpleRow = this.createSimpleSizeRow(rowIndex);
                        tbody.appendChild(simpleRow);
                        variationsTable.classList.remove('hidden');
                    }

                    this.updateVariationRemoveButtons();
                    this.calculateTotals();
                }

                createVariationRow(rowIndex, variationIndex, variation = { color: '' }) {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50 transition-colors variation-row';
                    tr.dataset.variationIndex = variationIndex;

                    const colorTd = document.createElement('td');
                    colorTd.className = 'border border-gray-300 px-3 py-3 align-top';

                    const colorInput = document.createElement('input');
                    colorInput.type = 'text';
                    colorInput.value = variation.color || `Color ${variationIndex + 1}`;
                    colorInput.name = `products[${rowIndex}][variations][${variationIndex}][color]`;
                    colorInput.className = 'color-input w-full px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm';
                    colorInput.placeholder = 'Enter color name';
                    colorTd.appendChild(colorInput);
                    tr.appendChild(colorTd);

                    for (let size = 35; size <= 44; size++) {
                        const sizeTd = document.createElement('td');
                        sizeTd.className = 'border border-gray-300 px-2 py-3 text-center';

                        const sizeInput = document.createElement('input');
                        sizeInput.type = 'number';
                        sizeInput.min = 0;
                        sizeInput.value = 0;
                        sizeInput.name = `products[${rowIndex}][variations][${variationIndex}][sizes][${size}]`;
                        sizeInput.className = 'size-qty w-16 h-12 px-2 text-center border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm';
                        sizeInput.addEventListener('input', () => {
                            this.updateRowTotal(tr);
                            this.calculateTotals();
                        });

                        sizeTd.appendChild(sizeInput);
                        tr.appendChild(sizeTd);
                    }

                    const totalTd = document.createElement('td');
                    totalTd.className = 'border border-gray-300 px-3 py-3 text-right font-semibold text-sm total-size-qty bg-blue-50';
                    totalTd.textContent = '0';
                    tr.appendChild(totalTd);

                    const actionTd = document.createElement('td');
                    actionTd.className = 'border border-gray-300 px-3 py-3 text-right';
                    const deleteBtn = document.createElement('button');
                    deleteBtn.type = 'button';
                    deleteBtn.className = 'remove-variation-btn remove-product-btn';
                    deleteBtn.innerHTML = `
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Remove
                `;
                    actionTd.appendChild(deleteBtn);
                    tr.appendChild(actionTd);

                    return tr;
                }

                createSimpleSizeRow(rowIndex) {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50 transition-colors variation-row';
                    tr.dataset.variationIndex = 0;

                    const colorTd = document.createElement('td');
                    colorTd.className = 'border border-gray-300 px-3 py-3 align-middle text-sm font-medium';
                    colorTd.textContent = 'Standard';
                    tr.appendChild(colorTd);

                    for (let size = 35; size <= 44; size++) {
                        const sizeTd = document.createElement('td');
                        sizeTd.className = 'border border-gray-300 px-2 py-3 text-center';

                        const sizeInput = document.createElement('input');
                        sizeInput.type = 'number';
                        sizeInput.min = 0;
                        sizeInput.value = 0;
                        sizeInput.name = `products[${rowIndex}][sizes][${size}]`;
                        sizeInput.className = 'size-qty w-16 h-12 px-2 text-center border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm';
                        sizeInput.addEventListener('input', () => {
                            this.updateRowTotal(tr);
                            this.calculateTotals();
                        });

                        sizeTd.appendChild(sizeInput);
                        tr.appendChild(sizeTd);
                    }

                    const totalTd = document.createElement('td');
                    totalTd.className = 'border border-gray-300 px-3 py-3 text-right font-semibold text-sm total-size-qty bg-blue-50';
                    totalTd.textContent = '0';
                    tr.appendChild(totalTd);

                    const actionTd = document.createElement('td');
                    actionTd.className = 'border border-gray-300 px-3 py-3 text-right';
                    const deleteBtn = document.createElement('button');
                    deleteBtn.type = 'button';
                    deleteBtn.className = 'remove-variation-btn remove-product-btn';
                    deleteBtn.innerHTML = `
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Remove
                `;
                    actionTd.appendChild(deleteBtn);
                    tr.appendChild(actionTd);

                    return tr;
                }

                updateRowTotal(rowElement) {
                    let totalQty = 0;
                    rowElement.querySelectorAll('.size-qty').forEach(input => {
                        totalQty += parseFloat(input.value) || 0;
                    });

                    const totalCell = rowElement.querySelector('.total-size-qty');
                    if (totalCell) {
                        totalCell.textContent = totalQty;
                        totalCell.classList.toggle('text-green-600', totalQty > 0);
                        totalCell.classList.toggle('text-gray-400', totalQty === 0);
                    }
                }

                addProductRow() {
                    const container = document.getElementById('products-container');
                    const templateRow = document.querySelector('.product-row');

                    if (!templateRow || !container) return;

                    const newRow = templateRow.cloneNode(true);
                    const newIndex = this.productRowIndex;

                    this.initializeProductRow(newRow, newIndex);

                    newRow.classList.add('adding');
                    newRow.style.opacity = '0';
                    newRow.style.transform = 'translateY(-10px)';

                    container.appendChild(newRow);

                    requestAnimationFrame(() => {
                        newRow.classList.remove('adding');
                        newRow.style.opacity = '1';
                        newRow.style.transform = 'translateY(0)';
                        newRow.style.transition = 'all 0.3s ease';
                    });

                    this.currentProductCount++;
                    this.productRowIndex++;

                    this.updateRemoveButtons();
                    this.calculateTotals();

                    setTimeout(() => {
                        const newProductSelect = newRow.querySelector('.product-id');
                        if (newProductSelect) {
                            newProductSelect.focus();
                        }
                    }, 300);
                }

                removeProductRow(event) {
                    const removeBtn = event.target.closest('.remove-product-row');
                    if (!removeBtn) return;

                    const row = removeBtn.closest('.product-row');
                    const rows = document.querySelectorAll('.product-row');

                    if (rows.length <= 1) {
                        console.log('Cannot remove last row');
                        return;
                    }

                    row.style.transition = 'all 0.3s ease';
                    row.classList.add('removing');
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';

                    setTimeout(() => {
                        if (row.parentNode) {
                            row.parentNode.removeChild(row);
                        }

                        this.variationCounts.delete(parseInt(row.dataset.rowIndex));

                        const remainingRows = document.querySelectorAll('.product-row');
                        remainingRows.forEach((remainingRow, index) => {
                            this.initializeProductRow(remainingRow, index);
                        });

                        this.currentProductCount--;
                        this.productRowIndex = Math.max(1, this.currentProductCount);

                        this.updateRemoveButtons();
                        this.calculateTotals();
                    }, 300);
                }

                addVariationRow(event) {
                    const addBtn = event.target.closest('.add-variation-btn');
                    if (!addBtn) return;

                    const productRow = addBtn.closest('.product-row');
                    const rowIndex = parseInt(productRow.dataset.rowIndex);
                    const tbody = productRow.querySelector('.variations-table tbody');

                    const variationIndex = this.variationCounts.get(rowIndex) || 0;

                    const newRow = this.createVariationRow(rowIndex, variationIndex);
                    newRow.classList.add('adding');
                    newRow.style.opacity = '0';
                    newRow.style.transform = 'translateY(-10px)';

                    tbody.appendChild(newRow);

                    requestAnimationFrame(() => {
                        newRow.classList.remove('adding');
                        newRow.style.opacity = '1';
                        newRow.style.transform = 'translateY(0)';
                        newRow.style.transition = 'all 0.3s ease';
                    });

                    this.variationCounts.set(rowIndex, variationIndex + 1);
                    this.updateVariationRemoveButtons();
                    this.calculateTotals();

                    setTimeout(() => {
                        const colorInput = newRow.querySelector('.color-input');
                        if (colorInput) colorInput.focus();
                    }, 300);
                }

                removeVariationRow(event) {
                    const removeBtn = event.target.closest('.remove-variation-btn');
                    if (!removeBtn) return;

                    const variationRow = removeBtn.closest('.variation-row');
                    const productRow = variationRow.closest('.product-row');
                    const rowIndex = parseInt(productRow.dataset.rowIndex);
                    const tbody = productRow.querySelector('tbody');

                    if (tbody.children.length <= 1) {
                        console.log('Cannot remove last variation row');
                        return;
                    }

                    variationRow.style.transition = 'all 0.3s ease';
                    variationRow.classList.add('removing');
                    variationRow.style.opacity = '0';
                    variationRow.style.transform = 'translateX(20px)';

                    setTimeout(() => {
                        if (variationRow.parentNode) {
                            variationRow.parentNode.removeChild(variationRow);
                        }

                        // Reindex variation rows
                        const remainingRows = tbody.querySelectorAll('.variation-row');
                        remainingRows.forEach((row, vIndex) => {
                            row.dataset.variationIndex = vIndex;
                            const colorInput = row.querySelector('.color-input');
                            if (colorInput) {
                                colorInput.name = `products[${rowIndex}][variations][${vIndex}][color]`;
                            }
                            row.querySelectorAll('.size-qty').forEach(input => {
                                const size = input.name.match(/\[sizes\]\[(\d+)\]/)?.[1];
                                if (size) {
                                    input.name = `products[${rowIndex}][variations][${vIndex}][sizes][${size}]`;
                                }
                            });
                        });

                        this.variationCounts.set(rowIndex, remainingRows.length);
                        this.updateVariationRemoveButtons();
                        this.calculateTotals();
                    }, 300);
                }

                updateRemoveButtons() {
                    const rows = document.querySelectorAll('.product-row');
                    const buttons = document.querySelectorAll('.remove-product-row');

                    buttons.forEach((btn) => {
                        const shouldEnable = rows.length > 1;
                        btn.disabled = !shouldEnable;
                        btn.setAttribute('aria-disabled', !shouldEnable.toString());

                        if (shouldEnable) {
                            btn.classList.remove('opacity-50', 'cursor-not-allowed');
                            btn.classList.add('opacity-100');
                            btn.style.opacity = '1';
                            btn.style.cursor = 'pointer';
                        } else {
                            btn.classList.add('opacity-50', 'cursor-not-allowed');
                            btn.style.opacity = '0.5';
                            btn.style.cursor = 'not-allowed';
                        }
                    });
                }

                updateVariationRemoveButtons() {
                    document.querySelectorAll('.product-row').forEach(row => {
                        const tbody = row.querySelector('tbody');
                        const buttons = row.querySelectorAll('.remove-variation-btn');
                        const shouldEnable = tbody.children.length > 1;

                        buttons.forEach(btn => {
                            btn.disabled = !shouldEnable;
                            btn.setAttribute('aria-disabled', !shouldEnable.toString());

                            if (shouldEnable) {
                                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                                btn.classList.add('opacity-100');
                                btn.style.opacity = '1';
                                btn.style.cursor = 'pointer';
                            } else {
                                btn.classList.add('opacity-50', 'cursor-not-allowed');
                                btn.style.opacity = '0.5';
                                btn.style.cursor = 'not-allowed';
                            }
                        });
                    });
                }

                initializeEventListeners() {
                    document.addEventListener('change', (e) => {
                        if (e.target.classList.contains('product-id')) {
                            this.handleProductSelection(e);
                        }
                    });

                    document.addEventListener('change', (e) => {
                        if (e.target.id === 'tax-type') {
                            this.updateTaxLabel();
                            this.calculateTotals();
                        }
                    });

                    document.addEventListener('change', (e) => {
                        if (e.target.id === 'client_id') {
                            if (e.target.value === 'add-new') {
                                this.partyModal.showAddPartyModal();
                            } else {
                                this.calculateTotals();
                            }
                        }
                    });

                    document.addEventListener('input', (e) => {
                        if (e.target.classList.contains('size-qty') ||
                            e.target.classList.contains('unit-price')) {
                            this.calculateTotals();
                        }
                    });

                    const addBtn = document.getElementById('add-product-row');
                    if (addBtn) {
                        addBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            this.addProductRow();

                            const originalTransform = addBtn.style.transform;
                            addBtn.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                addBtn.style.transform = originalTransform || '';
                            }, 150);
                        });
                    }

                    document.addEventListener('click', (e) => {
                        const removeProductBtn = e.target.closest('.remove-product-row');
                        const addVariationBtn = e.target.closest('.add-variation-btn');
                        const removeVariationBtn = e.target.closest('.remove-variation-btn');

                        if (removeProductBtn && !removeProductBtn.disabled) {
                            e.preventDefault();
                            this.removeProductRow(e);
                        }

                        if (addVariationBtn) {
                            e.preventDefault();
                            this.addVariationRow(e);

                            const originalTransform = addVariationBtn.style.transform;
                            addVariationBtn.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                addVariationBtn.style.transform = originalTransform || '';
                            }, 150);
                        }

                        if (removeVariationBtn && !removeVariationBtn.disabled) {
                            e.preventDefault();
                            this.removeVariationRow(e);
                        }
                    });

                    const form = document.getElementById('quotation-form');
                    if (form) {
                        form.addEventListener('submit', (e) => {
                            // If a new client is being added, block the quotation submit
                            if (window.partyModal && window.partyModal.isSavingClient) {
                                e.preventDefault();
                                alert('Please save the new client before submitting the quotation.');
                                return;
                            }

                            const submitBtn = document.getElementById('submit-btn');
                            if (submitBtn && !submitBtn.disabled) {
                                e.preventDefault();
                                this.setLoadingState(submitBtn, true);
                                // Use setTimeout to avoid infinite recursion
                                setTimeout(() => form.submit(), 0);
                            }
                        });
                    }


                    document.querySelectorAll('.product-row').forEach((row, index) => {
                        this.initializeProductRow(row, index);
                    });

                    this.updateRemoveButtons();
                    this.updateVariationRemoveButtons();
                }

                setLoadingState(button, loading = true) {
                    if (loading) {
                        button.classList.add('loading');
                        button.disabled = true;
                    } else {
                        button.classList.remove('loading');
                        setTimeout(() => {
                            button.disabled = false;
                        }, 500);
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                new QuotationForm();
            });
        </script>
@endsection