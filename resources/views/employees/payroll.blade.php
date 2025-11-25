@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="mb-6 flex items-center">
            <a href="{{ url()->previous() }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {{ __('Back') }}
            </a>
        </div>
        <h1 class="text-2xl font-bold mb-6">{{ __('Payroll for') }} {{ $employee->name }}</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                {{ session('success') }}
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

        <form method="POST" action="{{ route('employees.payroll.store', $employee) }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">{{ __('Amount') }}</label>
                    <input type="number" name="amount" id="amount" step="0.01" value="{{ old('amount') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700">{{ __('Payment Date') }}</label>
                    <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('payment_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="tax_rate_select" class="block text-sm font-medium text-gray-700">{{ __('Tax Rate (%)') }}</label>
                    <select name="tax_rate_select" id="tax_rate_select" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" onchange="toggleCustomTax(this)" required>
                        <option value="0">{{ __('Select Tax Rate') }}</option>
                        <option value="0">{{ __('Tax Exempt') }}</option>
                        <option value="5">5%</option>
                        <option value="15">15% ({{ __('VAT') }})</option>
                        <option value="18">18% ({{ __('GST') }})</option>
                        <option value="20">20%</option>
                        <option value="custom">{{ __('Custom') }}</option>
                    </select>
                    @error('tax_rate_select') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="custom_tax_container" class="hidden">
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700">{{ __('Custom Tax Rate (%)') }}</label>
                    <input type="number" name="tax_rate" id="tax_rate" step="0.01" value="{{ old('tax_rate') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    @error('tax_rate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                    <textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('Add Payroll') }}
            </button>
        </form>

        <div class="mt-6">
            <h2 class="text-xl font-semibold mb-4">{{ __('Payroll History') }}</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Amount') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Tax Rate') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Tax Amount') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Total Amount') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Payment Date') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Description') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payrolls as $payroll)
                            <tr class="hover:bg-gray-50">
                                <td class="border p-3">{{ \App\Helpers\FormatMoney::format($payroll->amount) }}</td>
                                <td class="border p-3">{{ $payroll->tax_rate ? $payroll->tax_rate . '%' : 'N/A' }}</td>
                                <td class="border p-3">{{ \App\Helpers\FormatMoney::format($payroll->tax_amount) }}</td>
                                <td class="border p-3">{{ \App\Helpers\FormatMoney::format($payroll->total_amount) }}</td>
                                <td class="border p-3">{{ $payroll->payment_date }}</td>
                                <td class="border p-3">{{ $payroll->description ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="border p-3 text-center text-gray-600">{{ __('No payroll records found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleCustomTax(select) {
            const customTaxContainer = document.getElementById('custom_tax_container');
            customTaxContainer.classList.toggle('hidden', select.value !== 'custom');
            document.getElementById('tax_rate').required = select.value === 'custom';
        }
    </script>
@endsection