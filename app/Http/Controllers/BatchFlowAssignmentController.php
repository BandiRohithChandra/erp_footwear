<?php

namespace App\Http\Controllers;

use App\Models\BatchFlowAssignment;
use App\Models\BatchFlow;
use App\Models\Batch;
use App\Models\Process;
use App\Models\Employee;
use Illuminate\Http\Request;

class BatchFlowAssignmentController extends Controller
{
    // List all assignments
    public function index()
    {
        $assignments = BatchFlowAssignment::with(['batchFlow.batch', 'process', 'employee'])
                                         ->orderBy('assigned_at', 'desc')
                                         ->get();

        return view('batch_flow_assignments.index', compact('assignments'));
    }

    // Show create form
    public function create(Request $request)
    {
        $batches = Batch::all(); // Use batches directly
        $processes = Process::all();
        $employees = Employee::where('position', 'worker')->get();
        $selectedBatch = $request->get('batch_id');

        return view('batch_flow_assignments.create', compact(
            'batches',
            'processes',
            'employees',
            'selectedBatch'
        ));
    }

    // Store assignment
    public function store(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'process_id' => 'required|exists:processes,id',
            'worker_id' => 'required|exists:employees,id',
        ]);

        // Find or create a batch flow for this batch
        $batchFlow = BatchFlow::firstOrCreate(
            ['batch_id' => $request->batch_id],
            [
                'status' => 'pending',
                'quantity' => 0,
                'priority' => 'normal'
            ]
        );

        BatchFlowAssignment::create([
            'batch_flow_id' => $batchFlow->id,
            'process_id' => $request->process_id,
            'worker_id' => $request->worker_id,
            'assigned_at' => now(),
        ]);

        return redirect()->route('batch-flow-assignments.index')
                         ->with('success', 'Worker assigned successfully!');
    }


    // Delete an assignment
public function destroy($id)
{
    $assignment = BatchFlowAssignment::findOrFail($id);
    $assignment->delete();

    return redirect()->route('batch-flow-assignments.index')
                     ->with('success', 'Assignment deleted successfully!');
}


// In BatchFlowAssignmentController.php

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,in_progress,completed,on_hold',
    ]);

    $assignment = BatchFlowAssignment::findOrFail($id);
    $assignment->status = $request->status;
    $assignment->save();

    return redirect()->route('batch-flow-assignments.index')
                     ->with('success', 'Status updated successfully!');
}





}
