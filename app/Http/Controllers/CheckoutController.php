<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\ProductionOrder;
use App\Notifications\OrderPlaced;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $cart = \App\Models\CartItem::with('product')->where('user_id', $userId)->get();

        if ($cart->isEmpty()) {
            return redirect()->route('client.products')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cart->sum(fn($item) => $item->price * $item->quantity);
        $gstRate = 0.18;
        $gstAmount = round($subtotal * $gstRate, 2);
        $total = round($subtotal + $gstAmount, 2);

        return view('clients.checkout', compact('cart', 'subtotal', 'gstAmount', 'total'));
    }

public function pay(Request $request)
{
    try {
        $useCompanyTransport = $request->boolean('use_company_transport');

        // Log incoming request for debugging
        \Log::info('Pay Request Data:', $request->all());

        // Validation
        $rules = [
            'payment_method' => 'required|string',
            'pay_amount'     => 'required|numeric|min:0',
        ];

        if (!$useCompanyTransport) {
            $rules['transport_name']    = 'required|string|max:255';
            $rules['transport_address'] = 'required|string|max:500';
            $rules['transport_id']      = 'required|string|max:100';
            $rules['transport_phone']   = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        $userId = auth()->id();
        $client = auth()->user();

        // Fetch cart items
        $cart = \App\Models\CartItem::with('product')->where('user_id', $userId)->get();
        if ($cart->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty!']);
        }

        // Subtotal & GST
        $subtotal = $cart->sum(fn($item) => ($item->price ?? $item->product->price) * $item->quantity);
        $gstAmount = round($subtotal * 0.18, 2); // 18% GST
        $total = round($subtotal + $gstAmount, 2);
        $payAmount = min(round(floatval($request->pay_amount), 2), $total);

        if ($payAmount <= 0) {
            return response()->json(['success' => false, 'message' => 'Payment amount must be greater than zero.']);
        }

        // Payment status
        $status = match(true) {
            $payAmount == 0     => 'Pending',
            $payAmount < $total => 'Partially Paid',
            default             => 'Paid',
        };

        // Prepare cart items array
        $cartItemsArray = $cart->map(fn($item) => [
            'product_id' => $item->product_id,
            'name'       => $item->product->name,
            'price'      => $item->price ?? $item->product->price,
            'quantity'   => $item->quantity,
            'color'      => $item->color ?? null,
            'size'       => $item->size ?? null,
            'image'      => $item->image ?? null,
        ]);

        // Transport details
        $transport_name    = $useCompanyTransport ? 'Company Transport Name' : $request->transport_name;
        $transport_address = $useCompanyTransport ? 'Company Address' : $request->transport_address;
        $transport_id      = $useCompanyTransport ? 'COMP123' : $request->transport_id;
        $transport_phone   = $useCompanyTransport ? '0000000000' : $request->transport_phone;

        // Create order
       $order = Order::create([
    'user_id'           => $userId,
    'client_id'         => $userId,
    'customer_name'     => $client->name,
    'cart_items'        => $cartItemsArray->toJson(),
    'subtotal'          => $subtotal,
    'gst'               => $gstAmount,
    'total'             => $total,
    'paid_amount'       => $payAmount,
    'balance'           => round($total - $payAmount, 2),
    'payment_method'    => $request->payment_method,
    'status'            => $status,
    'payment_status'    => $payAmount >= $total ? 'paid' : ($payAmount > 0 ? 'partial' : 'unpaid'),
    'transport_name'    => $transport_name,
    'transport_address' => $transport_address,
    'transport_id'      => $transport_id,
    'transport_phone'   => $transport_phone,
    'quotation_id'      => null,
]);


        // Generate PO number
        $order->po_no = 'PO-' . now()->format('Ymd') . '-' . $order->id;
        $order->save();

        // Attach products
        foreach ($cart as $item) {
            if ($item->product_id && $item->quantity > 0) {
                $order->products()->attach($item->product_id, [
                    'quantity' => $item->quantity,
                    'price'    => $item->price ?? $item->product->price,
                ]);
            }
        }

        // Product-level sales commission
        if ($client->sales_rep_id) {
            $employee = \App\Models\Employee::where('user_id', $client->sales_rep_id)->first();
            if ($employee) {
                $totalCommission = 0;
                $commissionDetails = [];

                foreach ($cart as $item) {
                    $product = $item->product;
                    $price = (float) ($item->price ?? $product->price);
                    $quantity = (int) $item->quantity;
                    $productCommissionRate = is_numeric($product->commission) ? (float)$product->commission : 0;
                    $commission = ($price * $quantity * $productCommissionRate) / 100;

                    $totalCommission += $commission;

                    $commissionDetails[] = [
                        'product_id' => $product->id,
                        'price' => $price,
                        'quantity' => $quantity,
                        'commission_rate' => $productCommissionRate,
                        'commission' => $commission
                    ];
                }

                // Log commission instead of dd
                \Log::info('Sales Commission Debug:', [
                    'client_id' => $client->id,
                    'employee_id' => $employee->id,
                    'commission_details' => $commissionDetails,
                    'total_commission' => $totalCommission,
                    'cart_count' => $cart->count()
                ]);

                \App\Models\SalesCommission::create([
                    'employee_id'       => $employee->id,
                    'client_id'         => $client->id,
                    'order_id'          => $order->id,
                    'commission_amount' => $totalCommission,
                ]);
            } else {
                \Log::warning('No sales employee found for user_id: ' . $client->sales_rep_id);
            }
        }

        // Notify admins
        $admins = User::role('Admin')->where('is_remote', 1)->get();
        Notification::send($admins, new OrderPlaced($order));

        // Create production order
        ProductionOrder::create([
            'client_order_id' => $order->id,
            'status'          => 'pending',
            'stage'           => 1,
            'due_date'        => now()->addDays(7),
        ]);

        // Clear cart
        \App\Models\CartItem::where('user_id', $userId)->delete();

        \Log::info('Checkout Pay Success:', ['order_id' => $order->id, 'request' => $request->all()]);

        return response()->json([
            'success'     => true,
            'message'     => 'Your order has been placed successfully!',
            'order_id'    => $order->id,
            'paid_amount' => $payAmount,
            'total'       => $total,
            'status'      => $status,
            'balance'     => round($total - $payAmount, 2),
        ]);

    } catch (\Throwable $e) {
        \Log::error('Checkout Pay Error: '.$e->getMessage(), [
            'stack' => $e->getTraceAsString(),
            'request' => $request->all(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong: ' . $e->getMessage(),
        ], 500);
    }
}


}
