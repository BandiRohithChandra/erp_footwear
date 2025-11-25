@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded-lg shadow">
    <h1 class="text-xl font-bold mb-6">Edit Assignment</h1>

    <form action="{{ route('batch-flow-assignments.update', $assignment->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Batch Flow</label>
            <select name="batch_flow_id" class="w-full border-gray-300 rounded-lg shadow-sm">
                @foreach($batchFlows as $flow)
                    <option value="{{ $flow->id }}" {{ $assignment->batch_flow_id == $flow->id ? 'selected' : '' }}>
                        {{ $flow->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Process</label>
            <select name="process_id" class="w-full border-gray-300 rounded-lg shadow-sm">
                @foreach($processes as $process)
                    <option value="{{ $process->id }}" {{ $assignment->process_id == $process->id ? 'selected' : '' }}>
                        {{ $process->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Employee</label>
            <select name="employee_id" class="w-full border-gray-300 rounded-lg shadow-sm">
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ $assignment->employee_id == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('batch-flow-assignments.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
