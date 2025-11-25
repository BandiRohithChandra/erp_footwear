<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;   // ✅ Add this
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ProductionOrder; 
use App\Models\Supplier;
use App\Models\SupplierOrder;


class SupplyChainController extends Controller
{
    // Dashboard
   public function dashboard() {
    // KPI calculations
    $totalOrders = SupplierOrder::count();
    $activeOrders = SupplierOrder::where('status', '!=', 'delivered')->count();
    $pendingOrders = SupplierOrder::where('status', 'pending')->count();
    $processingOrders = SupplierOrder::where('status', 'processing')->count();
    $deliveredOrders = SupplierOrder::where('status', 'delivered')->count();

    $ontimeOrders = ProductionOrder::whereColumn('due_date', '>=', 'created_at')->count();

    $averageLeadTime = ProductionOrder::whereNotNull('due_date')
        ->get()
        ->map(function($order) {
            return Carbon::parse($order->due_date)->diffInDays($order->created_at);
        })->average();

    // Monthly orders for line chart (last 12 months)
    $monthlyOrders = ProductionOrder::selectRaw("DATE_FORMAT(created_at,'%b %Y') as month, COUNT(*) as count")
                        ->where('created_at', '>=', Carbon::now()->subMonths(12))
                        ->groupBy('month')
                        ->orderByRaw('MIN(created_at)')
                        ->pluck('count','month');

    // Pass data to dashboard view
    return view('supply-chain.dashboard', compact(
        'totalOrders', 'activeOrders', 'pendingOrders', 'processingOrders', 'deliveredOrders',
        'ontimeOrders', 'averageLeadTime', 'monthlyOrders'
    ));
}



   public function active()
{
    // Count details (based only on supplier orders)
    $totalOrders = SupplierOrder::count();
    $activeOrders = SupplierOrder::whereIn('status', ['pending', 'processing'])->count();
    $ontimeOrders = SupplierOrder::where('status', 'delivered')->count();

    // Supplier avg lead time
    $averageLeadTime = SupplierOrder::whereNotNull('expected_delivery')
        ->get()
        ->map(function($order) {
            return \Carbon\Carbon::parse($order->expected_delivery)
                ->diffInDays($order->order_date);
        })
        ->average();

    // 👇 Fetch only supplier active orders
    $activeItems = SupplierOrder::whereIn('status', ['pending', 'processing'])
        ->with('supplier')
        ->get();

    return view('supply-chain.active', compact(
        'totalOrders',
        'activeOrders',
        'ontimeOrders',
        'averageLeadTime',
        'activeItems'
    ));
}


public function ontime()
{
    $today = Carbon::today();

    // ON TIME
    $onTimeItems = DB::table('supplier_orders')
        ->leftJoin('suppliers', 'suppliers.id', '=', 'supplier_orders.supplier_id')
        ->where('supplier_orders.status', 'delivered')
        ->where(function ($q) {
            $q->whereNull('expected_delivery')
              ->orWhereRaw('DATE(supplier_orders.updated_at) <= expected_delivery');
        })
        ->select('supplier_orders.*', 'suppliers.name as supplier_name')
        ->get();

    // DELAYED
    $delayedItems = DB::table('supplier_orders')
        ->leftJoin('suppliers', 'suppliers.id', '=', 'supplier_orders.supplier_id')
        ->where('supplier_orders.status', 'delivered')
        ->whereNotNull('expected_delivery')
        ->whereRaw('DATE(supplier_orders.updated_at) > expected_delivery')
        ->select('supplier_orders.*', 'suppliers.name as supplier_name')
        ->get();

    // OVERDUE
    $overdueItems = DB::table('supplier_orders')
        ->leftJoin('suppliers', 'suppliers.id', '=', 'supplier_orders.supplier_id')
        ->where('supplier_orders.status', 'pending')
        ->whereNotNull('expected_delivery')
        ->whereDate('expected_delivery', '<', $today)
        ->select('supplier_orders.*', 'suppliers.name as supplier_name')
        ->get();

    // AVG LEAD TIME
    $avgLeadTime = DB::table('supplier_orders')
        ->where('status', 'delivered')
        ->selectRaw('AVG(DATEDIFF(updated_at, order_date)) as avg_lead_time')
        ->value('avg_lead_time');

    // ON-TIME %
    $totalCompleted = count($onTimeItems) + count($delayedItems);
    $onTimePercentage = $totalCompleted > 0
        ? round((count($onTimeItems) / $totalCompleted) * 100, 2)
        : 0;

    return view(
        'supply-chain.ontime',
        compact('onTimeItems', 'delayedItems', 'overdueItems', 'avgLeadTime', 'onTimePercentage')
    );
}


}
