<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = Worker::all();
        return view('workers.index', compact('workers'));
    }

    public function create()
    {
        return view('workers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|unique:workers,email',
            'phone' => 'nullable|string|max:20',
        ]);

        Worker::create($request->all());

        return redirect()->route('workers.index')->with('success', 'Worker added successfully!');
    }

    public function edit(Worker $worker)
    {
        return view('workers.edit', compact('worker'));
    }

    public function update(Request $request, Worker $worker)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|unique:workers,email,' . $worker->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $worker->update($request->all());

        return redirect()->route('workers.index')->with('success', 'Worker updated successfully!');
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();
        return redirect()->route('workers.index')->with('success', 'Worker deleted successfully!');
    }
}
