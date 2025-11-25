<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    // List all batches
    public function index()
    {
        $batches = Batch::with('product')->get();
        return view('batches.index', compact('batches'));
    }

    // Show create batch form
    public function create()
    {
        $products = Product::all();
        return view('batches.create', compact('products'));
    }

    // Store batch
    public function store(Request $request)
    {
        $request->validate([
            'batch_name' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        Batch::create($request->all());

        return redirect()->route('batches.index')->with('success', 'Batch created successfully.');
    }

    // Edit batch
    public function edit(Batch $batch)
    {
        $products = Product::all();
        return view('batches.edit', compact('batch','products'));
    }

    // Update batch
    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'batch_name' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'required|string|in:pending,in_progress,completed',
        ]);

        $batch->update($request->all());

        return redirect()->route('batches.index')->with('success', 'Batch updated successfully.');
    }

    // Delete batch
    public function destroy(Batch $batch)
    {
        $batch->delete();
        return redirect()->route('batches.index')->with('success', 'Batch deleted successfully.');
    }
}
