<?php

namespace App\Http\Controllers;

use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveManagementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', __('No employee profile found for your account. Please contact HR to set up your employee profile.'));
        }

        $leaveBalances = LeaveBalance::where('employee_id', $employee->id)
            ->where('year', now()->year)
            ->get();

        $leaveTypes = ['annual', 'sick'];
        foreach ($leaveTypes as $type) {
            if (!$leaveBalances->contains('leave_type', $type)) {
                LeaveBalance::create([
                    'employee_id' => $employee->id,
                    'leave_type' => $type,
                    'total_days' => $type === 'annual' ? 20 : 10,
                    'used_days' => 0,
                    'remaining_days' => $type === 'annual' ? 20 : 10,
                    'year' => now()->year,
                ]);
            }
        }

        $leaveBalances = LeaveBalance::where('employee_id', $employee->id)
            ->where('year', now()->year)
            ->get();

        $upcomingLeaves = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();

        $historicalLeaves = LeaveRequest::where('employee_id', $employee->id)
            ->where('start_date', '<', now())
            ->orderBy('start_date', 'desc')
            ->take(10)
            ->get();

        $pendingLeaveRequests = [];
        if ($user->hasRole('manager')) {
            $pendingLeaveRequests = LeaveRequest::where('manager_id', $user->id)
                ->where('status', 'pending')
                ->with('employee')
                ->latest()
                ->get();
        }

        return view('leave-management.index', compact('employee', 'leaveBalances', 'upcomingLeaves', 'historicalLeaves', 'pendingLeaveRequests'));
    }

    public function requestLeave(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', __('No employee profile found for your account. Please contact HR to set up your employee profile.'));
        }

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
            'leave_type' => 'required|in:annual,sick',
        ]);

        $managerId = $user->manager_id;
        if (!$managerId) {
            return redirect()->route('leave-management.index')->with('error', __('You do not have a manager assigned. Please contact HR.'));
        }

        $leaveBalance = LeaveBalance::where('employee_id', $employee->id)
            ->where('leave_type', $validated['leave_type'])
            ->where('year', now()->year)
            ->first();

        if (!$leaveBalance || $leaveBalance->remaining_days <= 0) {
            return redirect()->route('leave-management.index')->with('error', __('Insufficient leave balance for this leave type.'));
        }

        $duration = \Carbon\Carbon::parse($validated['start_date'])->diffInDays(\Carbon\Carbon::parse($validated['end_date'])) + 1;
        if ($duration > $leaveBalance->remaining_days) {
            return redirect()->route('leave-management.index')->with('error', __('Requested leave duration exceeds remaining balance.'));
        }

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employee->id,
            'manager_id' => $managerId,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        $manager = $user->manager;
        $manager->notify(new \App\Notifications\LeaveRequestSubmitted($leaveRequest));

        return redirect()->route('leave-management.index')->with('success', __('Leave request submitted successfully.'));
    }

    public function approveLeave(LeaveRequest $leaveRequest)
    {
        if (Auth::id() !== $leaveRequest->manager_id) {
            return redirect()->route('leave-management.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $leaveRequest->update(['status' => 'approved']);

        $leaveDays = \Carbon\Carbon::parse($leaveRequest->start_date)->diffInDays(\Carbon\Carbon::parse($leaveRequest->end_date)) + 1;
        $leaveBalance = LeaveBalance::firstOrCreate(
            [
                'employee_id' => $leaveRequest->employee_id,
                'leave_type' => $leaveRequest->leave_type,
                'year' => now()->year,
            ],
            [
                'total_days' => $leaveRequest->leave_type === 'annual' ? 20 : 10,
                'used_days' => 0,
                'remaining_days' => $leaveRequest->leave_type === 'annual' ? 20 : 10,
            ]
        );

        $leaveBalance->increment('used_days', $leaveDays);
        $leaveBalance->decrement('remaining_days', $leaveDays);
        $leaveBalance->save();

        return redirect()->route('leave-management.index')->with('success', __('Leave request approved successfully.'));
    }

    public function rejectLeave(LeaveRequest $leaveRequest)
    {
        if (Auth::id() !== $leaveRequest->manager_id) {
            return redirect()->route('leave-management.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $leaveRequest->update(['status' => 'rejected']);
        return redirect()->route('leave-management.index')->with('success', __('Leave request rejected successfully.'));
    }
}