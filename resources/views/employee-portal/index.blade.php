@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Employee Portal') }}</h1>

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

        <!-- Attendance -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Attendance') }}</h2>
            <div class="mb-4">
                @if ($openAttendance)
                    <form action="{{ route('employee-portal.mark-attendance') }}" method="POST" class="inline-block">
                        @csrf
                        <input type="hidden" name="action" value="check-out">
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                            {{ __('Check Out') }}
                        </button>
                    </form>
                @else
                    <form action="{{ route('employee-portal.mark-attendance') }}" method="POST" class="inline-block">
                        @csrf
                        <input type="hidden" name="action" value="check-in">
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                            {{ __('Check In') }}
                        </button>
                    </form>
                @endif
            </div>
            @if ($attendances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Check In') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Check Out') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $attendance->date }}</td>
                                    <td class="border p-3">{{ $attendance->check_in }}</td>
                                    <td class="border p-3">{{ $attendance->check_out ?? 'N/A' }}</td>
                                    <td class="border p-3">{{ $attendance->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No attendance records found.') }}</p>
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
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Leave Type') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Start Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('End Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Reason') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaveRequests as $leaveRequest)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ __(ucfirst($leaveRequest->leave_type)) }}</td>
                                    <td class="border p-3">{{ $leaveRequest->start_date }}</td>
                                    <td class="border p-3">{{ $leaveRequest->end_date }}</td>
                                    <td class="border p-3">{{ $leaveRequest->reason }}</td>
                                    <td class="border p-3">{{ $leaveRequest->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No leave requests found.') }}</p>
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
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Exit Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Re-Entry Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Reason') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($exitEntryRequests as $exitEntryRequest)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $exitEntryRequest->exit_date }}</td>
                                    <td class="border p-3">{{ $exitEntryRequest->re_entry_date }}</td>
                                    <td class="border p-3">{{ $exitEntryRequest->reason }}</td>
                                    <td class="border p-3">{{ $exitEntryRequest->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No exit/entry requests found.') }}</p>
            @endif
        </div>

        <!-- Salary Advance Requests -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Salary Advance Requests') }}</h2>
            <form method="POST" action="{{ route('employee-portal.request-advance-salary') }}" class="space-y-4 mb-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="amount" class="block text-gray-700 font-medium mb-2">{{ __('Amount') }}</label>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('amount') border-red-500 @enderror" required step="0.01">
                        @error('amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="reason" class="block text-gray-700 font-medium mb-2">{{ __('Reason') }}</label>
                        <textarea id="reason" name="reason" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('reason') border-red-500 @enderror" required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                    {{ __('Request Salary Advance') }}
                </button>
            </form>
            @if ($salaryAdvanceRequests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Amount') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Reason') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salaryAdvanceRequests as $salaryAdvanceRequest)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $salaryAdvanceRequest->amount }}</td>
                                    <td class="border p-3">{{ $salaryAdvanceRequest->reason }}</td>
                                    <td class="border p-3">{{ $salaryAdvanceRequest->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No salary advance requests found.') }}</p>
            @endif
        </div>

        <!-- Payslips -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Payslips') }}</h2>
            @if ($payslips->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Payment Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Amount') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Tax Rate') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Tax Amount') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Total Amount') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Description') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payslips as $payslip)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $payslip->payment_date }}</td>
                                    <td class="border p-3">{{ $payslip->amount }}</td>
                                    <td class="border p-3">{{ $payslip->tax_rate ? $payslip->tax_rate . '%' : 'N/A' }}</td>
                                    <td class="border p-3">{{ $payslip->tax_amount }}</td>
                                    <td class="border p-3">{{ $payslip->total_amount }}</td>
                                    <td class="border p-3">{{ $payslip->description ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No payslips found.') }}</p>
            @endif
        </div>

        <!-- Expense Claims -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Expense Claims') }}</h2>
            <form method="POST" action="{{ route('employee-portal.request-expense-claim') }}" enctype="multipart/form-data" class="space-y-4 mb-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="amount" class="block text-gray-700 font-medium mb-2">{{ __('Amount') }}</label>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('amount') border-red-500 @enderror" required step="0.01">
                        @error('amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="expense_date" class="block text-gray-700 font-medium mb-2">{{ __('Expense Date') }}</label>
                        <input type="date" id="expense_date" name="expense_date" value="{{ old('expense_date') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('expense_date') border-red-500 @enderror" required>
                        @error('expense_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="attachment" class="block text-gray-700 font-medium mb-2">{{ __('Attachment (Optional)') }}</label>
                        <input type="file" id="attachment" name="attachment" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('attachment') border-red-500 @enderror">
                        @error('attachment')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="description" class="block text-gray-700 font-medium mb-2">{{ __('Description') }}</label>
                    <textarea id="description" name="description" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                    {{ __('Submit Expense Claim') }}
                </button>
            </form>
            @if ($expenseClaims->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Expense Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Amount') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Description') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Attachment') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenseClaims as $expenseClaim)
                                <tr class="hover:bg-gray-50">
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
            <form method="POST" action="{{ route('employee-portal.request-training') }}" class="space-y-4 mb-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="training_title" class="block text-gray-700 font-medium mb-2">{{ __('Training Title') }}</label>
                        <input type="text" id="training_title" name="training_title" value="{{ old('training_title') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('training_title') border-red-500 @enderror" required>
                        @error('training_title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="proposed_date" class="block text-gray-700 font-medium mb-2">{{ __('Proposed Date') }}</label>
                        <input type="date" id="proposed_date" name="proposed_date" value="{{ old('proposed_date') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('proposed_date') border-red-500 @enderror" required>
                        @error('proposed_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="description" class="block text-gray-700 font-medium mb-2">{{ __('Description') }}</label>
                    <textarea id="description" name="description" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                    {{ __('Request Training') }}
                </button>
            </form>
            @if ($trainingRequests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Training Title') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Proposed Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Description') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trainingRequests as $trainingRequest)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $trainingRequest->training_title }}</td>
                                    <td class="border p-3">{{ $trainingRequest->proposed_date }}</td>
                                    <td class="border p-3">{{ $trainingRequest->description }}</td>
                                    <td class="border p-3">{{ $trainingRequest->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No training requests found.') }}</p>
            @endif
        </div>

        <!-- Warning Letters -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Warning Letters') }}</h2>
            @if ($warningLetters->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Issue Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Reason') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warningLetters as $warningLetter)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $warningLetter->issue_date }}</td>
                                    <td class="border p-3">{{ $warningLetter->reason }}</td>
                                    <td class="border p-3">
                                        <a href="{{ route('employee-portal.warning-letter.show', $warningLetter) }}" class="text-blue-500 hover:underline">{{ __('View Details') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No warning letters found.') }}</p>
            @endif
        </div>

        <!-- Notifications -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">{{ __('Notifications') }}</h2>
            @if ($notifications->count() > 0)
                <ul class="space-y-2">
                    @foreach ($notifications as $notification)
                        <li class="border-b py-2">
                            <p class="text-gray-700">{{ $notification->data['message'] }}</p>
                            <p class="text-gray-500 text-sm">{{ $notification->created_at->diffForHumans() }}</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">{{ __('No notifications found.') }}</p>
            @endif
        </div>
    </div>
@endsection