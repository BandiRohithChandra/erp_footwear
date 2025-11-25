@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Edit User Profile') }}</h1>

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

        <form method="POST" action="{{ route('users.update', $user) }}" class="bg-white p-6 rounded-lg shadow space-y-4">
            @csrf
            @method('PUT')
            <div id="saudi_fields" class="hidden">
                <div>
                    <label for="iqama_number" class="block text-gray-700 font-medium mb-2">{{ __('Iqama Number') }}</label>
                    <input type="text" id="iqama_number" name="iqama_number" value="{{ old('iqama_number', $user->iqama_number) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('iqama_number') border-red-500 @enderror">
                    @error('iqama_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="iqama_expiry_date" class="block text-gray-700 font-medium mb-2">{{ __('Iqama Expiry Date') }}</label>
                    <input type="date" id="iqama_expiry_date" name="iqama_expiry_date" value="{{ old('iqama_expiry_date', $user->iqama_expiry_date ? $user->iqama_expiry_date->format('Y-m-d') : '') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('iqama_expiry_date') border-red-500 @enderror">
                    @error('iqama_expiry_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="health_card_number" class="block text-gray-700 font-medium mb-2">{{ __('Health Card Number') }}</label>
                    <input type="text" id="health_card_number" name="health_card_number" value="{{ old('health_card_number', $user->health_card_number) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('health_card_number') border-red-500 @enderror">
                    @error('health_card_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                {{ __('Update Profile') }}
            </button>
        </form>
    </div>

    <script>
        function toggleSaudiFields() {
            fetch('/api/settings/region', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                const region = data.region;
                const saudiFields = document.getElementById('saudi_fields');
                if (region === 'sa') { // Update to match 'sa'
                    saudiFields.classList.remove('hidden');
                    document.getElementById('iqama_number').required = true;
                    document.getElementById('iqama_expiry_date').required = true;
                    document.getElementById('health_card_number').required = true;
                } else {
                    saudiFields.classList.add('hidden');
                    document.getElementById('iqama_number').required = false;
                    document.getElementById('iqama_expiry_date').required = false;
                    document.getElementById('health_card_number').required = false;
                }
            })
            .catch(error => console.error('Error fetching region:', error));
        }

        // Call toggleSaudiFields on page load
        document.addEventListener('DOMContentLoaded', toggleSaudiFields);
    </script>
@endsection