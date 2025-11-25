<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Notifications\PayrollApprovalNotification;
use App\Notifications\PayrollDisbursedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;

class PayrollController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage payroll')->only(['index', 'create', 'store', 'createBulk', 'storeBulk', 'approveByManager', 'rejectByManager']);
        $this->middleware('can:approve transactions')->only(['approveByFinance', 'rejectByFinance', 'disburse']);
    }

    public function index(Request $request)
    {
        try {
            $status = $request->query('status', 'pending');
            $payrolls = Payroll::with(['employee.user', 'manager', 'financeApprover'])
                ->where('status', $status)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            \Log::info('Payrolls fetched:', ['count' => $payrolls->count(), 'status' => $status]);

            return view('payrolls.index', compact('payrolls', 'status'));
        } catch (\Exception $e) {
            \Log::error('Error rendering payrolls.index: ' . $e->getMessage());
            return redirect()->route('payrolls.index')->with('error', __('An error occurred while loading payrolls. Please try again.'));
        }
    }

    public function create()
    {
        $employees = Employee::with('user')->get();
        return view('payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        $payroll = new Payroll($validated);
        $payroll->region = config('taxes.default_region', 'in');
        $payroll->calculateTax();
        $payroll->status = 'pending';
        $payroll->save();

        // Notify the employee's manager
        if ($employee->user && $employee->user->manager_id) {
            $manager = \App\Models\User::find($employee->user->manager_id);
            if ($manager) {
                Notification::send($manager, new PayrollApprovalNotification($payroll, 'manager'));
            }
        }

        return redirect()->route('payrolls.index')->with('success', __('Payroll created successfully and is pending manager approval!'));
    }

    public function createBulk()
    {
        $employees = Employee::with('user')->get();
        return view('payrolls.create-bulk', compact('employees'));
    }

    public function storeBulk(Request $request)
    {
        $validated = $request->validate([
            'employees' => 'required|array',
            'employees.*.employee_id' => 'required|exists:employees,id',
            'employees.*.amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $successCount = 0;
        foreach ($validated['employees'] as $employeeData) {
            $employee = Employee::findOrFail($employeeData['employee_id']);
            $payroll = new Payroll([
                'employee_id' => $employee->id,
                'amount' => $employeeData['amount'],
                'payment_date' => $validated['payment_date'],
                'description' => $validated['description'],
            ]);
            $payroll->region = config('taxes.default_region', 'in');
            $payroll->calculateTax();
            $payroll->status = 'pending';
            $payroll->save();

            // Notify the employee's manager
            if ($employee->user && $employee->user->manager_id) {
                $manager = \App\Models\User::find($employee->user->manager_id);
                if ($manager) {
                    Notification::send($manager, new PayrollApprovalNotification($payroll, 'manager'));
                }
            }
            $successCount++;
        }

        return redirect()->route('payrolls.index')->with('success', __(':count payrolls created successfully and are pending manager approval!', ['count' => $successCount]));
    }

    public function approveByManager(Request $request, Payroll $payroll)
    {
        if ($payroll->status !== 'pending') {
            return redirect()->route('payrolls.index')->with('error', __('Payroll cannot be approved as it is not pending.'));
        }

        $employee = $payroll->employee;
        $user = Auth::user();

        if (!$employee->user || $employee->user->manager_id !== $user->id) {
            return redirect()->route('payrolls.index')->with('error', __('You are not authorized to approve this payroll.'));
        }

        $payroll->update([
            'status' => 'manager_approved',
            'manager_id' => $user->id,
        ]);

        // Notify users with 'approve transactions' permission (finance team)
        $financeApprovers = \App\Models\User::permission('approve transactions')->get();
        if ($financeApprovers->isNotEmpty()) {
            Notification::send($financeApprovers, new PayrollApprovalNotification($payroll, 'finance'));
        } else {
            \Log::warning('No users found with "approve transactions" permission to notify for payroll ID: ' . $payroll->id);
        }

        return redirect()->route('payrolls.index')->with('success', __('Payroll approved by manager and is pending finance approval!'));
    }

    public function rejectByManager(Request $request, Payroll $payroll)
    {
        if ($payroll->status !== 'pending') {
            return redirect()->route('payrolls.index')->with('error', __('Payroll cannot be rejected as it is not pending.'));
        }

        $employee = $payroll->employee;
        $user = Auth::user();

        if (!$employee->user || $employee->user->manager_id !== $user->id) {
            return redirect()->route('payrolls.index')->with('error', __('You are not authorized to reject this payroll.'));
        }

        $payroll->update([
            'status' => 'rejected',
            'manager_id' => $user->id,
        ]);

        return redirect()->route('payrolls.index')->with('success', __('Payroll rejected by manager.'));
    }

    public function approveByFinance(Request $request, Payroll $payroll)
    {
        if ($payroll->status !== 'manager_approved') {
            return redirect()->route('payrolls.index')->with('error', __('Payroll cannot be approved as it is not approved by the manager.'));
        }

        $payroll->update([
            'status' => 'finance_approved',
            'finance_approver_id' => Auth::id(),
        ]);

        return redirect()->route('payrolls.index')->with('success', __('Payroll approved by finance and is ready for disbursement!'));
    }

    public function rejectByFinance(Request $request, Payroll $payroll)
    {
        if ($payroll->status !== 'manager_approved') {
            return redirect()->route('payrolls.index')->with('error', __('Payroll cannot be rejected as it is not approved by the manager.'));
        }

        $payroll->update([
            'status' => 'rejected',
            'finance_approver_id' => Auth::id(),
        ]);

        return redirect()->route('payrolls.index')->with('success', __('Payroll rejected by finance.'));
    }

    public function disburse(Request $request, Payroll $payroll)
    {
        if ($payroll->status !== 'finance_approved') {
            return redirect()->route('payrolls.index')->with('error', __('Payroll cannot be disbursed as it is not approved by finance.'));
        }

        $payroll->update([
            'status' => 'disbursed',
            'disbursed_at' => now(),
        ]);

        // Notify the employee
        if ($payroll->employee && $payroll->employee->user) {
            Notification::send($payroll->employee->user, new PayrollDisbursedNotification($payroll));
        } else {
            \Log::warning('No associated user found for employee ID: ' . $payroll->employee_id . ' to notify for payroll disbursement ID: ' . $payroll->id);
        }

        return redirect()->route('payrolls.index')->with('success', __('Payroll disbursed successfully!'));
    }
}