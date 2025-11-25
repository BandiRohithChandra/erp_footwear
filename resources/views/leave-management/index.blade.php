@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Leave Management Dashboard') }}</h1>

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

        <!-- Leave Balances -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Leave Balances') }} ({{ now()->year }})</h2>
            @if ($leaveBalances->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($leaveBalances as $balance)
                        <div class="border p-4 rounded-lg">
                            <h3 class="text-lg font-semibold">{{ __(ucfirst($balance->leave_type)) }} {{ __('Leave') }}</h3>
                            <p>{{ __('Total') }}: {{ $balance->total_days }} {{ __('days') }}</p>
                            <p>{{ __('Used') }}: {{ $balance->used_days }} {{ __('days') }}</p>
                            <p>{{ __('Remaining') }}: {{ $balance->remaining_days }} {{ __('days') }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">{{ __('No leave balances found.') }}</p>
            @endif
        </div>

        <!-- Request Leave -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Request Leave') }}</h2>
            <form method="POST" action="{{ route('leave-management.request') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="leave_type" class="block text-gray-700 font-medium mb-2">{{ __('Leave Type') }}</label>
                        <select id="leave_type" name="leave_type" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('leave_type') border-red-500 @enderror" required>
                            <option value="annual" {{ old('leave_type') === 'annual' ? 'selected' : '' }}>{{ __('Annual') }}</option>
                            <option value="sick" {{ old('leave_type') === 'sick' ? 'selected' : '' }}>{{ __('Sick') }}</option>
                        </select>
                        @error('leave_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="start_date" class="block text-gray-700 font-medium mb-2">{{ __('Start Date') }}</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 @enderror" required>
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-gray-700 font-medium mb-2">{{ __('End Date') }}</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror" required>
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="reason" class="block text-gray-700 font-medium mb-2">{{ __('Reason') }}</label>
                    <textarea id="reason" name="reason" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('reason') border-red-500 @enderror" required>{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                    {{ __('Submit Leave Request') }}
                </button>
            </form>
        </div>

        <!-- Upcoming Leaves -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Upcoming Leaves') }}</h2>
            @if ($upcomingLeaves->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Leave Type') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Start Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('End Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Duration') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Reason') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($upcomingLeaves as $leave)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ __(ucfirst($leave->leave_type)) }}</td>
                                    <td class="border p-3">{{ $leave->start_date }}</td>
                                    <td class="border p-3">{{ $leave->end_date }}</td>
                                    <td class="border p-3">{{ $leave->duration }} {{ __('days') }}</td>
                                    <td class="border p-3">{{ $leave->reason }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No upcoming leaves scheduled.') }}</p>
            @endif
        </div>

        <!-- Historical Leaves -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Historical Leaves') }}</h2>
            @if ($historicalLeaves->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Leave Type') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Start Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('End Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Duration') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Reason') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historicalLeaves as $leave)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ __(ucfirst($leave->leave_type)) }}</td>
                                    <td class="border p-3">{{ $leave->start_date }}</td>
                                    <td class="border p-3">{{ $leave->end_date }}</td>
                                    <td class="border p-3">{{ $leave->duration }} {{ __('days') }}</td>
                                    <td class="border p-3">{{ $leave->reason }}</td>
                                    <td class="border p-3">{{ $leave->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No historical leave records found.') }}</p>
            @endif
        </div>

        <!-- Pending Leave Requests (For Managers) -->
        @if (Auth::user()->hasRole('manager') && $pendingLeaveRequests->count() > 0)
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">{{ __('Pending Leave Requests') }}</h2>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Employee') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Leave Type') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Start Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('End Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Duration') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Reason') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingLeaveRequests as $leaveRequest)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $leaveRequest->employee->name }}</td>
                                    <td class="border p-3">{{ __(ucfirst($leaveRequest->leave_type)) }}</td>
                                    <td class="border p-3">{{ $leaveRequest->start_date }}</td>
                                    <td class="border p-3">{{ $leaveRequest->end_date }}</td>
                                    <td class="border p-3">{{ $leaveRequest->duration }} {{ __('days') }}</td>
                                    <td class="border p-3">{{ $leaveRequest->reason }}</td>
                                    <td class="border p-3">
                                        <form action="{{ route('leave-management.approve', $leaveRequest) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 mr-2">
                                                {{ __('Approve') }}
                                            </button>
                                        </form>
                                        <form action="{{ route('leave-management.reject', $leaveRequest) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                {{ __('Reject') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection