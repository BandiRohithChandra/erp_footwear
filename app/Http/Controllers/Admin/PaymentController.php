<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display all pending payments (only for Admins).
     */
    // public function pendingPayments()
    // {
        
    //     if (!Auth::user() || !Auth::user()->hasRole('Admin')) {
    //         abort(403, 'Unauthorized action.');
    //     }

        
    //     $pendingOrders = Order::where('status', 'pending')->get();

        
    //     return view('admin.payments.pending', compact('pendingOrders'));
    // }

    /**
     * View single order details.
     */
    public function viewOrder(Order $order)
    {
        // Optionally, authorize access here
        return view('admin.payments.view', compact('order'));
    }

    /**
     * Mark order as paid (or completed payment).
     */
    public function markPaid(Order $order)
{
    $order->status = 'completed';
    $order->save();

    return redirect()->route('admin.payments.pending')->with('success', 'Payment marked as paid.');
}

}
