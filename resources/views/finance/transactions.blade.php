@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Finance Transactions') }}</h1>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Create Transaction Form -->
        @can('create transactions')
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Create New Transaction') }}</h2>
                <form action="{{ route('finance.transactions.create') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">{{ __('Type') }}</label>
                            <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="payable">{{ __('Payable') }}</option>
                                <option value="receivable">{{ __('Receivable') }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">{{ __('Category') }}</label>
                            <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="salary">{{ __('Salary') }}</option>
                                <option value="purchase">{{ __('Purchase') }}</option>
                                <option value="other">{{ __('Other') }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">{{ __('Amount') }}</label>
                            <input type="number" name="amount" id="amount" step="0.01" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Create Transaction') }}</button>
                </form>
            </div>
        @endcan

        <!-- Filter Form -->
        <div class="mb-6">
            <form method="GET" action="{{ route('finance.transactions') }}" class="flex space-x-4">
                <div>
                    <label for="category_filter" class="block text-sm font-medium text-gray-700">{{ __('Filter by Category') }}</label>
                    <select name="category" id="category_filter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('All') }}</option>
                        <option value="salary" {{ request('category') == 'salary' ? 'selected' : '' }}>{{ __('Salary') }}</option>
                        <option value="purchase" {{ request('category') == 'purchase' ? 'selected' : '' }}>{{ __('Purchase') }}</option>
                        <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">{{ __('Filter') }}</button>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ID') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Category') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Amount') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Approved By') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Approved At') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Created At') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($transaction->type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($transaction->category) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ \App\Helpers\FormatMoney::format($transaction->amount) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $transaction->status == 'approved' ? 'bg-green-100 text-green-800' : ($transaction->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->approvedBy ? $transaction->approvedBy->name : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->approved_at ? $transaction->approved_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @can('approve transactions')
                                    @if ($transaction->status == 'pending')
                                        <form action="{{ route('finance.transactions.approve', $transaction) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 mr-2">{{ __('Approve') }}</button>
                                        </form>
                                        <form action="{{ route('finance.transactions.reject', $transaction) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-800">{{ __('Reject') }}</button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">{{ __('No transactions found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection