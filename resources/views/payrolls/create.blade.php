<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Payroll') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('payrolls.store') }}">
                        @csrf

                        <!-- Employee -->
                        <div class="mb-4">
                            <label for="employee_id" class="block text-sm font-medium text-gray-700">{{ __('Employee') }}</label>
                            <select id="employee_id" name="employee_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('employee_id') border-red-500 @enderror">
                                <option value="">{{ __('Select Employee') }}</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->position }})</option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">{{ __('Amount') }}</label>
                            <input type="number" step="0.01" id="amount" name="amount" value="{{ old('amount') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('amount') border-red-500 @enderror" required>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div class="mb-4">
                            <label for="payment_date" class="block text-sm font-medium text-gray-700">{{ __('Payment Date') }}</label>
                            <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('payment_date') border-red-500 @enderror" required>
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-white hover:bg-blue-600">
                                {{ __('Create Payroll') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>