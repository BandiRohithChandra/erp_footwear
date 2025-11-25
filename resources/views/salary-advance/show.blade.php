@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-8 bg-white rounded-2xl shadow-lg border border-gray-100">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Salary Advance Details</h2>
            <p class="text-sm text-gray-500 mt-1">View detailed information about the selected advance</p>
        </div>
        <a href="{{ route('salary-advance.index') }}" 
           class="text-sm text-gray-600 hover:text-gray-900 underline transition">
           ← Back to List
        </a>
    </div>

    <!-- Details Card -->
    <div class="grid gap-6 sm:grid-cols-2 bg-gray-50 p-6 rounded-xl border border-gray-200">
        <!-- Employee -->
        <div class="flex flex-col">
            <span class="text-sm text-gray-500 uppercase tracking-wide">Employee</span>
            <span class="mt-1 text-base font-semibold text-gray-900">{{ $advance->employee->name }}</span>
        </div>

        <!-- Amount -->
        <div class="flex flex-col">
            <span class="text-sm text-gray-500 uppercase tracking-wide">Amount</span>
            <span class="mt-1 text-base font-semibold text-gray-900">₹ {{ number_format($advance->amount, 2) }}</span>
        </div>

        <!-- Applied Amount -->
        <div class="flex flex-col">
            <span class="text-sm text-gray-500 uppercase tracking-wide">Applied Amount</span>
            <span class="mt-1 text-base font-semibold text-gray-900">₹ {{ number_format($advance->applied_amount ?? 0, 2) }}</span>
        </div>

        <!-- Remaining Amount -->
        <div class="flex flex-col">
            <span class="text-sm text-gray-500 uppercase tracking-wide">Remaining Amount</span>
            <span class="mt-1 text-base font-semibold text-gray-900">₹ {{ number_format($advance->remaining_amount ?? $advance->amount, 2) }}</span>
        </div>

        <!-- Total Earned -->
        <div class="flex flex-col">
            <span class="text-sm text-gray-500 uppercase tracking-wide">Total Earned (from Batches)</span>
            <span class="mt-1 text-base font-semibold text-gray-900">
                ₹ {{
                    number_format(
                        \App\Models\EmployeeBatch::where('employee_id', $advance->employee_id)
                            ->sum(\DB::raw('quantity * labor_rate')), 
                        2
                    )
                }}
            </span>
        </div>

        <!-- Status -->
        <div class="flex flex-col">
            <span class="text-sm text-gray-500 uppercase tracking-wide">Status</span>
            @php
                $statusColor = match($advance->dynamic_status ?? 'Pending') {
                    'Paid' => 'bg-green-100 text-green-800',
                    'Partially Paid' => 'bg-yellow-100 text-yellow-800',
                    default => 'bg-gray-100 text-gray-800',
                };
            @endphp
            <span class="mt-1 inline-flex items-center justify-center px-3 py-1 text-sm font-semibold rounded-full {{ $statusColor }}">
                {{ $advance->dynamic_status ?? 'Pending' }}
            </span>
        </div>

        <!-- Date -->
        <div class="flex flex-col sm:col-span-2">
            <span class="text-sm text-gray-500 uppercase tracking-wide">Advance Date</span>
            <span class="mt-1 text-base font-semibold text-gray-900">{{ \Carbon\Carbon::parse($advance->date)->format('d M, Y') }}</span>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-8 flex justify-end">
        <a href="{{ route('salary-advance.index') }}" 
           class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg shadow hover:bg-indigo-700 transition-all duration-200">
           Back to Salary Advances
        </a>
    </div>
</div>
@endsection
