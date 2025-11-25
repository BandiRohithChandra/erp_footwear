@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded-lg shadow">
    <h1 class="text-xl font-bold mb-6">Assign Worker to Process</h1>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('batch-flow-assignments.store') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Batch --}}
        <div>
            <label class="block text-sm font-medium mb-1">Batch</label>
            <select name="batch_id" class="w-full border-gray-300 rounded-lg shadow-sm">
                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}"
                        {{ (isset($selectedBatch) && $selectedBatch == $batch->id) ? 'selected' : '' }}>
                        {{ $batch->batch_no }} - {{ $batch->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Process --}}
        <div>
            <label class="block text-sm font-medium mb-1">Process</label>
            <select name="process_id" class="w-full border-gray-300 rounded-lg shadow-sm">
                @foreach($processes as $process)
                    <option value="{{ $process->id }}">{{ $process->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Employee / Worker --}}
        <div>
            <label class="block text-sm font-medium mb-1">Employee / Worker</label>
            <select name="worker_id" class="w-full border-gray-300 rounded-lg shadow-sm">
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end gap-2">
            <a href="{{ route('batch-flow-assignments.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Assign
            </button>
        </div>
    </form>
</div>
@endsection
