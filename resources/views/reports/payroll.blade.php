<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payroll Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('reports.payroll') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">{{ __('Start Date') }}</label>
                                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">{{ __('End Date') }}</label>
                                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="manager_approved" {{ $status == 'manager_approved' ? 'selected' : '' }}>{{ __('Manager Approved') }}</option>
                                    <option value="finance_approved" {{ $status == 'finance_approved' ? 'selected' : '' }}>{{ __('Finance Approved') }}</option>
                                    <option value="disbursed" {{ $status == 'disbursed' ? 'selected' : '' }}>{{ __('Disbursed') }}</option>
                                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-white hover:bg-blue-600">
                                    {{ __('Filter') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Export Button -->
                    <form method="GET" action="{{ route('reports.payroll.export') }}" class="mb-6">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-white hover:bg-green-600">
                            {{ __('Export to CSV') }}
                        </button>
                    </form>

                    <!-- Summary -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Summary') }}</h3>
                        <p><strong>{{ __('Total Amount') }}:</strong> {{ \App\Helpers\FormatMoney::format($totalAmount) }}</p>
                        <p><strong>{{ __('Total Tax Amount') }}:</strong> {{ \App\Helpers\FormatMoney::format($totalTaxAmount) }}</p>
                        <p><strong>{{ __('Total Payable') }}:</strong> {{ \App\Helpers\FormatMoney::format($totalPayable) }}</p>
                    </div>

                    <!-- Payroll Table -->
                    @if ($payrolls->isNotEmpty())
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Employee') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Amount') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Tax Amount') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total Amount') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Payment Date') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Manager') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Finance Approver') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Disbursed At') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($payrolls as $payroll)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payroll->employee->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \App\Helpers\FormatMoney::format($payroll->amount, $payroll->region) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \App\Helpers\FormatMoney::format($payroll->tax_amount, $payroll->region) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \App\Helpers\FormatMoney::format($payroll->total_amount, $payroll->region) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payroll->payment_date->format('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst(str_replace('_', ' ', $payroll->status)) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payroll->manager ? $payroll->manager->name : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payroll->financeApprover ? $payroll->financeApprover->name : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payroll->disbursed_at ? $payroll->disbursed_at->format('d M Y H:i:s') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-600">{{ __('No payroll records found for the selected criteria.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>