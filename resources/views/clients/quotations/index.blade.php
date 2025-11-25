@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('My Quotations') }}</h1>
    <p class="text-gray-600 mb-6">{{ __('All quotations sent to you by your sales representatives.') }}</p>

    <!-- Messages -->
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-6">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if ($quotations->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No quotations found') }}</h3>
            <p class="text-gray-500">{{ __('Quotations sent by your sales representative will appear here.') }}</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Quotation No') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Salesperson') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Subtotal') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Tax') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Grand Total') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
               <tbody class="bg-white divide-y divide-gray-200">
    @foreach ($quotations as $quotation)
       <tr onclick="window.location='{{ route('client.quotations.show', $quotation->id) }}'" class="cursor-pointer hover:bg-gray-50">

            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $quotation->quotation_no ?? 'QTN-' . str_pad($quotation->id,6,'0',STR_PAD_LEFT) }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ $quotation->salesperson?->name ?? 'N/A' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $quotation->created_at->format('M d, Y') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">₹{{ number_format($quotation->subtotal,2) }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">₹{{ number_format($quotation->tax,2) }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">₹{{ number_format($quotation->grand_total,2) }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    📧 {{ ucfirst($quotation->status) }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex flex-wrap gap-2">
                {{-- View button now optional since row is clickable --}}
                <a href="{{ route('client.quotations.show', $quotation->id) }}" 
   class="px-2 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700 transition">
    View
</a>

                @if($quotation->status === 'sent')
                    <form action="{{ route('client.quotations.accept', $quotation) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-2 py-1 text-xs text-green-700 bg-green-100 hover:bg-green-200 rounded transition">
                            ✅ Accept
                        </button>
                    </form>
                    <form action="{{ route('client.quotations.reject', $quotation) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-2 py-1 text-xs text-red-700 bg-red-100 hover:bg-red-200 rounded transition">
                            ❌ Reject
                        </button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
</tbody>

            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $quotations->links() }}
        </div>
    @endif
</div>

@endsection
