<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkerPayroll;
use App\Models\EmployeeBatch;
use App\Models\SalaryAdvance;
use App\Models\Employee;
use App\Models\Batch;
use App\Models\ProductionProcess;
use Illuminate\Support\Facades\DB;

class WorkerPayrollController extends Controller
{
    // List all employee payrolls
public function index(Request $request)
{

    $statusFilter = $request->get('status', 'all');

    // Load employees with related batches (for labor salary calc)
    $employees = Employee::with('employeeBatches')->get();

    $employees = $employees->map(function ($employee) {
        $position = strtolower($employee->position ?? '');
        $role = strtolower($employee->role ?? '');
        $type = strtolower($employee->employee_type ?? '');
        $status = $employee->status ?? 'active';

        // ✅ Determine employee type
        $isSupervisor = in_array($position, ['supervisor', 'manager', 'foreman'])
            || in_array($role, ['supervisor', 'manager', 'foreman'])
            || in_array($type, ['supervisor', 'manager', 'foreman']);

        $employeeData = [
            'employee_id'   => $employee->id,
            'employee_name' => $employee->name,
            'status'        => $status,
            'type'          => $isSupervisor ? ucfirst($position ?: $role ?: $type) : 'Labor',
        ];

        // === SUPERVISOR / MANAGER LOGIC ===
        if ($isSupervisor) {
            $totalSalary = (float) ($employee->salary ?? 0);
            $totalPaid   = (float) WorkerPayroll::where('employee_id', $employee->id)->sum('amount');

            $advanceRecords = SalaryAdvance::where('employee_id', $employee->id)
                ->whereIn('status', ['Approved', 'Pending'])
                ->get();

            $totalAdvance     = $advanceRecords->sum('amount');
            $usedAdvance      = $advanceRecords->sum('used_amount');
            $remainingAdvance = max($totalAdvance - $usedAdvance, 0);
            $due              = max($totalSalary - $totalPaid - $remainingAdvance, 0);

            // ✅ Normalize payment status (underscore format)
            $paymentStatus = match (true) {
                $totalPaid == 0 => 'pending',
                $due > 0 && $totalPaid > 0 => 'partially_paid',
                $due <= 0 => 'paid',
                default => 'pending',
            };

            $employeeData += [
                'salary'         => number_format($totalSalary, 2, '.', ''),
                'paid'           => number_format($totalPaid, 2, '.', ''),
                'total_advance'  => number_format($totalAdvance, 2, '.', ''),
                'advance'        => number_format($remainingAdvance, 2, '.', ''),
                'due'            => number_format($due, 2, '.', ''),
                'payment_status' => $paymentStatus,
            ];

            
        }

        

        // === LABOR LOGIC ===
        else {
            $batches = $employee->employeeBatches;
            $totalSalary = 0;

            foreach ($batches as $b) {
                if (!in_array(strtolower($b->labor_status), ['pending', 'completed', 'paid', 'partially_paid'])) {
                    continue;
                }

                $rate = $b->labor_rate ?? \App\Models\ProductionProcess::find($b->process_id)?->labor_rate ?? 0;
                $totalSalary += ($b->quantity ?? 0) * $rate;
            }

            $totalPaid = $batches->sum(fn($b) => $b->paid_amount ?? 0);

            $advanceRecords = \App\Models\SalaryAdvance::where('employee_id', $employee->id)
                ->whereIn('status', ['Approved', 'Pending'])
                ->get();

            $totalAdvance     = $advanceRecords->sum('amount');
            $usedAdvance      = $advanceRecords->sum('used_amount');
            $remainingAdvance = max($totalAdvance - $usedAdvance, 0);
            $due              = max($totalSalary - $totalPaid - $remainingAdvance, 0);

            // ✅ Normalize payment status
            $paymentStatus = match (true) {
                $totalPaid == 0 => 'pending',
                $due > 0 && $totalPaid > 0 => 'partially_paid',
                $due <= 0 => 'paid',
                default => 'pending',
            };

            $employeeData += [
                'salary'         => number_format($totalSalary, 2, '.', ''),
                'paid'           => number_format($totalPaid, 2, '.', ''),
                'total_advance'  => number_format($totalAdvance, 2, '.', ''),
                'advance'        => number_format($remainingAdvance, 2, '.', ''),
                'due'            => number_format($due, 2, '.', ''),
                'payment_status' => $paymentStatus,
            ];
        }
        
        return $employeeData;
    });

    // === STATUS FILTERING ===
    if ($statusFilter !== 'all') {
        $employees = $employees->filter(function ($emp) use ($statusFilter) {
            return match ($statusFilter) {
                'active'         => $emp['status'] === 'active',
                'due'            => $emp['due'] > 0,
                'paid'           => strtolower($emp['payment_status']) === 'paid',
                'partially_paid' => strtolower($emp['payment_status']) === 'partially_paid',
                'pending'        => strtolower($emp['payment_status']) === 'pending',
                default          => true,
            };
        });
    }
    
    return view('payrolls.worker_payroll_index', [
        'employees'     => $employees->values(),
        'statusFilter'  => $statusFilter
    ]);
}


public function payNow(Request $request, $employeeId)
{
    $request->validate([
        'deduct_amount' => 'nullable|numeric|min:0',
        'total_pay'     => 'nullable|numeric|min:0',
    ]);

    $deduct = floatval($request->input('deduct_amount', 0));
    $payNow = floatval($request->input('total_pay', 0));

    $employee = Employee::findOrFail($employeeId);

    if ($employee->status !== 'active') {
        return redirect()->back()->with('error', 'Cannot pay. Employee is not active.');
    }

    $position = strtolower($employee->position ?? '');

    // === SUPERVISOR / MANAGER PAYMENT LOGIC ===
    if (in_array($position, ['supervisor', 'manager', 'foreman'])) {
        if ($deduct > 0) {
            $advances = SalaryAdvance::where('employee_id', $employeeId)
                ->whereIn('status', ['Approved', 'Pending'])
                ->orderBy('date')
                ->get();

            foreach ($advances as $adv) {
                if ($deduct <= 0) break;
                $remainingAdv = $adv->amount - ($adv->used_amount ?? 0);
                if ($remainingAdv <= 0) continue;

                $applied = min($deduct, $remainingAdv);
                $adv->used_amount = ($adv->used_amount ?? 0) + $applied;
                $adv->save();
                $deduct -= $applied;
            }
        }

        WorkerPayroll::create([
            'employee_id'  => $employeeId,
            'batch_id'     => null,
            'amount'       => $payNow,
            'total_amount' => $payNow,
            'payment_date' => now(),
            'status'       => 'paid',
        ]);

        return redirect()->back()->with('success', 'Supervisor payment recorded successfully!');
    }

    // === LABOR PAYMENT LOGIC ===
    $batches = EmployeeBatch::where('employee_id', $employeeId)->get();

    // ✅ Calculate total salary & paid from DB
    $totalSalary = $batches->sum(fn($b) => ($b->quantity ?? 0) * ($b->labor_rate ?? 0));
    $totalPaid   = $batches->sum(fn($b) => $b->paid_amount ?? 0);
    $dueAmount   = max($totalSalary - $totalPaid, 0);

    // ✅ Validate entered amount
    if ($payNow > $dueAmount) {
        return redirect()->back()->with('error', "You cannot pay more than the remaining due amount (₹" . number_format($dueAmount, 2) . ").");
    }

    // ✅ Validate advance deduction doesn’t exceed available
    $totalAdvance = SalaryAdvance::where('employee_id', $employeeId)
        ->whereIn('status', ['Approved', 'Pending'])
        ->sum('amount');
    $usedAdvance = SalaryAdvance::where('employee_id', $employeeId)
        ->whereIn('status', ['Approved', 'Pending'])
        ->sum('used_amount');
    $availableAdvance = max($totalAdvance - $usedAdvance, 0);

    if ($deduct > $availableAdvance) {
        return redirect()->back()->with('error', "Deduction cannot exceed available advance (₹" . number_format($availableAdvance, 2) . ").");
    }

    // Apply deduction from SalaryAdvance
    if ($deduct > 0) {
        $advances = SalaryAdvance::where('employee_id', $employeeId)
            ->whereIn('status', ['Approved', 'Pending'])
            ->orderBy('date')
            ->get();

        foreach ($advances as $adv) {
            if ($deduct <= 0) break;
            $remainingAdv = $adv->amount - ($adv->used_amount ?? 0);
            if ($remainingAdv <= 0) continue;

            $applied = min($deduct, $remainingAdv);
            $adv->used_amount = ($adv->used_amount ?? 0) + $applied;
            $adv->save();
            $deduct -= $applied;
        }
    }

    // ✅ Store payment record
    WorkerPayroll::create([
        'employee_id'  => $employeeId,
        'batch_id'     => null,
        'amount'       => $payNow,
        'total_amount' => $payNow,
        'payment_date' => now(),
        'status'       => 'paid',
    ]);

    // ✅ Apply payment across batches proportionally
    foreach ($batches as $batch) {
        $batchSalary = ($batch->quantity ?? 0) * ($batch->labor_rate ?? 0);
        $batchDue    = $batchSalary - ($batch->paid_amount ?? 0);
        if ($batchDue <= 0) continue;

        $payToBatch = min($payNow, $batchDue);
        $batch->paid_amount += $payToBatch;
        $payNow -= $payToBatch;

        // ✅ Set proper payment status
        if ($batch->paid_amount <= 0) {
            $batch->labor_status = 'pending';
        } elseif ($batch->paid_amount < $batchSalary) {
            $batch->labor_status = 'partially_paid';
        } else {
            $batch->labor_status = 'paid';
        }

        $batch->save();

        // ✅ Update production process table accordingly
       if ($batch->process_id) {
    // ✅ Map labor_status to matching enum-friendly process_status
    $processStatus = match ($batch->labor_status) {
        'pending' => 'pending',
        'partially_paid' => 'in_progress', // ✅ matches enum definition
        'paid', 'completed' => 'completed',
        default => 'pending',
    };

    DB::table('production_processes')
        ->where('id', $batch->process_id)
        ->update([
            'status' => $processStatus, // ✅ exact enum-compatible string
            'updated_at' => now(),
        ]);
}


        if ($payNow <= 0) break;
    }

    return redirect()->back()->with('success', 'Labor payment updated successfully!');
}




public function show($employeeId)
{
    // Fetch employee with relationships
    $employee = Employee::with(['employeeBatches.batch', 'workerPayrolls', 'salaryAdvances'])
                        ->findOrFail($employeeId);

    // Correct supervisor/manager check (ONLY BASED ON ROLE)
    $role = strtolower($employee->role ?? '');
    $isSupervisor = in_array($role, ['supervisor', 'manager', 'foreman']);

    /* =====================================================
       =============== SUPERVISOR / MANAGER ================
       ===================================================== */
    if ($isSupervisor) {

        $totalSalary = (float) ($employee->salary ?? 0);

        // Total paid from payroll
        $totalPaid = WorkerPayroll::where('employee_id', $employee->id)->sum('amount');

        // Advances
        $advances = $employee->salaryAdvances->whereIn('status', ['Approved', 'Pending']);
        $remainingAdvance = max($advances->sum('amount') - $advances->sum('used_amount'), 0);

        // Due
        $due = max($totalSalary - $totalPaid - $remainingAdvance, 0);

        // Status
        $overallStatus = match (true) {
            $totalPaid == 0 => 'pending',
            $due > 0 => 'partially_paid',
            default => 'paid',
        };

        return view('payrolls.worker_show', [
            'employee'          => $employee,
            'batches'           => collect([]),  // supervisors have no batches
            'advances'          => $advances,
            'totalSalary'       => $totalSalary,
            'totalPaid'         => $totalPaid,
            'remainingAdvance'  => $remainingAdvance,
            'due'               => $due,
            'paymentHistory'    => $employee->workerPayrolls->sortByDesc('created_at'),
            'overallStatus'     => $overallStatus,
        ]);
    }

    /* =====================================================
       ====================== LABORS ========================
       ===================================================== */

    // Collect unique batch IDs
    $batchIds = $employee->employeeBatches->pluck('batch_id')->unique();

    // Build batches
    $batches = $batchIds->map(function ($batchId) use ($employee) {
        $batchModel = Batch::find($batchId);
        $batchRecords = $employee->employeeBatches->where('batch_id', $batchId);

        $batchSalary = $batchRecords->sum(fn($b) => $b->quantity * ($b->labor_rate ?? 0));
        $totalPaid   = $batchRecords->sum('paid_amount');
        $dueAmount   = max($batchSalary - $totalPaid, 0);

        $paymentStatus = match (true) {
            $totalPaid == 0 => 'pending',
            $dueAmount > 0 && $totalPaid > 0 => 'partially_paid',
            default => 'paid',
        };

        $assignedProcesses = $batchRecords->map(function ($b) {
            $process = ProductionProcess::find($b->process_id);
            $salary  = $b->quantity * ($b->labor_rate ?? 0);
            $paid    = $b->paid_amount ?? 0;
            $due     = max($salary - $paid, 0);

            $processStatus = match (true) {
                $paid == 0 => 'pending',
                $due > 0 && $paid > 0 => 'partially_paid',
                default => 'paid',
            };

            return [
                'process_name' => $process->name ?? 'N/A',
                'assigned_qty' => $b->quantity,
                'rate'         => $b->labor_rate ?? 0,
                'paid'         => $paid,
                'due'          => $due,
                'salary'       => $salary,
                'status'       => $processStatus,
            ];
        });

        return [
            'batch_no'          => $batchModel?->batch_no ?? 'N/A',
            'salary'            => $batchSalary,
            'paid'              => $totalPaid,
            'due'               => $dueAmount,
            'status'            => $paymentStatus,
            'quantity'          => $batchRecords->sum('quantity'),
            'assignedProcesses' => $assignedProcesses,
        ];
    });

    // Totals for labor
    $totalSalary = $batches->sum('salary');
    $totalPaid   = $batches->sum('paid');

    $advances = $employee->salaryAdvances->whereIn('status', ['Approved', 'Pending']);
    $remainingAdvance = max($advances->sum('amount') - $advances->sum('used_amount'), 0);

    $due = max($totalSalary - $totalPaid - $remainingAdvance, 0);

    $overallStatus = match (true) {
        $totalPaid == 0 => 'pending',
        $due > 0 && $totalPaid > 0 => 'partially_paid',
        default => 'paid',
    };

    $paymentHistory = $employee->workerPayrolls->sortByDesc('created_at');

    return view('payrolls.worker_show', compact(
        'employee',
        'batches',
        'advances',
        'totalSalary',
        'totalPaid',
        'remainingAdvance',
        'due',
        'paymentHistory',
        'overallStatus'
    ));
}



    // Toggle employee status (active/on_hold/inactive)
    public function toggleStatus(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        $newStatus = $request->input('status');
        if (!in_array($newStatus, ['active','on_hold','inactive'])) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        $employee->status = $newStatus;
        $employee->save();

        return redirect()->back()->with('success', 'Employee status updated.');
    }
}
