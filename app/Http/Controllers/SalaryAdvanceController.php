<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\SalaryAdvance;
use App\Models\EmployeeBatch;

class SalaryAdvanceController extends Controller
{
    // List all salary advances
    public function index()
    {
        $advances = SalaryAdvance::with('employee')->latest()->paginate(10);

        $advances->getCollection()->transform(function ($advance) {
            $usedAmount = $advance->used_amount ?? 0;
            $remaining = max($advance->amount - $usedAmount, 0);

            $advance->applied_amount = $usedAmount;
            $advance->remaining_amount = $remaining;

            if ($remaining <= 0) {
                $advance->dynamic_status = 'Paid';
            } elseif ($usedAmount > 0) {
                $advance->dynamic_status = 'Partially Paid';
            } else {
                $advance->dynamic_status = 'Pending';
            }

            return $advance;
        });

        return view('salary-advance.index', compact('advances'));
    }

    // Show form to create a new advance
    public function create()
    {
        $employees = Employee::all();
        return view('salary-advance.create', compact('employees'));
    }

    // Store new advance
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
        ]);

        SalaryAdvance::create([
            'employee_id' => $request->employee_id,
            'amount' => $request->amount,
            'used_amount' => 0, // initialize
            'date' => $request->date,
            'status' => 'Pending',
        ]);

        return redirect()->route('salary-advance.index')->with('success', 'Salary advance recorded successfully.');
    }

    // Show single advance
    public function show($id)
    {
        $advance = SalaryAdvance::with('employee')->findOrFail($id);
        $appliedAmount = $advance->used_amount ?? 0;
        $remainingAmount = max($advance->amount - $appliedAmount, 0);

        $advance->applied_amount = $appliedAmount;
        $advance->remaining_amount = $remainingAmount;

        $advance->dynamic_status = $remainingAmount <= 0
            ? 'Paid'
            : ($appliedAmount > 0 ? 'Partially Paid' : 'Pending');

        return view('salary-advance.show', compact('advance'));
    }

    // Show form to edit advance
    public function edit($id)
    {
        $advance = SalaryAdvance::findOrFail($id);
        $employees = Employee::all();
        return view('salary-advance.edit', compact('advance', 'employees'));
    }

    // Update advance
    public function update(Request $request, $id)
    {
        $advance = SalaryAdvance::findOrFail($id);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
        ]);

        $advance->update([
            'employee_id' => $request->employee_id,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        // Recalculate status based on actual applied amount
        $usedAmount = $advance->used_amount ?? 0;
        $remaining = max($advance->amount - $usedAmount, 0);

        $advance->status = $remaining <= 0
            ? 'Paid'
            : ($usedAmount > 0 ? 'Partially Paid' : 'Pending');

        $advance->save();

        return redirect()->route('salary-advance.index')->with('success', 'Salary advance updated successfully.');
    }

    // Delete advance
    public function destroy($id)
    {
        $advance = SalaryAdvance::findOrFail($id);
        $advance->delete();

        return redirect()->route('salary-advance.index')->with('success', 'Salary advance deleted successfully.');
    }
}
