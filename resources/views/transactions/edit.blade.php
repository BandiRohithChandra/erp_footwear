@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">{{ __('Edit Transaction') }}</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                    <input type="text" name="description" id="description" value="{{ old('description', $transaction->description) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">{{ __('Type') }}</label>
                    <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                        <option value="income" {{ old('type', $transaction->type) === 'income' ? 'selected' : '' }}>{{ __('Income') }}</option>
                        <option value="expense" {{ old('type', $transaction->type) === 'expense' ? 'selected' : '' }}>{{ __('Expense') }}</option>
                    </select>
                    @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">{{ __('Category') }}</label>
                    <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('Select Category') }}</option>
                        <option value="salary" {{ old('category', $transaction->category) === 'salary' ? 'selected' : '' }}>{{ __('Salary') }}</option>
                        <option value="purchase" {{ old('category', $transaction->category) === 'purchase' ? 'selected' : '' }}>{{ __('Purchase') }}</option>
                        <option value="other" {{ old('category', $transaction->category) === 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                    </select>
                    @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">{{ __('Amount') }}</label>
                    <input type="number" name="amount" id="amount" step="0.01" value="{{ old('amount', $transaction->amount) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="tax_rate_select" class="block text-sm font-medium text-gray-700">{{ __('Tax Rate (%)') }}</label>
                    <select name="tax_rate_select" id="tax_rate_select" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" onchange="toggleCustomTax(this)" required>
                        <option value="0" {{ $transaction->tax_rate == 0 ? 'selected' : '' }}>{{ __('Select Tax Rate') }}</option>
                        <option value="0" {{ $transaction->tax_rate == 0 ? 'selected' : '' }}>{{ __('Tax Exempt') }}</option>
                        <option value="5" {{ $transaction->tax_rate == 5 ? 'selected' : '' }}>5%</option>
                        <option value="15" {{ $transaction->tax_rate == 15 ? 'selected' : '' }}>15% ({{ __('VAT') }})</option>
                        <option value="18" {{ $transaction->tax_rate == 18 ? 'selected' : '' }}>18% ({{ __('GST') }})</option>
                        <option value="20" {{ $transaction->tax_rate == 20 ? 'selected' : '' }}>20%</option>
                        <option value="custom" {{ !in_array($transaction->tax_rate, [0, 5, 15, 18, 20]) ? 'selected' : '' }}>{{ __('Custom') }}</option>
                    </select>
                    @error('tax_rate_select') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="custom_tax_container" class="{{ !in_array($transaction->tax_rate, [0, 5, 15, 18, 20]) ? '' : 'hidden' }}">
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700">{{ __('Custom Tax Rate (%)') }}</label>
                    <input type="number" name="tax_rate" id="tax_rate" step="0.01" value="{{ old('tax_rate', $transaction->tax_rate) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    @error('tax_rate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="transaction_date" class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                    <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('transaction_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ __('Update Transaction') }}
            </button>
        </form>
    </div>

    <script>
        function toggleCustomTax(select) {
            const customTaxContainer = document.getElementById('custom_tax_container');
            customTaxContainer.classList.toggle('hidden', select.value !== 'custom');
            document.getElementById('tax_rate').required = select.value === 'custom';
        }
    </script>
@endsection