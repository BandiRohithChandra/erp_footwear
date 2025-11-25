<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Bulk Payroll') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('payrolls.store-bulk') }}">
                        @csrf

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

                        <!-- Employees Table -->
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Select Employees') }}</h3>
                            <table class="min-w-full divide-y divide-gray-200 mt-4">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Select') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Employee') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($employees as $index => $employee)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="employees[{{ $index }}][employee_id]" value="{{ $employee->id }}" class="rounded">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $employee->name }} ({{ $employee->position }})</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" step="0.01" name="employees[{{ $index }}][amount]" class="border-gray-300 rounded-md shadow-sm w-32" placeholder="0.00">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @error('employees')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-white hover:bg-blue-600">
                                {{ __('Create Bulk Payroll') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            let checked = document.querySelectorAll('input[type="checkbox"]:checked');
            if (checked.length === 0) {
                e.preventDefault();
                alert('Please select at least one employee.');
            }
        });
    </script>
</x-app-layout>