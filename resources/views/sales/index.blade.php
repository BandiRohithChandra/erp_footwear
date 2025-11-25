@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ __('Sales') }}</h1>
            <a href="{{ route('sales.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('Add New Sale') }}
            </a>
        </div>

        <!-- Back Button -->
<a href="{{ url()->previous() }}" class="back-btn">
    <span class="back-icon">←</span> Back
</a>

<style>
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(90deg, #9747FF 0%, #92D3F5 100%);
    color: #fff;
    font-weight: 600;
    font-size: 16px;
    border-radius: 30px;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    margin-bottom: 25px;
}

.back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    background: linear-gradient(90deg, #92D3F5 0%, #9747FF 100%);
}

.back-icon {
    font-size: 18px;
}
</style>


        <form method="GET" action="{{ route('sales.index') }}" class="mb-6">
            <div class="flex items-center space-x-4">
                <input type="text" name="search" value="{{ request()->query('search') }}" placeholder="{{ __('Search by customer or product...') }}" class="border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 w-64">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    {{ __('Search') }}
                </button>
                <a href="{{ route('sales.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    {{ __('Export to CSV') }}
                </a>
            </div>
        </form>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Product') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Warehouse') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Quantity') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Unit Price') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Discount') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Tax Rate') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Tax Amount') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Total Amount') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Sale Date') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Customer Name') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Customer Email') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Notes') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="border p-3">{{ $sale->product->name }}</td>
                            <td class="border p-3">{{ $sale->warehouse->name ?? 'N/A' }}</td>
                            <td class="border p-3">{{ $sale->quantity }}</td>
                            <td class="border p-3">{{ \App\Helpers\FormatMoney::format($sale->unit_price) }}</td>
                            <td class="border p-3">{{ \App\Helpers\FormatMoney::format($sale->discount) }}</td>
                            <td class="border p-3">{{ $sale->tax_rate ? $sale->tax_rate . '%' : 'N/A' }}</td>
                            <td class="border p-3">{{ \App\Helpers\FormatMoney::format($sale->tax_amount) }}</td>
                            <td class="border p-3">{{ \App\Helpers\FormatMoney::format($sale->total_amount) }}</td>
                            <td class="border p-3">{{ $sale->sale_date }}</td>
                            <td class="border p-3">{{ $sale->customer_name }}</td>
                            <td class="border p-3">{{ $sale->customer_email ?? 'N/A' }}</td>
                            <td class="border p-3">{{ $sale->notes ?? 'N/A' }}</td>
                            <td class="border p-3">
                                <div class="flex space-x-2">
                                    <a href="{{ route('sales.edit', $sale) }}" class="text-blue-600 hover:text-blue-800">{{ __('Edit') }}</a>
                                    <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this sale?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="border p-3 text-center text-gray-600">{{ __('No sales found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $sales->links() }}
        </div>
    </div>
@endsection