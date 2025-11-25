<?php

namespace App\Http\Controllers;

use App\Models\Process;
use App\Models\BatchFlow;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    // Show process flow per batch
    public function index()
    {
        // Fetch all batch flows with their batch info, assignments, processes, and workers
        $batchFlows = BatchFlow::with([
            'batch',
            'assignments.process',
            'assignments.employee'
        ])->orderBy('created_at', 'desc')->get();

        return view('processes.index', compact('batchFlows'));
    }

    // Store a new process
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable|exists:processes,id',
        ]);

        Process::create($request->all());

        return back()->with('success', 'Process added successfully.');
    }

    // Update a process
    public function update(Request $request, Process $process)
    {
        $request->validate([
            'name' => 'required',
            'progress_percent' => 'integer|min:0|max:100',
        ]);

        $process->update($request->all());

        return back()->with('success', 'Process updated successfully.');
    }

    // Delete a process
    public function destroy(Process $process)
    {
        $process->delete();
        return back()->with('success', 'Process deleted.');
    }
}
