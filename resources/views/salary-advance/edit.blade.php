@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold mb-4">Edit Salary Advance</h2>

    <form action="{{ route('salary-advance.update', $advance->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Employee Name (disabled for display) -->
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Employee</label>
            <input type="text" class="w-full border rounded px-3 py-2" value="{{ $advance->employee->name }}" disabled>
            <!-- Hidden field to send employee_id -->
            <input type="hidden" name="employee_id" value="{{ $advance->employee_id }}">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Amount</label>
            <input type="number" name="amount" class="w-full border rounded px-3 py-2" value="{{ $advance->amount }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Date</label>
            <input type="date" name="date" class="w-full border rounded px-3 py-2" value="{{ $advance->date->format('Y-m-d') }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                <option value="Pending" {{ $advance->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ $advance->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Paid" {{ $advance->status == 'Paid' ? 'selected' : '' }}>Paid</option>
            </select>
        </div>

        <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Update</button>
        <a href="{{ route('salary-advance.index') }}" class="ml-2 px-5 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Cancel</a>
    </form>
</div>
@endsection
