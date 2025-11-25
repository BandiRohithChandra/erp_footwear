<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function print(Request $request)
    {
        // Default to the current month (June 2025) if no dates are provided
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString()); // 2025-06-01
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString()); // 2025-06-30

        // Fetch employees with their attendances for the given date range
        $employees = Employee::with(['attendances' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }])->get();

        return view('hr.attendance.print', compact('employees', 'startDate', 'endDate'));
    }
}