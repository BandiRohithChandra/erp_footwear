@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Manager Portal') }}</h1>

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

        <!-- Notifications -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Notifications') }}</h2>
            @if ($notifications->count() > 0)
                <ul class="space-y-2">
                    @foreach ($notifications as $notification)
                        <li class="border-b py-2 flex justify-between items-center">
                            <div>
                                <p class="text-gray-700">{{ $notification->data['message'] }}</p>
                                <p class="text-gray-500 text-sm">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            @if (!$notification->read_at)
                                <form action="{{ route('manager-portal.mark-notification-as-read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-blue-500 hover:underline text-sm">
                                        {{ __('Mark as Read') }}
                                    </button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">{{ __('No notifications found.') }}</p>
            @endif
        </div>

        <!-- Leave Requests -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Leave Requests') }}</h2>
            <div class="mb-4">
                <a href="{{ route('leave-management.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    {{ __('Go to Leave Management Dashboard') }}
                </a>
            </div>
            @if ($leaveRequests->count() > 0)
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
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaveRequests as $leaveRequest)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $leaveRequest->employee->name }}</td>
                                    <td class="border p-3">{{ __(ucfirst($leaveRequest->leave_type)) }}</td>
                                    <td class="border p-3">{{ $leaveRequest->start_date }}</td>
                                    <td class="border p-3">{{ $leaveRequest->end_date }}</td>
                                    <td class="border p-3">{{ $leaveRequest->duration }} {{ __('days') }}</td>
                                    <td class="border p-3">{{ $leaveRequest->reason }}</td>
                                    <td class="border p-3">{{ $leaveRequest->status }}</td>
                                    <td class="border p-3">
                                        @if ($leaveRequest->status === 'pending')
                                            <form action="{{ route('manager-portal.approve-leave', $leaveRequest) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 mr-2">
                                                    {{ __('Approve') }}
                                                </button>
                                            </form>
                                            <form action="{{ route('manager-portal.reject-leave', $leaveRequest) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    {{ __('Reject') }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-500">{{ __('Action Taken') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No leave requests found.') }}</p>
            @endif
        </div>

        <!-- Salary Advance Requests -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Salary Advance Requests') }}</h2>
            @if ($salaryAdvanceRequests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Employee') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Amount') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Reason') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salaryAdvanceRequests as $salaryAdvanceRequest)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $salaryAdvanceRequest->employee->name }}</td>
                                    <td class="border p-3">{{ $salaryAdvanceRequest->amount }}</td>
                                    <td class="border p-3">{{ $salaryAdvanceRequest->reason }}</td>
                                    <td class="border p-3">{{ $salaryAdvanceRequest->status }}</td>
                                    <td class="border p-3">
                                        @if ($salaryAdvanceRequest->status === 'pending')
                                            <form action="{{ route('manager-portal.approve-salary-advance', $salaryAdvanceRequest) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 mr-2">
                                                    {{ __('Approve') }}
                                                </button>
                                            </form>
                                            <form action="{{ route('manager-portal.reject-salary-advance', $salaryAdvanceRequest) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    {{ __('Reject') }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-500">{{ __('Action Taken') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No salary advance requests found.') }}</p>
            @endif
        </div>

        <!-- Exit/Entry Requests -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Exit/Entry Requests') }}</h2>
            @if ($exitEntryRequests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Employee') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Exit Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Re-Entry Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Reason') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($exitEntryRequests as $exitEntryRequest)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $exitEntryRequest->employee->name }}</td>
                                    <td class="border p-3">{{ $exitEntryRequest->exit_date }}</td>
                                    <td class="border p-3">{{ $exitEntryRequest->re_entry_date }}</td>
                                    <td class="border p-3">{{ $exitEntryRequest->reason }}</td>
                                    <td class="border p-3">{{ $exitEntryRequest->status }}</td>
                                    <td class="border p-3">
                                        @if ($exitEntryRequest->status === 'pending')
                                            <form action="{{ route('manager-portal.approve-exit-entry', $exitEntryRequest) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 mr-2">
                                                    {{ __('Approve') }}
                                                </button>
                                            </form>
                                            <form action="{{ route('manager-portal.reject-exit-entry', $exitEntryRequest) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    {{ __('Reject') }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-500">{{ __('Action Taken') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No exit/entry requests found.') }}</p>
            @endif
        </div>

        <!-- Expense Claims -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Expense Claims') }}</h2>
            @if ($expenseClaims->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Employee') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Expense Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Amount') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Description') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Attachment') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenseClaims as $expenseClaim)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $expenseClaim->employee->name }}</td>
                                    <td class="border p-3">{{ $expenseClaim->expense_date }}</td>
                                    <td class="border p-3">{{ $expenseClaim->amount }}</td>
                                    <td class="border p-3">{{ $expenseClaim->description }}</td>
                                    <td class="border p-3">{{ $expenseClaim->status }}</td>
                                    <td class="border p-3">
                                        @if ($expenseClaim->attachment_path)
                                            <a href="{{ Storage::url($expenseClaim->attachment_path) }}" target="_blank" class="text-blue-500 hover:underline">{{ __('View Attachment') }}</a>
                                        @else
                                            {{ __('N/A') }}
                                        @endif
                                    </td>
                                    <td class="border p-3">
                                        @if ($expenseClaim->status === 'pending')
                                            <form action="{{ route('manager-portal.approve-expense-claim', $expenseClaim) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 mr-2">
                                                    {{ __('Approve') }}
                                                </button>
                                            </form>
                                            <form action="{{ route('manager-portal.reject-expense-claim', $expenseClaim) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    {{ __('Reject') }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-500">{{ __('Action Taken') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No expense claims found.') }}</p>
            @endif
        </div>

        <!-- Training Requests -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Training Requests') }}</h2>
            @if ($trainingRequests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Employee') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Training Title') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Proposed Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Description') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trainingRequests as $trainingRequest)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $trainingRequest->employee->name }}</td>
                                    <td class="border p-3">{{ $trainingRequest->training_title }}</td>
                                    <td class="border p-3">{{ $trainingRequest->proposed_date }}</td>
                                    <td class="border p-3">{{ $trainingRequest->description }}</td>
                                    <td class="border p-3">{{ $trainingRequest->status }}</td>
                                    <td class="border p-3">
                                        @if ($trainingRequest->status === 'pending')
                                            <form action="{{ route('manager-portal.approve-training-request', $trainingRequest) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 mr-2">
                                                    {{ __('Approve') }}
                                                </button>
                                            </form>
                                            <form action="{{ route('manager-portal.reject-training-request', $trainingRequest) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    {{ __('Reject') }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-500">{{ __('Action Taken') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No training requests found.') }}</p>
            @endif
        </div>

        <!-- Performance Reviews -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Performance Reviews') }}</h2>
            <div class="mb-4">
                <a href="{{ route('performance-reviews.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    {{ __('Go to Performance Reviews') }}
                </a>
            </div>
            @if ($performanceReviews->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Employee') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Review Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($performanceReviews as $review)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $review->employee->name }}</td>
                                    <td class="border p-3">{{ $review->review_date }}</td>
                                    <td class="border p-3">{{ $review->status }}</td>
                                    <td class="border p-3">
                                        <a href="{{ route('performance-reviews.show', $review) }}" class="text-blue-500 hover:underline">{{ __('View') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No performance reviews scheduled.') }}</p>
            @endif
        </div>
    </div>
@endsection