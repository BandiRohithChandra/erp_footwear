<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payroll Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Status Filter -->
                    <div class="mb-4">
                        <label for="status" class="mr-2">{{ __('Filter by Status') }}:</label>
                        <select id="status" onchange="location = this.value;" class="border-gray-300 rounded-md">
                            <option value="{{ route('payrolls.index', ['status' => 'pending']) }}" {{ $status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="{{ route('payrolls.index', ['status' => 'manager_approved']) }}" {{ $status == 'manager_approved' ? 'selected' : '' }}>{{ __('Manager Approved') }}</option>
                            <option value="{{ route('payrolls.index', ['status' => 'finance_approved']) }}" {{ $status == 'finance_approved' ? 'selected' : '' }}>{{ __('Finance Approved') }}</option>
                            <option value="{{ route('payrolls.index', ['status' => 'disbursed']) }}" {{ $status == 'disbursed' ? 'selected' : '' }}>{{ __('Disbursed') }}</option>
                            <option value="{{ route('payrolls.index', ['status' => 'rejected']) }}" {{ $status == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                        </select>
                    </div>

                    <!-- Create Payroll Buttons (HR Only) -->
                    @can('manage hr')
                        <div class="mb-4 space-x-2">
                            <a href="{{ route('payrolls.create') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                {{ __('Create Single Payroll') }}
                            </a>
                            <a href="{{ route('payrolls.create-bulk') }}" class="inline-block px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                {{ __('Create Bulk Payroll') }}
                            </a>
                        </div>
                    @endcan

                    <!-- Payroll List -->
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                            <!-- Manager Approval -->
                                            @can('manage hr')
                                                @if ($payroll->status == 'pending' && $payroll->employee->user && $payroll->employee->user->manager_id == auth()->id())
                                                    <form action="{{ route('payrolls.approve.manager', $payroll) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-500 hover:underline">{{ __('Approve') }}</button>
                                                    </form>
                                                    <form action="{{ route('payrolls.reject.manager', $payroll) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-red-500 hover:underline">{{ __('Reject') }}</button>
                                                    </form>
                                                @endif
                                            @endcan

                                            <!-- Finance Approval -->
                                            @can('approve transactions')
                                                @if ($payroll->status == 'manager_approved')
                                                    <form action="{{ route('payrolls.approve.finance', $payroll) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-500 hover:underline">{{ __('Approve') }}</button>
                                                    </form>
                                                    <form action="{{ route('payrolls.reject.finance', $payroll) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-red-500 hover:underline">{{ __('Reject') }}</button>
                                                    </form>
                                                @endif

                                                <!-- Disburse -->
                                                @if ($payroll->status == 'finance_approved')
                                                    <form action="{{ route('payrolls.disburse', $payroll) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-blue-500 hover:underline">{{ __('Disburse') }}</button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $payrolls->links() }}
                        </div>
                    @else
                        <p class="text-gray-600">{{ __('No payrolls found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>