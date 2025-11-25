@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Submit Exit/Entry Request') }}</h1>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

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

        <form method="POST" action="{{ route('exit-entry-requests.store') }}" class="bg-white p-6 rounded-lg shadow space-y-4">
            @csrf
            <div>
                <label for="exit_date" class="block text-gray-700 font-medium mb-2">{{ __('Exit Date') }}</label>
                <input type="date" id="exit_date" name="exit_date" value="{{ old('exit_date') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('exit_date') border-red-500 @enderror" required>
                @error('exit_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="re_entry_date" class="block text-gray-700 font-medium mb-2">{{ __('Re-Entry Date') }}</label>
                <input type="date" id="re_entry_date" name="re_entry_date" value="{{ old('re_entry_date') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('re_entry_date') border-red-500 @enderror" required>
                @error('re_entry_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="reason" class="block text-gray-700 font-medium mb-2">{{ __('Reason') }}</label>
                <textarea id="reason" name="reason" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('reason') border-red-500 @enderror" required>{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                {{ __('Submit Request') }}
            </button>
        </form>
    </div>
@endsection