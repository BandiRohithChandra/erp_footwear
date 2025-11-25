<?php

namespace App\Http\Controllers\Admin;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Invoice;
use App\Models\ProductionOrder;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Show Pending Payments or All Payments
  public function pendingPayments(Request $request)
{
    $query = Order::with('client')->orderBy('created_at', 'desc');

    if ($request->filled('client_id')) {
        $query->where('client_id', $request->client_id);
    }

    if ($request->filled('payment_filter') && $request->payment_filter === 'pending') {
        // Include both pending and partially paid orders
        $query->where(function($q) {
            $q->where('status', 'pending')
              ->orWhereRaw('(total - paid_amount) > 0'); // balance > 0
        });
    }

    $orders = $query->get()->map(function ($o) {
        $items = [];

        if (!empty($o->cart_items)) {
            $decoded = is_string($o->cart_items) ? json_decode($o->cart_items, true) : $o->cart_items;
            $decoded = is_array($decoded) ? $decoded : [];

            $items = array_map(function($item) {
                return [
                    'name' => $item['product']['name'] ?? 'Unknown', // ✅ fixed here
                    'quantity' => isset($item['quantity']) ? (int)$item['quantity'] : 1,
                    'price' => isset($item['price']) ? (float)$item['price'] : 0,
                    'color' => $item['color'] ?? null,
                    'size' => $item['size'] ?? null,
                ];
            }, $decoded);
        }

        return [
            'id' => $o->id,
            'client_id' => $o->client_id,
            'client_name' => $o->client->name ?? 'N/A',
            'items' => $items,
            'total' => isset($o->total) ? (float)$o->total : 0,
            'paid' => isset($o->paid_amount) ? (float)$o->paid_amount : 0,
            'due' => max(($o->total ?? 0) - ($o->paid_amount ?? 0), 0),
            'payment_method' => $o->payment_method ?? 'N/A',
            'status' => $o->status ?? 'pending',
        ];
    })->toArray();

    $clients = User::whereIn('category', ['wholesale', 'retail'])->get();

    return view('admin.orders.pending_payments', compact('orders', 'clients'));
}


public function show($id)
{
    // Fetch the order by ID
    $order = \App\Models\Order::findOrFail($id);

    // Return the view with the order data
    return view('admin.orders.show', compact('order'));
}



public function updatePayment(Request $request, $id)
{
    try {
        $order = Order::findOrFail($id);

        // Validate paid amount
        $paidAmount = floatval($request->paid);

        if ($paidAmount < 0 || $paidAmount > $order->total) {
            return response()->json([
                'success' => false,
                'message' => 'Paid amount must be between 0 and total.'
            ], 422);
        }

        $paidAmount = round(floatval($request->paid), 2);
$orderTotal = round($order->total, 2);

if ($paidAmount <= 0) {
    $status = 'pending';
} elseif ($paidAmount < $orderTotal) {
    $status = 'partial';
} else {
    $status = 'paid';
}

        // Update order
        $order->paid_amount = $paidAmount;
        $order->status = $status;
        $order->save();

        // Notify client
        if ($order->client) {
            $order->client->notify(new OrderStatusUpdated($order));
        }

        // Create invoice if fully paid
        if ($status === 'paid') {
            Invoice::firstOrCreate(
                ['order_id' => $order->id],
                [
                    'client_id' => $order->client_id,
                    'amount' => $order->total,
                    'amount_paid' => $order->total,
                    'items' => $order->cart_items ? json_decode($order->cart_items, true) : [],
                    'status' => 'paid',
                    'payment_type' => $order->payment_method ?? 'cod',
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment & status updated successfully!',
            'paid' => $order->paid_amount,
            'status' => $order->status
        ]);

    } catch (\Exception $e) {
        \Log::error('Update payment failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong while updating the payment.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // Update order status via AJAX
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        // Notify client
        if ($order->client) {
            $order->client->notify(new OrderStatusUpdated($order));
        }

        // Create invoice if status is 'paid' and corresponding production order exists
        if ($order->status === 'paid') {
    Invoice::firstOrCreate(
        ['order_id' => $order->id],
        [
            'client_id' => $order->client_id,
            'amount' => $order->total ?? $order->subtotal,
            'amount_paid' => $order->total ?? $order->subtotal,
            'items' => $order->cart_items ? json_decode($order->cart_items, true) : [],
            'status' => 'paid',
            'payment_type' => $order->payment_method ?? 'cod',
        ]
    );
}


        return response()->json(['success' => true, 'status' => $order->status]);
    }


public function pending(Request $request)
{
    $query = Order::query();

    // Filter only partially paid / unpaid orders
    $query->whereRaw('total - IFNULL(paid_amount, 0) > 0');

    // Filter by client if selected
    if ($request->filled('client_id')) {
        $query->where('client_id', $request->client_id);
    }

    // Eager load related client and user
    $orders = $query->with(['client', 'user'])->get();

    // Fetch only clients with category 'wholesale' or 'retail'
    $clients = \App\Models\User::whereIn('category', ['wholesale', 'retail'])->get();

    return view('admin.orders.pending', compact('orders', 'clients'));
}



public function completed()
{
    $completedOrders = Order::with(['user', 'client'])
        ->where('status', 'delivered')
        ->latest()
        ->get();

    $totalSales = Order::sum('total');
    $completedOrdersCount = $completedOrders->count();

    return view('admin.sales.completed', compact('completedOrders', 'totalSales', 'completedOrdersCount'));
}



    public function export(Request $request)
{
    $clientId = $request->input('client_id');
    $paymentFilter = $request->input('payment_filter', 'pending');
    $searchTerm = $request->input('search_term', '');

    // Fetch orders with client relationship
    $ordersQuery = \App\Models\Order::with('client');

    if ($clientId) {
        $ordersQuery->where('client_id', $clientId);
    }

    if ($paymentFilter === 'pending') {
        $ordersQuery->where('status', 'pending');
    }

    if ($searchTerm) {
        $ordersQuery->where(function($q) use ($searchTerm) {
            $q->where('id', 'like', "%$searchTerm%")
              ->orWhereHas('client', function($q2) use ($searchTerm) {
                  $q2->where('name', 'like', "%$searchTerm%");
              });
        });
    }

    $orders = $ordersQuery->get();

    $response = new StreamedResponse(function() use ($orders) {
        $handle = fopen('php://output', 'w');

        // Header row
        fputcsv($handle, ['Order ID','Client','Products','Total','Paid','Due','Payment Method','Status']);

        foreach ($orders as $order) {
            $items = json_decode($order->cart_items, true);
            $products = collect($items)->map(fn($i) => $i['name'].' x'.$i['quantity'])->implode(' | ');

            fputcsv($handle, [
                $order->id,
                $order->client->name ?? 'N/A',
                $products,
                number_format($order->total ?? 0, 2),
                number_format($order->paid_amount ?? 0, 2),
                number_format(max(($order->total ?? 0) - ($order->paid_amount ?? 0), 0), 2),
                ucfirst($order->payment_method ?? 'N/A'),
                ucfirst($order->status ?? 'N/A'),
            ]);
        }

        fclose($handle);
    });

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="orders_export.csv"');

    return $response;
}
}

