<?php

namespace App\Http\Controllers;

use App\Models\ProductionProcess;
use App\Models\Batch;
use App\Models\Process;
use App\Models\Employee;
use Illuminate\Http\Request;

class ProductionProcessController extends Controller
{
    // List all assigned processes
    public function index()
    {
        $productionProcesses = ProductionProcess::with(['batch','process','employee'])->get();
        return view('production_processes.index', compact('productionProcesses'));
    }

    // Assign process form
    public function create()
    {
        $batches = Batch::all();
        $processes = Process::all();
        $employees = Employee::all();
        return view('production_processes.create', compact('batches','processes','employees'));
    }

    // Store assigned process
    public function store(Request $request)
    {
        $request->validate([
            'batch_id'           => 'required|exists:batches,id',
            'process_id'         => 'required|exists:processes,id',
            'employee_id'        => 'required|exists:employees,id',
            'assigned_quantity'  => 'required|integer|min:1',
        ]);

        ProductionProcess::create($request->all());

        return redirect()->route('production_processes.index')
                         ->with('success', 'Process assigned successfully.');
    }

    // Edit process assignment / progress
    public function edit(ProductionProcess $productionProcess)
    {
        $batches = Batch::all();
        $processes = Process::all();
        $employees = Employee::all();
        return view('production_processes.edit', compact('productionProcess','batches','processes','employees'));
    }

    // Update process assignment / progress
    public function update(Request $request, ProductionProcess $productionProcess)
    {
        $request->validate([
            'assigned_quantity'  => 'required|integer|min:1',
            'completed_quantity' => 'nullable|integer|min:0|max:'.$request->assigned_quantity,
            'status'             => 'required|in:pending,in progress,completed',
        ]);

        $productionProcess->update([
            'assigned_quantity'  => $request->assigned_quantity,
            'completed_quantity' => $request->completed_quantity ?? 0,
            'status'             => $request->status,
        ]);

        // Optional: Update batch status if all processes are completed
        $batch = $productionProcess->batch;
        if ($batch->productionProcesses()->where('status', '!=', 'completed')->count() === 0) {
            $batch->status = 'completed';
            $batch->save();
        }

        return redirect()->route('production_processes.index')
                         ->with('success', 'Process updated successfully.');
    }

    // Delete process assignment
    public function destroy(ProductionProcess $productionProcess)
    {
        $productionProcess->delete();
        return redirect()->route('production_processes.index')
                         ->with('success', 'Process deleted successfully.');
    }
}
