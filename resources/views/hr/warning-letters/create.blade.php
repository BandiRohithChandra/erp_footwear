@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Issue Warning Letter') }}</h1>

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

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (isset($employees) && $employees->isNotEmpty())
            <form method="POST" action="{{ route('warning-letters.store') }}" class="bg-white p-6 rounded-lg shadow space-y-4">
                @csrf
                <div>
                    <label for="employee_id" class="block text-gray-700 font-medium mb-2">{{ __('Employee') }}</label>
                    <select id="employee_id" name="employee_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('employee_id') border-red-500 @enderror" required>
                        <option value="">{{ __('Select Employee') }}</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->user ? $employee->user->name : 'No User (ID: ' . $employee->id . ')' }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="reason" class="block text-gray-700 font-medium mb-2">{{ __('Reason') }}</label>
                    <input type="text" id="reason" name="reason" value="{{ old('reason') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('reason') border-red-500 @enderror" required>
                    @error('reason')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="description" class="block text-gray-700 font-medium mb-2">{{ __('Description') }}</label>
                    <textarea id="description" name="description" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="issue_date" class="block text-gray-700 font-medium mb-2">{{ __('Issue Date') }}</label>
                    <input type="date" id="issue_date" name="issue_date" value="{{ old('issue_date', now()->toDateString()) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('issue_date') border-red-500 @enderror" required>
                    @error('issue_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                    {{ __('Issue Warning Letter') }}
                </button>
            </form>
        @else
            <p class="text-gray-600">{{ __('No employees available to issue a warning letter.') }}</p>
        @endif
    </div>
@endsection