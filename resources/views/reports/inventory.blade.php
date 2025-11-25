@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">{{ __('Inventory Report') }}</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('reports.inventory.export') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                {{ __('Export') }}
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ID') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Stock') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Warehouse') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->warehouse ? $product->warehouse->name : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center">{{ __('No products found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection