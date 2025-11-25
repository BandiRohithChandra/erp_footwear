@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Process Flow</h1>

    @foreach($batchFlows as $flow)
        <div class="mb-8 p-4 border rounded shadow bg-white">
            <h2 class="text-lg font-semibold mb-2">
                {{ $flow->batch->batch_no }} - {{ $flow->batch->name }} 
                (Priority: {{ ucfirst($flow->priority) }})
            </h2>

            <p class="text-sm text-gray-500 mb-4">
                Batch ID: {{ $flow->batch->id }} | Created At: {{ $flow->batch->created_at }}
            </p>

            <table class="min-w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-100 uppercase text-xs text-gray-700">
                    <tr>
                        <th class="px-4 py-2">Process ID</th>
                        <th class="px-4 py-2">Process Name</th>
                        <th class="px-4 py-2">Sequence</th>
                        <th class="px-4 py-2">Assigned Worker</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Assigned At</th>
                        <th class="px-4 py-2">Completed At</th>
                        <th class="px-4 py-2">Operator</th>
                        <th class="px-4 py-2">Progress %</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($flow->assignments->sortBy('process.sequence') as $assignment)
                        @php
                            $statusColor = match($assignment->status) {
                                'pending' => 'bg-gray-400 text-white',
                                'in_progress' => 'bg-blue-500 text-white',
                                'completed' => 'bg-green-500 text-white',
                                'on_hold' => 'bg-orange-500 text-white',
                                default => 'bg-gray-200'
                            };
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $assignment->process->id }}</td>
                            <td class="px-4 py-2">{{ $assignment->process->name }}</td>
                            <td class="px-4 py-2">{{ $assignment->process->sequence }}</td>
                            <td class="px-4 py-2">{{ $assignment->employee->name ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded {{ $statusColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $assignment->assigned_at }}</td>
                            <td class="px-4 py-2">{{ $assignment->completed_at ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $assignment->process->operator ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $assignment->process->progress_percent }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>
@endsection
