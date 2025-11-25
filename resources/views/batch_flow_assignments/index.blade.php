@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Batch Assignments</h1>

    <div class="mb-4">
        <a href="{{ route('batch-flow-assignments.create') }}" 
           class="btn-primary" 
           style="background-color: #28a745;">
           Assign Workers
        </a>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Batch</th>
                    <th class="px-4 py-3">Process</th>
                    <th class="px-4 py-3">Employee</th>
                    <th class="px-4 py-3">Assigned At</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $assignment->id }}</td>
                        <td class="px-4 py-3">
                            {{ $assignment->batchFlow->batch->batch_no ?? '-' }} - 
                            {{ $assignment->batchFlow->batch->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3">{{ $assignment->process->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $assignment->employee->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $assignment->assigned_at }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('batch-flow-assignments.updateStatus', $assignment->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="border rounded px-2 py-1">
                                    <option value="pending" {{ $assignment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $assignment->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $assignment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="on_hold" {{ $assignment->status == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('batch-flow-assignments.edit', $assignment->id) }}" 
                               class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                Edit
                            </a>
                            <form action="{{ route('batch-flow-assignments.destroy', $assignment->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                                        onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center text-gray-500">No assignments found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
