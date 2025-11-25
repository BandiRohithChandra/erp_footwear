@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Schedule Performance Review') }}</h1>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow">
            <form method="POST" action="{{ route('performance-reviews.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="employee_id" class="block text-gray-700 font-medium mb-2">{{ __('Employee') }}</label>
                    <select id="employee_id" name="employee_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('employee_id') border-red-500 @enderror" required>
                        <option value="">{{ __('Select Employee') }}</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="review_date" class="block text-gray-700 font-medium mb-2">{{ __('Review Date') }}</label>
                    <input type="date" id="review_date" name="review_date" value="{{ old('review_date') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('review_date') border-red-500 @enderror" required>
                    @error('review_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                    {{ __('Schedule Review') }}
                </button>
            </form>
        </div>
    </div>
@endsection