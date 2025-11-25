@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-8 bg-white rounded-2xl shadow-lg border border-gray-100">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">New Salary Advance</h2>
            <p class="text-sm text-gray-500 mt-1">Create a new salary advance record for an employee</p>
        </div>
        <a href="{{ route('salary-advance.index') }}" 
           class="text-sm text-gray-600 hover:text-gray-900 underline transition">
           ← Back to List
        </a>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
            <h3 class="text-red-700 font-semibold mb-2">Please fix the following errors:</h3>
            <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('salary-advance.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Employee -->
        <div>
            <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
            <select id="employee_id" name="employee_id" 
                class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 text-gray-800 py-2.5"
                required>
                <option value="">-- Select Employee --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Amount -->
        <div>
            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (₹)</label>
            <input type="number" id="amount" name="amount" min="1" step="0.01" 
                class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 text-gray-800 py-2.5"
                placeholder="Enter amount" required>
        </div>

        <!-- Date -->
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" id="date" name="date" 
                class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 text-gray-800 py-2.5"
                required>
        </div>

        <!-- Actions -->
        <div class="flex justify-end items-center gap-3 pt-4">
            <a href="{{ route('salary-advance.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" 
                class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg shadow hover:bg-indigo-700 transition-all duration-200">
                Save Advance
            </button>
        </div>
    </form>
</div>
@endsection
