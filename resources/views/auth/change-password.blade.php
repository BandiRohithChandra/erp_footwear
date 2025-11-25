@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-6">{{ __('Change Password') }}</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.change.update') }}" class="space-y-4">
            @csrf
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">{{ __('New Password') }}</label>
                <input id="password" type="password" name="password" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror" required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                {{ __('Change Password') }}
            </button>
        </form>
    </div>
@endsection