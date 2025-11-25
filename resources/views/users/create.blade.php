@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Create User') }}</h1>

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

        <form method="POST" action="{{ route('users.store') }}" class="bg-white p-6 rounded-lg shadow space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-gray-700 font-medium mb-2">{{ __('Name') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-2">{{ __('Email') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">{{ __('Password') }}</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror" required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">{{ __('Confirm Password') }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="country" class="block text-gray-700 font-medium mb-2">{{ __('Country') }}</label>
                <select id="country" name="country" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('country') border-red-500 @enderror">
                    <option value="">{{ __('Select Country') }}</option>
                    @foreach ($countries as $code => $name)
                        <option value="{{ $code }}" {{ old('country') == $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @error('country')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="manager_id" class="block text-gray-700 font-medium mb-2">{{ __('Manager') }}</label>
                <select id="manager_id" name="manager_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('manager_id') border-red-500 @enderror">
                    <option value="">{{ __('No Manager') }}</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>{{ $manager->name }}</option>
                    @endforeach
                </select>
                @error('manager_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="region" class="block text-gray-700 font-medium mb-2">{{ __('Region') }}</label>
                <select id="region" name="region" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('region') border-red-500 @enderror" onchange="toggleSaudiFields()">
                    <option value="">{{ __('Select Region') }}</option>
                    <option value="saudi_arabia" {{ old('region') == 'saudi_arabia' ? 'selected' : '' }}>{{ __('Saudi Arabia') }}</option>
                    <option value="other" {{ old('region') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                </select>
                @error('region')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div id="saudi_fields" class="space-y-4 {{ old('region') == 'saudi_arabia' ? '' : 'hidden' }}">
                <div>
                    <label for="iqama_number" class="block text-gray-700 font-medium mb-2">{{ __('Iqama Number') }}</label>
                    <input type="text" id="iqama_number" name="iqama_number" value="{{ old('iqama_number') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('iqama_number') border-red-500 @enderror">
                    @error('iqama_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="iqama_expiry_date" class="block text-gray-700 font-medium mb-2">{{ __('Iqama Expiry Date') }}</label>
                    <input type="date" id="iqama_expiry_date" name="iqama_expiry_date" value="{{ old('iqama_expiry_date') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('iqama_expiry_date') border-red-500 @enderror">
                    @error('iqama_expiry_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="health_card_number" class="block text-gray-700 font-medium mb-2">{{ __('Health Card Number') }}</label>
                    <input type="text" id="health_card_number" name="health_card_number" value="{{ old('health_card_number') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('health_card_number') border-red-500 @enderror">
                    @error('health_card_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_remote" value="1" {{ old('is_remote') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">{{ __('Is Remote') }}</span>
                </label>
                @error('is_remote')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">{{ __('Roles') }}</label>
                @foreach ($roles as $role)
                    <div class="flex items-center">
                        <input type="checkbox" id="role_{{ $role->name }}" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="role_{{ $role->name }}" class="ml-2">{{ $role->name }}</label>
                    </div>
                @endforeach
                @error('roles')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                {{ __('Create User') }}
            </button>
        </form>
    </div>

    <script>
        function toggleSaudiFields() {
            const region = document.getElementById('region').value;
            const saudiFields = document.getElementById('saudi_fields');
            saudiFields.classList.toggle('hidden', region !== 'saudi_arabia');
        }
    </script>
@endsection