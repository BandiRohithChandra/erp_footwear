@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-lg">

    <!-- Title -->
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-extrabold text-gray-800">{{ __('Financial Transactions') }}</h1>

        <div class="flex items-center gap-3">
            @can('manage finance')
                <a href="{{ route('transactions.create') }}"
                   class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Add Transaction') }}
                </a>
            @endcan

            <a href="{{ route('transactions.export') }}"
               class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-sm transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                {{ __('Export CSV') }}
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 border border-green-200" id="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <form method="GET" action="{{ route('transactions.index') }}" class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="{{ __('Search description or category...') }}"
                   class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">

            <select name="category" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('All Categories') }}</option>
                <option value="salary" {{ request('category') == 'salary' ? 'selected' : '' }}>{{ __('Salary') }}</option>
                <option value="purchase" {{ request('category') == 'purchase' ? 'selected' : '' }}>{{ __('Purchase') }}</option>
                <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
            </select>

            <select name="status" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
            </select>

            <button type="submit"
                    class="flex justify-center items-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                {{ __('Search') }}
            </button>
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 sticky top-0 z-10">
                <tr>
                    @foreach(['Description','Type','Category','Amount','Tax','Total','Region','Date','Status','Approved By','Approved At','Actions'] as $head)
                        <th class="p-3 font-semibold text-gray-700 border">{{ __($head) }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @forelse ($transactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-3">{{ $transaction->description }}</td>
                        <td class="border p-3">{{ __($transaction->type) }}</td>
                        <td class="border p-3">{{ $transaction->category ?? 'N/A' }}</td>
                        <td class="border p-3">{{ \App\Helpers\FormatMoney::format($transaction->amount, $transaction->region) }}</td>
                        <td class="border p-3">{{ \App\Helpers\FormatMoney::format($transaction->tax_amount, $transaction->region) }}</td>
                        <td class="border p-3">{{ \App\Helpers\FormatMoney::format($transaction->total_amount, $transaction->region) }}</td>
                        <td class="border p-3">
                            {{ config('taxes.regions.' . ($transaction->region ?? config('taxes.default_region', 'in')) . '.name') }}
                        </td>
                        <td class="border p-3">{{ $transaction->transaction_date->format('Y-m-d') }}</td>

                        <td class="border p-3">
                            <span class="
                                px-2 py-1 rounded-full text-xs font-semibold
                                @if($transaction->status == 'approved') bg-green-100 text-green-700
                                @elseif($transaction->status == 'rejected') bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-700 @endif
                            ">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>

                        <td class="border p-3">{{ $transaction->approvedBy->name ?? 'N/A' }}</td>
                        <td class="border p-3">{{ $transaction->approved_at ? $transaction->approved_at->format('Y-m-d H:i') : 'N/A' }}</td>

                        <td class="border p-3 space-x-2 whitespace-nowrap">
                            @can('manage finance')
                                <a href="{{ route('transactions.edit', $transaction) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('transactions.destroy', $transaction) }}"
                                      class="inline"
                                      onsubmit="return confirm('Delete this transaction?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            @endcan

                            @can('approve transactions')
                                @if($transaction->status == 'pending')
                                    <form action="{{ route('transactions.approve', $transaction) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-green-600 hover:underline">Approve</button>
                                    </form>
                                    <form action="{{ route('transactions.reject', $transaction) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-red-600 hover:underline">Reject</button>
                                    </form>
                                @endif
                            @endcan
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="12" class="text-center text-gray-600 p-4">
                            {{ __('No transactions found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $transactions->appends(request()->query())->links() }}
    </div>

</div>

<!-- Live updates -->
<script>
window.Echo.channel('transactions')
    .listen('.transaction.updated', (e) => {
        const message = `Transaction ${e.transaction.description} has been updated!`;
        const alert = document.createElement('div');
        alert.className = 'bg-blue-100 text-blue-700 p-4 rounded-lg mb-6 border border-blue-200';
        alert.textContent = message;

        document.querySelector('#success-message')?.remove();
        document.querySelector('.bg-white')?.prepend(alert);

        setTimeout(() => alert.remove(), 3000);
    });
</script>
@endsection
