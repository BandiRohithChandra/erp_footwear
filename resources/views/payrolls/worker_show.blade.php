@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-gray-50 min-h-screen">

    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('payrolls.worker_payroll_index') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
            ← Back
        </a>
    </div>

    <h2 class="text-3xl font-bold text-gray-800 mb-6">Worker Details: {{ $employee->name }}</h2>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-gradient-to-r from-green-100 to-green-200 p-6 rounded-2xl shadow-md">
            <h3 class="font-semibold text-lg mb-2 text-green-800">Total Salary</h3>
            <p class="text-2xl font-bold text-green-900">₹{{ number_format($totalSalary, 2) }}</p>
        </div>
        <div class="bg-gradient-to-r from-blue-100 to-blue-200 p-6 rounded-2xl shadow-md">
            <h3 class="font-semibold text-lg mb-2 text-blue-800">Total Paid</h3>
            <p class="text-2xl font-bold text-blue-900">₹{{ number_format($totalPaid, 2) }}</p>
        </div>
        <div class="bg-gradient-to-r from-yellow-100 to-yellow-200 p-6 rounded-2xl shadow-md">
            <h3 class="font-semibold text-lg mb-2 text-yellow-800">Remaining Advance</h3>
            <p class="text-2xl font-bold text-yellow-900">₹{{ number_format($remainingAdvance, 2) }}</p>
        </div>
        <div class="bg-gradient-to-r from-red-100 to-red-200 p-6 rounded-2xl shadow-md">
            <h3 class="font-semibold text-lg mb-2 text-red-800">Remaining Due</h3>
            <p class="text-2xl font-bold text-red-900">₹{{ number_format($due, 2) }}</p>

            @php
                $status = strtolower($overallStatus ?? 'pending');
                $statusColor = match($status) {
                    'paid' => 'bg-green-100 text-green-800 border border-green-300',
                    'partially_paid' => 'bg-yellow-100 text-yellow-800 border border-yellow-300',
                    'pending' => 'bg-red-100 text-red-800 border border-red-300',
                    default => 'bg-gray-100 text-gray-800 border border-gray-300',
                };
            @endphp

            <span class="inline-block mt-2 px-3 py-1 text-sm font-semibold rounded-full {{ $statusColor }}">
                {{ ucfirst(str_replace('_', ' ', $overallStatus ?? 'Pending')) }}
            </span>
        </div>
    </div>

    <!-- Worker Info -->
    <div class="bg-white rounded-2xl shadow-md p-6 mb-6 border-l-8 border-blue-500">
        <h3 class="text-xl font-semibold text-blue-700 mb-4">Worker Info</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <p><strong>Name:</strong> {{ $employee->name }}</p>
            <p><strong>Role:</strong> {{ $employee->role ?? 'N/A' }}</p>
        </div>
    </div>

    <!-- Batches & Assigned Processes -->
    <div class="bg-white rounded-2xl shadow-md p-6 mb-6 border-l-8 border-green-500">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-green-700">Batches & Assigned Processes</h3>
            <div class="text-sm text-gray-500 space-x-2">
                <span class="inline-flex items-center"><span class="w-3 h-3 bg-green-500 rounded-full mr-1"></span> Paid</span>
                <span class="inline-flex items-center"><span class="w-3 h-3 bg-yellow-500 rounded-full mr-1"></span> Partially Paid</span>
                <span class="inline-flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-1"></span> Pending</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-center">
                <thead class="bg-green-100">
                    <tr>
                        <th class="py-2 px-3 border-b">S.No</th>
                        <th class="py-2 px-3 border-b">Batch No</th>
                        <th class="py-2 px-3 border-b">Process</th>
                        <th class="py-2 px-3 border-b">Assigned Qty</th>
                        <th class="py-2 px-3 border-b">Rate (₹)</th>
                        <th class="py-2 px-3 border-b">Amount Paid (₹)</th>
                        <th class="py-2 px-3 border-b">Remaining Due (₹)</th>
                        <th class="py-2 px-3 border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $index => $batch)
                        @php
                            $assignedProcesses = $batch['assignedProcesses'] ?? [];
                        @endphp

                        @if(!empty($assignedProcesses))
                            @foreach($assignedProcesses as $procIndex => $process)
                                @php
                                    $status = strtolower($process['status'] ?? 'pending');
                                    $statusColor = match($status) {
                                        'paid' => 'text-green-700 bg-green-100 border border-green-300',
                                        'partially_paid' => 'text-yellow-700 bg-yellow-100 border border-yellow-300',
                                        'pending' => 'text-red-700 bg-red-100 border border-red-300',
                                        default => 'text-gray-700 bg-gray-100 border border-gray-300',
                                    };
                                @endphp

                                <tr class="hover:bg-green-50 font-medium">
                                    <td class="py-2 px-3 border-b">{{ $index + 1 }}.{{ $procIndex + 1 }}</td>
                                    <td class="py-2 px-3 border-b">{{ $batch['batch_no'] }}</td>
                                    <td class="py-2 px-3 border-b">{{ $process['process_name'] ?? 'N/A' }}</td>
                                    <td class="py-2 px-3 border-b">{{ $process['assigned_qty'] }}</td>
                                    <td class="py-2 px-3 border-b">₹{{ number_format($process['rate'] ?? 0, 2) }}</td>
                                    <td class="py-2 px-3 border-b text-green-700 font-semibold">₹{{ number_format($process['paid'] ?? 0, 2) }}</td>
                                    <td class="py-2 px-3 border-b text-red-600 font-semibold">₹{{ number_format($process['due'] ?? 0, 2) }}</td>
                                    <td class="py-2 px-3 border-b">
                                        <span class="px-2 py-1 rounded-full text-sm font-semibold {{ $statusColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $process['status'] ?? 'Pending')) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="py-4 text-gray-500">No assigned processes found for this batch.</td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="py-4 text-gray-500">No batches found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Salary Advances -->
    <div class="bg-white rounded-2xl shadow-md p-6 mb-6 border-l-8 border-yellow-500">
        <h3 class="text-xl font-semibold text-yellow-700 mb-4">Salary Advances</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-center">
                <thead class="bg-yellow-100">
                    <tr>
                        <th class="py-2 px-3 border-b">S.No</th>
                        <th class="py-2 px-3 border-b">Advance Amount (₹)</th>
                        <th class="py-2 px-3 border-b">Used Amount (₹)</th>
                        <th class="py-2 px-3 border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($advances as $index => $advance)
                        <tr class="hover:bg-yellow-50">
                            <td class="py-2 px-3 border-b">{{ $index + 1 }}</td>
                            <td class="py-2 px-3 border-b">₹{{ number_format($advance->amount, 2) }}</td>
                            <td class="py-2 px-3 border-b">₹{{ number_format($advance->used_amount ?? 0, 2) }}</td>
                            <td class="py-2 px-3 border-b">
                                @php
                                    $advColor = $advance->status == 'Approved'
                                        ? 'bg-green-100 text-green-800 border border-green-300'
                                        : 'bg-blue-100 text-blue-800 border border-blue-300';
                                @endphp
                                <span class="px-2 py-1 rounded-full text-sm font-semibold {{ $advColor }}">
                                    {{ $advance->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-gray-500">No advances found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('payrolls.worker_payroll_index') }}" class="text-blue-600 hover:underline">
            ← Back to Payroll List
        </a>
    </div>
</div>
@endsection
