@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ __('Warehouses') }}</h1>
            <a href="{{ route('inventory.warehouses.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('Add New Warehouse') }}
            </a>
        </div>

        <form method="GET" action="{{ route('inventory.warehouses') }}" class="mb-6">
            <div class="flex items-center space-x-4">
                <input type="text" name="search" value="{{ request()->query('search') }}" placeholder="{{ __('Search by name or location...') }}" class="border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 w-64">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    {{ __('Search') }}
                </button>
            </div>
        </form>

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

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Name') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Location') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Description') }}</th>
                        <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($warehouses as $warehouse)
                        <tr class="hover:bg-gray-50">
                            <td class="border p-3">{{ $warehouse->name }}</td>
                            <td class="border p-3">{{ $warehouse->location }}</td>
                            <td class="border p-3">{{ $warehouse->description ?? 'N/A' }}</td>
                            <td class="border p-3">
                                <div class="flex space-x-2">
                                    <a href="{{ route('inventory.warehouses.edit', $warehouse) }}" class="text-blue-600 hover:text-blue-800">{{ __('Edit') }}</a>
                                    <form action="{{ route('inventory.warehouses.destroy', $warehouse) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this warehouse?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="border p-3 text-center text-gray-600">{{ __('No warehouses found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $warehouses->links() }}
        </div>
    </div>
@endsection