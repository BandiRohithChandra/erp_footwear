<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Notifications\OrderPlaced;
use App\Http\Controllers\Controller;
use App\Models\Order;
use PDF;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    // Store a new client order
public function store(Request $request)
{
   
    $useCompanyTransport = $request->has('use_company_transport') && $request->use_company_transport;

    // Validation rules
    $rules = [
        'po_no' => 'nullable|string|max:100',
        'cart_items' => 'required|array',
        'cart_items.*.product_id' => 'required|exists:products,id',
        'cart_items.*.quantity' => 'required|integer|min:1',
        'cart_items.*.price' => 'required|numeric|min:0',
        'payment_method' => 'required|string',
        'customer_name' => 'required|string|max:255',
        'address' => 'required|string|max:500',
        'city' => 'required|string|max:255',
        'state' => 'required|string|max:255',
        'pincode' => 'required|string|max:20',
        'mobile' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'pan_no' => 'nullable|string|max:20',
    ];

    if (!$useCompanyTransport) {
        $rules = array_merge($rules, [
            'transport_name'    => 'required|string|max:255',
            'transport_address' => 'required|string|max:500',
            'transport_id'      => 'required|string|max:100',
            'transport_phone'   => 'required|string|max:20',
        ]);
    }

    $validated = $request->validate($rules);

    // Calculate subtotal and prepare cart items
    $subtotal = 0;
    $cart_items = [];
    foreach ($validated['cart_items'] as $item) {
        $product = \App\Models\Product::find($item['product_id']);
        if ($product) {
            $variation = json_decode($product->variations, true)[0] ?? [];
            $item['hsn_code'] = $variation['hsn_code'] ?? $product->hsn_code ?? null;

            $cart_items[] = $item;
            $subtotal += $item['quantity'] * $item['price'];
        }
    }

    if (empty($cart_items)) {
        return redirect()->back()->withErrors('No valid products in cart.');
    }

    // GST calculation
    $clientState = $validated['state'];
    $company = \App\Models\User::find(1); 
    $companyState = $company->state ?? 'Maharashtra';
    $gstRate = 18; 
    $cgst = $sgst = $igst = 0;

    if (strtolower($companyState) === strtolower($clientState)) {
        $cgst = $subtotal * ($gstRate / 2) / 100;
        $sgst = $subtotal * ($gstRate / 2) / 100;
    } else {
        $igst = $subtotal * $gstRate / 100;
    }

    $total = $subtotal + $cgst + $sgst + $igst;

    // Transport details
    if ($useCompanyTransport) {
        $transport_name    = $company->business_name ?? 'Company Transport Name';
        $transport_address = $company->address ?? 'Company Address';
        $transport_id      = $company->id ?? 'COMP123';
        $transport_phone   = $company->phone ?? '0000000000';
    } else {
        $transport_name    = $validated['transport_name'];
        $transport_address = $validated['transport_address'];
        $transport_id      = $validated['transport_id'];
        $transport_phone   = $validated['transport_phone'];
    }

    // --- Create order ---
    $order = Order::create([
        'user_id'           => auth()->id(),
        'client_id'         => auth()->id(),
        'po_no'             => null,
        'customer_name'     => $validated['customer_name'],
        'address'           => $validated['address'],
        'city'              => $validated['city'],
        'state'             => $validated['state'],
        'pincode'           => $validated['pincode'],
        'mobile'            => $validated['mobile'],
        'email'             => $validated['email'],
        'pan_no'            => $validated['pan_no'] ?? null,
        'cart_items'        => json_encode($cart_items),
        'subtotal'          => $subtotal,
        'cgst'              => $cgst,
        'sgst'              => $sgst,
        'igst'              => $igst,
        'total'             => $total,
        'paid_amount'       => $total,
        'balance'           => 0,
        'payment_method'    => $validated['payment_method'],
        'status'            => 'paid',
        'transport_name'    => $transport_name,
        'transport_address' => $transport_address,
        'transport_id'      => $transport_id,
        'transport_phone'   => $transport_phone,
    ]);

    // Generate PO number
    $order->po_no = empty($validated['po_no']) 
                    ? 'PO-' . now()->format('Ymd') . '-' . $order->id
                    : $validated['po_no'];
    $order->save();

    // Attach products
    foreach ($cart_items as $item) {
        $order->products()->attach($item['product_id'], [
            'quantity' => $item['quantity'],
            'price'    => $item['price'],
        ]);
    }

    // --- Calculate sales commission per product ---
    // --- Calculate sales commission per product ---
$client = auth()->user();




if ($client->sales_rep_id) {
    $salesRep = User::find($client->sales_rep_id);
    $totalCommission = 0;

    foreach ($cart_items as $item) {
        $product = \App\Models\Product::find($item['product_id']);
        $productCommissionRate = is_numeric($product->commission) ? (float)$product->commission : 0;

        $price = (float) $item['price'];
        $quantity = (int) $item['quantity'];
        $commission = ($price * $quantity * $productCommissionRate) / 100;

        $totalCommission += $commission;

        // Debug using dd
        
    }

    \App\Models\SalesCommission::create([
        'employee_id'       => $salesRep->id,
        'client_id'         => $client->id,
        'order_id'          => $order->id,
        'commission_amount' => $totalCommission,
    ]);
}

    // Notify admins
    $admins = User::role('Admin')->where('is_remote', 1)->get();
    if ($admins->count()) {
        \Illuminate\Support\Facades\Notification::send($admins, new OrderPlaced($order));
    }

    // Create production order
    ProductionOrder::create([
        'client_order_id' => $order->id,
        'status'          => 'pending',
        'stage'           => 1,
        'due_date'        => now()->addDays(7),
    ]);

    return redirect()->route('client.orders.index')
                     ->with('success', 'Order placed successfully! Commission calculated for sales rep.');
}


    // List all orders for the authenticated client with status filter
    public function index(Request $request)
    {
        
        $query = Order::where('user_id', auth()->id())
                      ->with('products')
                      ->latest();
if ($request->filled('status')) {
    $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
}



        $orders = $query->paginate(10)->withQueryString();

        return view('clients.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        $order->cart_items = json_decode($order->cart_items, true);

        return view('clients.orders.show', compact('order'));
    }

    public function invoice($id)
{
    $order = Order::with('products', 'user')->findOrFail($id);

    // If your company is always user_id=1, fetch it directly:
    $company = User::find(1);

    return view('clients.orders.invoice', [
        'order'   => $order,
        'company' => $company,
    ]);
}

public function download($id)
{
    $order = Order::findOrFail($id);

    $pdf = PDF::loadView('clients.orders.show', compact('order'))
              ->setPaper('a4', 'portrait');

    return $pdf->download('Order_'.$order->id.'.pdf');
}

}
