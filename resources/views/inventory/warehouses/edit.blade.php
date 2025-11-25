@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">{{ __('Edit Warehouse') }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('inventory.warehouses.update', $warehouse) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $warehouse->name) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">{{ __('Location') }}</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $warehouse->location) }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                    <textarea name="description" id="description" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">{{ old('description', $warehouse->description) }}</textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ __('Update Warehouse') }}
            </button>
        </form>
    </div>
@endsection