@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded-lg shadow">
    <h1 class="text-xl font-bold mb-6">Add New Worker</h1>

    <form action="{{ route('workers.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Name</label>
            <input type="text" name="name" class="w-full border p-2 rounded" value="{{ old('name') }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded" value="{{ old('email') }}">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Phone</label>
            <input type="text" name="phone" class="w-full border p-2 rounded" value="{{ old('phone') }}">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Role</label>
            <input type="text" name="role" class="w-full border p-2 rounded" value="{{ old('role', 'worker') }}">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full border p-2 rounded">
                <option value="active" selected>Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('workers.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Worker</button>
        </div>
    </form>
</div>
@endsection
