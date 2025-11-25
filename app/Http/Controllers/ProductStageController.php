<?php

namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use Illuminate\Http\Request;

class ProductStageController extends Controller
{
    // Show current stage of a production order
    public function show($id)
    {
        $order = ProductionOrder::findOrFail($id);
        return view('stages.show', compact('order'));
    }

    // Update stage (like moving order from stage 1 -> stage 2)
    public function update(Request $request, $id)
    {
        $order = ProductionOrder::findOrFail($id);

        $request->validate([
            'stage' => 'required|integer|min:1'
        ]);

        $order->stage = $request->stage;
        $order->save();

        return redirect()->back()->with('success', 'Stage updated successfully!');
    }

    // List all orders by stage
    public function index()
    {
        $orders = ProductionOrder::orderBy('stage')->get();
        return view('stages.index', compact('orders'));
    }
}
