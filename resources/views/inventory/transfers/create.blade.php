@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">{{ __('Record Inventory Transfer') }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('inventory.transfers.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">{{ __('Product') }}</label>
                    <select name="product_id" id="product_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                        <option value="">{{ __('Select Product') }}</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                    @error('product_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="from_warehouse_id" class="block text-sm font-medium text-gray-700">{{ __('From Warehouse') }}</label>
                    <select name="from_warehouse_id" id="from_warehouse_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                        <option value="">{{ __('Select Warehouse') }}</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('from_warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                    @error('from_warehouse_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="to_warehouse_id" class="block text-sm font-medium text-gray-700">{{ __('To Warehouse') }}</label>
                    <select name="to_warehouse_id" id="to_warehouse_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                        <option value="">{{ __('Select Warehouse') }}</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('to_warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                    @error('to_warehouse_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">{{ __('Quantity') }}</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required min="1">
                    @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="transfer_date" class="block text-sm font-medium text-gray-700">{{ __('Transfer Date') }}</label>
                    <input type="date" name="transfer_date" id="transfer_date" value="{{ old('transfer_date') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('transfer_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                    <textarea name="notes" id="notes" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                    @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('Record Transfer') }}
            </button>
        </form>
    </div>
@endsection