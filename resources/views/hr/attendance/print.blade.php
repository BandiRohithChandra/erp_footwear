<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Attendance Report') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; }
        .date-range { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .print-button { text-align: center; margin-top: 20px; }
        @media print {
            .print-button { display: none; }
        }
    </style>
</head>
<body>
    <h1>{{ __('Attendance Report') }}</h1>
    <div class="date-range">
        {{ __('Date Range') }}: {{ $startDate }} {{ __('to') }} {{ $endDate }}
    </div>

    @if (isset($employees) && $employees->isNotEmpty())
        @foreach ($employees as $employee)
            <h2>{{ $employee->user ? $employee->user->name : 'Employee Not Found (ID: ' . $employee->id . ')' }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Check In') }}</th>
                        <th>{{ __('Check Out') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($employee->attendances && $employee->attendances->isNotEmpty())
                        @foreach ($employee->attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->date }}</td>
                                <td>{{ $attendance->check_in ?? 'N/A' }}</td>
                                <td>{{ $attendance->check_out ?? 'N/A' }}</td>
                                <td>{{ $attendance->status }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">{{ __('No attendance records found.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach
    @else
        <p>{{ __('No employees found for this report.') }}</p>
    @endif

    <div class="print-button">
        <button onclick="window.print()">{{ __('Print') }}</button>
    </div>
</body>
</html>