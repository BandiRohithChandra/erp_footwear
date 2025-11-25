<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\ExitEntryRequest;
use App\Models\ExpenseClaim;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\SalaryAdvanceRequest;
use App\Models\TrainingRequest;
use App\Models\WarningLetter;
use App\Notifications\AdvanceSalaryRequestSubmitted;
use App\Notifications\ExpenseClaimSubmitted;
use App\Notifications\LeaveRequestSubmitted;
use App\Notifications\TrainingRequestSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EmployeePortalController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the employee portal dashboard.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Redirect if no employee profile exists
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', __('No employee profile found for your account. Please contact HR to set up your employee profile.'));
        }

        // Fetch recent attendance records
        $attendances = Attendance::where('employee_id', $employee->id)
            ->latest()
            ->take(10)
            ->get();

        // Check for an open attendance record for today
        $today = now()->toDateString();
        $openAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->first();

        // Fetch recent leave requests
        $leaveRequests = LeaveRequest::where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        // Fetch recent salary advance requests
        $salaryAdvanceRequests = SalaryAdvanceRequest::where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        // Fetch recent exit/entry requests
        $exitEntryRequests = ExitEntryRequest::where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        // Fetch recent warning letters
        $warningLetters = WarningLetter::where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        // Fetch recent notifications (warning letters only)
        $notifications = $user->notifications()
            ->where('type', 'App\Notifications\WarningLetterIssued')
            ->latest()
            ->take(5)
            ->get();

        // Fetch recent payslips
        $payslips = Payroll::where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        // Fetch recent expense claims
        $expenseClaims = ExpenseClaim::where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        // Fetch recent training requests
        $trainingRequests = TrainingRequest::where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        $this->authorize('access employee portal');

        return view('employee-portal.index', compact(
            'employee',
            'attendances',
            'openAttendance',
            'leaveRequests',
            'salaryAdvanceRequests',
            'exitEntryRequests',
            'warningLetters',
            'notifications',
            'payslips',
            'expenseClaims',
            'trainingRequests'
        ));
    }

    /**
     * Mark attendance (check-in or check-out) for the employee.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAttendance(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', __('No employee profile found for your account. Please contact HR to set up your employee profile.'));
        }

        $today = now()->toDateString();
        $action = $request->input('action');

        if ($action === 'check-in') {
            $existingAttendance = Attendance::where('employee_id', $employee->id)
                ->where('date', $today)
                ->exists();

            if ($existingAttendance) {
                return redirect()->route('employee-portal.index')->with('error', __('You have already checked in today.'));
            }

            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'check_in' => now(),
                'status' => 'present',
            ]);
            $message = __('Check-in marked successfully.');
        } elseif ($action === 'check-out') {
            $attendance = Attendance::where('employee_id', $employee->id)
                ->where('date', $today)
                ->whereNotNull('check_in')
                ->whereNull('check_out')
                ->first();

            if ($attendance) {
                $attendance->update(['check_out' => now()]);
                $message = __('Check-out marked successfully.');
            } else {
                return redirect()->route('employee-portal.index')->with('error', __('No open check-in found for today.'));
            }
        } else {
            return redirect()->route('employee-portal.index')->with('error', __('Invalid action.'));
        }

        return redirect()->route('employee-portal.index')->with('success', $message);
    }

    /**
     * Submit a leave request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestLeave(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', __('No employee profile found for your account. Please contact HR to set up your employee profile.'));
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
            'leave_type' => 'required|in:annual,sick',
        ]);

        $managerId = $user->manager_id;
        if (!$managerId) {
            return redirect()->route('employee-portal.index')->with('error', __('You do not have a manager assigned. Please contact HR.'));
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
        $manager->notify(new LeaveRequestSubmitted($leaveRequest));

        return redirect()->route('employee-portal.index')->with('success', __('Leave request submitted successfully.'));
    }

    /**
     * Submit a salary advance request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestAdvanceSalary(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('dashboard')->with('error', __('No employee profile found for your account. Please contact HR to set up your employee profile.'));
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
        ]);

        $managerId = $user->manager_id;
        if (!$managerId) {
            return redirect()->route('employee-portal.index')->with('error', __('You do not have a manager assigned. Please contact HR.'));
        }

        $salaryAdvanceRequest = SalaryAdvanceRequest::create([
            'employee_id' => $employee->id,
            'manager_id' => $managerId,
            'amount' => $validated['amount'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        $manager = $user->manager;
        $manager->notify(new AdvanceSalaryRequestSubmitted($salaryAdvanceRequest));

        return redirect()->route('employee-portal.index')->with('success', __('Salary advance request submitted successfully.'));
    }

    /**
     * Display a warning letter.
     *
     * @param \App\Models\WarningLetter $warningLetter
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showWarningLetter(WarningLetter $warningLetter)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee || $warningLetter->employee_id !== $employee->id) {
            return redirect()->route('employee-portal.index')->with('error', __('You are not authorized to view this warning letter.'));
        }

        return view('employee-portal.warning-letter.show', compact('warningLetter'));
    }

    /**
     * Submit an expense claim.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestExpenseClaim(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('employee-portal.index')->with('error', __('No employee profile found for your account. Please contact HR to set up your employee profile.'));
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'expense_date' => 'required|date',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $managerId = $user->manager_id;
        if (!$managerId) {
            return redirect()->route('employee-portal.index')->with('error', __('You do not have a manager assigned. Please contact HR.'));
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('expense-claims', 'public');
        }

        $expenseClaim = ExpenseClaim::create([
            'employee_id' => $employee->id,
            'manager_id' => $managerId,
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'expense_date' => $validated['expense_date'],
            'attachment_path' => $attachmentPath,
            'status' => 'pending',
        ]);

        $manager = $user->manager;
        $manager->notify(new ExpenseClaimSubmitted($expenseClaim));

        return redirect()->route('employee-portal.index')->with('success', __('Expense claim submitted successfully.'));
    }

    /**
     * Submit a training request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestTraining(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('employee-portal.index')->with('error', __('No employee profile found for your account. Please contact HR to set up your employee profile.'));
        }

        $validated = $request->validate([
            'training_title' => 'required|string|max:255',
            'description' => 'required|string',
            'proposed_date' => 'required|date|after:today',
        ]);

        $managerId = $user->manager_id;
        if (!$managerId) {
            return redirect()->route('employee-portal.index')->with('error', __('You do not have a manager assigned. Please contact HR.'));
        }

        $trainingRequest = TrainingRequest::create([
            'employee_id' => $employee->id,
            'manager_id' => $managerId,
            'training_title' => $validated['training_title'],
            'description' => $validated['description'],
            'proposed_date' => $validated['proposed_date'],
            'status' => 'pending',
        ]);

        $manager = $user->manager;
        $manager->notify(new TrainingRequestSubmitted($trainingRequest));

        return redirect()->route('employee-portal.index')->with('success', __('Training request submitted successfully.'));
    }
}