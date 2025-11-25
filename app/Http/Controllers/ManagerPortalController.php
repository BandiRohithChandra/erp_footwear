<?php

namespace App\Http\Controllers;

use App\Models\ExitEntryRequest;
use App\Models\ExpenseClaim;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\PerformanceReview;
use App\Models\SalaryAdvanceRequest;
use App\Models\TrainingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerPortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch leave requests assigned to the manager
        $leaveRequests = LeaveRequest::where('manager_id', $user->id)
            ->latest()
            ->get();

        // Fetch salary advance requests assigned to the manager
        $salaryAdvanceRequests = SalaryAdvanceRequest::where('manager_id', $user->id)
            ->latest()
            ->get();

        // Fetch exit/entry requests for employees under this manager
        $exitEntryRequests = ExitEntryRequest::whereHas('employee', function ($query) use ($user) {
            $query->whereIn('user_id', $user->subordinates->pluck('id'));
        })->latest()->get();

        // Fetch expense claims
        $expenseClaims = ExpenseClaim::where('manager_id', $user->id)
            ->latest()
            ->get();

        // Fetch training requests
        $trainingRequests = TrainingRequest::where('manager_id', $user->id)
            ->latest()
            ->get();

        // Fetch performance reviews
        $performanceReviews = PerformanceReview::where('reviewer_id', $user->id)
            ->with('employee')
            ->latest()
            ->get();

        // Fetch unread notifications
        $notifications = $user->unreadNotifications;

        return view('manager-portal.index', compact('leaveRequests', 'salaryAdvanceRequests', 'exitEntryRequests', 'expenseClaims', 'trainingRequests', 'performanceReviews', 'notifications'));
    }

    public function markNotificationAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        return redirect()->back()->with('success', __('Notification marked as read.'));
    }

    public function approveLeave(LeaveRequest $leaveRequest)
    {
        if (Auth::id() !== $leaveRequest->manager_id) {
            return redirect()->route('manager-portal.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $leaveRequest->update(['status' => 'approved']);

        // Update leave balance
        $leaveDays = $leaveRequest->duration;
        $leaveBalance = LeaveBalance::firstOrCreate(
            [
                'employee_id' => $leaveRequest->employee_id,
                'leave_type' => $leaveRequest->leave_type,
                'year' => now()->year,
            ],
            [
                'total_days' => 20,
                'used_days' => 0,
                'remaining_days' => 20,
            ]
        );

        $leaveBalance->increment('used_days', $leaveDays);
        $leaveBalance->decrement('remaining_days', $leaveDays);
        $leaveBalance->save();

        return redirect()->route('manager-portal.index')->with('success', __('Leave request approved successfully.'));
    }

    public function rejectLeave(LeaveRequest $leaveRequest)
    {
        if (Auth::id() !== $leaveRequest->manager_id) {
            return redirect()->route('manager-portal.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $leaveRequest->update(['status' => 'rejected']);
        return redirect()->route('manager-portal.index')->with('success', __('Leave request rejected successfully.'));
    }

    public function approveAdvanceSalary(SalaryAdvanceRequest $salaryAdvanceRequest)
    {
        if (Auth::id() !== $salaryAdvanceRequest->manager_id) {
            return redirect()->route('manager-portal.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $salaryAdvanceRequest->update(['status' => 'approved']);
        return redirect()->route('manager-portal.index')->with('success', __('Salary advance request approved successfully.'));
    }

    public function rejectAdvanceSalary(SalaryAdvanceRequest $salaryAdvanceRequest)
    {
        if (Auth::id() !== $salaryAdvanceRequest->manager_id) {
            return redirect()->route('manager-portal.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $salaryAdvanceRequest->update(['status' => 'rejected']);
        return redirect()->route('manager-portal.index')->with('success', __('Salary advance request rejected successfully.'));
    }

    public function approveExitEntry(ExitEntryRequest $exitEntryRequest)
    {
        $user = Auth::user();
        if (!$exitEntryRequest->employee->user->manager || $exitEntryRequest->employee->user->manager->id !== $user->id) {
            return redirect()->route('manager-portal.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $exitEntryRequest->update(['status' => 'approved']);
        return redirect()->route('manager-portal.index')->with('success', __('Exit/Entry Request approved successfully.'));
    }

    public function rejectExitEntry(ExitEntryRequest $exitEntryRequest)
    {
        $user = Auth::user();
        if (!$exitEntryRequest->employee->user->manager || $exitEntryRequest->employee->user->manager->id !== $user->id) {
            return redirect()->route('manager-portal.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $exitEntryRequest->update(['status' => 'rejected']);
        return redirect()->route('manager-portal.index')->with('success', __('Exit/Entry Request rejected successfully.'));
    }

    public function approveExpenseClaim(ExpenseClaim $expenseClaim)
    {
        if (Auth::id() !== $expenseClaim->manager_id) {
            return redirect()->route('manager-portal.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $expenseClaim->update(['status' => 'approved']);
        return redirect()->route('manager-portal.index')->with('success', __('Expense claim approved successfully.'));
    }

    public function rejectExpenseClaim(ExpenseClaim $expenseClaim)
    {
        if (Auth::id() !== $expenseClaim->manager_id) {
            return redirect()->route('manager-portal.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $expenseClaim->update(['status' => 'rejected']);
        return redirect()->route('manager-portal.index')->with('success', __('Expense claim rejected successfully.'));
    }

    public function approveTrainingRequest(TrainingRequest $trainingRequest)
    {
        if (Auth::id() !== $trainingRequest->manager_id) {
            return redirect()->route('manager-portal.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $trainingRequest->update(['status' => 'approved']);
        return redirect()->route('manager-portal.index')->with('success', __('Training request approved successfully.'));
    }

    public function rejectTrainingRequest(TrainingRequest $trainingRequest)
    {
        if (Auth::id() !== $trainingRequest->manager_id) {
            return redirect()->route('manager-portal.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $trainingRequest->update(['status' => 'rejected']);
        return redirect()->route('manager-portal.index')->with('success', __('Training request rejected successfully.'));
    }
}