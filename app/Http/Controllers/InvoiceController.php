<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ProductionOrder;
use App\Models\Quotation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{



private function getGstStateCode($gstNo)
{
    if (!$gstNo || strlen($gstNo) < 2) {
        return null; // Invalid GST
    }
    return substr($gstNo, 0, 2);
}



    // Display all invoices
 public function index(Request $request)
    {
        $query = Invoice::query();

        if ($request->invoice_type && $request->invoice_type !== 'all') {
            $query->where('type', $request->invoice_type);
        }

        $invoices = $query->with(['quotation', 'order'])->get();

        $allTypes = [
            'quotation' => 'Quotation Invoices',
            'order' => 'Order Invoices'
        ];

        return view('sales.invoices.index', compact('invoices', 'allTypes'));
    }





// Show create invoice form for a production order
public function create(ProductionOrder $order, Request $request)
{
    $quotation = $order->quotation()->with(['products', 'invoices'])->first();

    // ✅ Optional PO Number (from modal)
    $poNumber = $request->query('po_no');

    // ✅ Ensure only the assigned salesperson can access this
    if ($quotation->salesperson_id !== auth()->id()) {
        return redirect()->route('sales.invoices.index')
                         ->with('error', __('Unauthorized action.'));
    }

    // ✅ Allow only pending or accepted orders
    if (!in_array($order->status, ['pending', 'accepted'])) {
        return redirect()->route('sales.invoices.index')
                         ->with('error', __('Order is not eligible for invoicing.'));
    }

    // ✅ Calculate total and invoiced quantities
    $totalQty = $quotation->products->sum('pivot.quantity');
    $invoicedQty = 0;

    foreach ($quotation->invoices as $invoice) {
        $items = json_decode($invoice->items, true) ?? [];
        foreach ($items as $item) {
            $invoicedQty += $item['quantity'];
        }
    }

    $remainingQty = $totalQty - $invoicedQty;

    // ❌ Block only if everything has been invoiced
    if ($remainingQty <= 0) {
        return redirect()->route('sales.invoices.index')
                         ->with('error', __('All product quantities have already been invoiced.'));
    }

    // ✅ Pass PO Number to view
    return view('sales.invoices.create', [
        'order' => $order,
        'po_no' => $poNumber, // 👈 add this
    ]);
}


// Store a new invoice
public function store(Request $request, ProductionOrder $order)
{

    // dd($request->all());

    $quotation = $order->quotation;

    // Authorization & status check
    if ($quotation->salesperson_id !== auth()->id() 
        || !in_array($order->status, ['pending', 'accepted'])) {
        return redirect()->route('sales.invoices.index')
                         ->with('error', __('Unauthorized action.'));
    }

    try {
        $validated = $request->validate([
            'payment_type' => 'required|in:immediate,grace',
            'grace_period_days' => 'nullable|required_if:payment_type,grace|integer|min:1',
            'assigned_qty' => 'required|array',
        ]);

        $dueDate = $validated['payment_type'] === 'grace'
            ? now()->addDays((int) $validated['grace_period_days'])
            : null;

        $totalAmount = 0;
        $invoiceItems = [];

        // 🔹 Loop through assigned quantities
        foreach ($validated['assigned_qty'] as $productId => $assignedQty) {
            $assignedQty = (int) $assignedQty;
            if ($assignedQty <= 0) {
                continue; // skip items with no quantity assigned
            }

            $product = $quotation->products()->find($productId);
            if (!$product) {
                return back()->with('error', 'Invalid product ID detected.');
            }

            // 🔸 Calculate remaining qty
            $previousInvoices = Invoice::where('quotation_id', $quotation->id)->get();
            $previousAssignedQty = 0;

            foreach ($previousInvoices as $inv) {
                $items = json_decode($inv->items, true) ?? [];
                foreach ($items as $item) {
                    if ($item['product_id'] == $productId) {
                        $previousAssignedQty += $item['quantity'];
                    }
                }
            }

            $remainingQty = $product->pivot->quantity - $previousAssignedQty;
            if ($remainingQty <= 0) {
                return back()->with('error', "All quantity already invoiced for {$product->name}.");
            }

            if ($assignedQty > $remainingQty) {
                return back()->with('error', "Assigned quantity exceeds remaining quantity for {$product->name}.");
            }

            // Calculate totals for this product
            $itemTotal = $assignedQty * $product->pivot->unit_price;
            $totalAmount += $itemTotal;

            $invoiceItems[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $assignedQty,
                'unit_price' => $product->pivot->unit_price,
                'total' => $itemTotal,
                'variations' => $product->pivot->variations ?? [],
            ];
        }

        // 🔹 If no quantity is assigned, block invoice creation
        if (empty($invoiceItems)) {
            return back()->with('error', 'Please assign at least one product quantity to generate invoice.');
        }

        // 🔹 Create Invoice
        // 🔹 Create Invoice
$invoice = Invoice::create([
    'order_id'      => $order->id,
    'quotation_id'  => $quotation->id,
    'client_id'     => $quotation->client_id,
    'po_no'         => $request->input('po_no') ?? $request->query('po_no'),
    'amount'        => $totalAmount,
    'amount_paid'   => 0.00,
    'items'         => json_encode($invoiceItems),

    // 🔥 THIS WAS MISSING
    'payment_type'  => $validated['payment_type'],
    'grace_period'  => $validated['payment_type'] === 'grace'
                        ? (int) $validated['grace_period_days']
                        : null,

    // Auto calculate due date
    'due_date'      => $validated['payment_type'] === 'grace'
                        ? now()->addDays((int) $validated['grace_period_days'])
                        : null,

    'status' => 'pending',
]);



        // 🔹 Check if all products are fully invoiced
        $allInvoiced = true;
        foreach ($quotation->products as $product) {
            $invoicedQty = 0;
            foreach (Invoice::where('quotation_id', $quotation->id)->get() as $inv) {
                $items = json_decode($inv->items, true) ?? [];
                foreach ($items as $item) {
                    if ($item['product_id'] == $product->id) {
                        $invoicedQty += $item['quantity'];
                    }
                }
            }
            if ($invoicedQty < $product->pivot->quantity) {
                $allInvoiced = false;
                break;
            }
        }

        if ($allInvoiced) {
            $order->update(['status' => 'invoiced']); // optional flag to lock further invoices
        }

        return redirect()->route('sales.invoices.index', $order)
                 ->with('success', __('Invoice generated successfully!'));


    } catch (\Exception $e) {
        \Log::error('Failed to generate invoice', [
            'order_id' => $order->id,
            'error' => $e->getMessage(),
        ]);
        return redirect()->back()
                         ->with('error', __('Failed to generate invoice: ') . $e->getMessage())
                         ->withInput();
    }
}

    // Show pending payments
    public function pendingPayments()
    {
        $quotations = Quotation::where('salesperson_id', auth()->id())
            ->whereNotNull('client_id')
            ->pluck('id');

        $orders = ProductionOrder::whereIn('quotation_id', $quotations)->pluck('id');

        $pendingPayments = Invoice::whereIn('order_id', $orders)
            ->whereColumn('amount_paid', '<', 'amount')
            ->with('order.quotation.client', 'order.products')
            ->get();

        return view('sales.invoices.pending', compact('pendingPayments'));
    }


    
public function show(Invoice $invoice)
{
    // Decode items JSON string into an array
    $invoice->items = json_decode($invoice->items, true) ?? [];

    return view('sales.invoices.show', compact('invoice'));
}



    public function recordPayment(Request $request, Invoice $invoice)
{
    $order = $invoice->order;

    // Use null-safe operator to avoid error if quotation is null
    $quotation = $order?->quotation;

    // Only check salesperson if quotation exists
    if ($quotation && $quotation->salesperson_id !== auth()->id()) {
        return redirect()->route('sales.invoices.index')
            ->with('error', __('Unauthorized action.'));
    }

    if ($invoice->status === 'paid') {
        return redirect()->route('sales.invoices.index')
            ->with('error', __('Invoice is already fully paid.'));
    }

    $validated = $request->validate([
        'amount' => 'required|numeric|min:0.01|max:' . $invoice->remaining_balance,
        'payment_date' => 'required|date',
        'payment_method' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ]);

    try {
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'],
        ]);

        $invoice->amount_paid += $payment->amount;
        $invoice->updateStatus();
        $invoice->save();

        return redirect()->route('sales.invoices.show', $invoice)
            ->with('success', __('Payment recorded successfully!'));
    } catch (\Exception $e) {
        Log::error('Failed to record payment', [
            'invoice_id' => $invoice->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return redirect()->back()
            ->with('error', __('Failed to record payment: ') . $e->getMessage())
            ->withInput();
    }
}


public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,partially_paid,paid,cancelled',
        'amount_paid' => 'nullable|numeric|min:0'
    ]);

    $invoice = Invoice::findOrFail($id);

    // ============================================
    // 🔥 STEP 1: Get SELLER & CUSTOMER GST NUMBERS
    // ============================================
    $sellerGST = "27AMRPK6699L1ZV";  // Your client (company) GST
    $customerGST = $invoice->order->quotation->client->gst_number ?? null;

    // If client has no GST, assume intra-state to avoid errors
    if (!$customerGST) {
        $customerGST = $sellerGST;
    }

    // Extract first 2 digits
    $sellerState = substr($sellerGST, 0, 2);
    $customerState = substr($customerGST, 0, 2);

    // ============================================
    // 🔥 STEP 2: DETERMINE TAX TYPE
    // ============================================
    $isIntra = ($sellerState === $customerState);

    if ($isIntra) {
        // Intra-State → CGST + SGST (2.5% + 2.5%)
        $cgstPercent = 2.5;
        $sgstPercent = 2.5;
        $igstPercent = 0;
    } else {
        // Inter-State → IGST 5%
        $cgstPercent = 0;
        $sgstPercent = 0;
        $igstPercent = 5;
    }

    // ============================================
    // 🔥 STEP 3: CALCULATE GST AMOUNTS
    // ============================================
    $cgstAmount = ($invoice->amount * $cgstPercent) / 100;
    $sgstAmount = ($invoice->amount * $sgstPercent) / 100;
    $igstAmount = ($invoice->amount * $igstPercent) / 100;

    $totalWithGST = $invoice->amount + $cgstAmount + $sgstAmount + $igstAmount;

    // ============================================
    // 🔥 STEP 4: UPDATE STATUS & HANDLE PAYMENTS
    // ============================================
    $invoice->status = $request->status;

    if ($request->status === 'partially_paid') {
        if ($request->filled('amount_paid')) {
            $invoice->amount_paid = min($request->amount_paid, $totalWithGST);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please enter amount paid for partially paid invoices.'
            ]);
        }
    } elseif ($request->status === 'paid') {
        $invoice->amount_paid = $totalWithGST;
    } elseif ($request->status === 'pending' || $request->status === 'cancelled') {
        $invoice->amount_paid = 0;
    }

    $invoice->save();

    return response()->json([
        'success' => true,
        'message' => "Invoice #{$invoice->id} updated to {$invoice->status}.",
        'amount_paid' => $invoice->amount_paid,
        'remaining_balance' => max($totalWithGST - $invoice->amount_paid, 0),
        'total_with_gst' => $totalWithGST,
        'cgst_amount' => $cgstAmount,
        'sgst_amount' => $sgstAmount,
        'igst_amount' => $igstAmount,
        'tax_type' => $isIntra ? 'intra' : 'inter'
    ]);
}


    // Download invoice PDF
    public function downloadPDF(Invoice $invoice)
    {
        $invoice->load(['order.client', 'order.products']);

        $order = $invoice->order;
        $quotation = $order->quotation;

        if ($quotation->salesperson_id !== auth()->id()) {
            return redirect()->route('sales.invoices.index')->with('error', __('Unauthorized action.'));
        }

        $pdf = Pdf::loadView('sales.invoices.pdf', compact('invoice'));
        return $pdf->download('invoice-' . $invoice->id . '.pdf');
    }

public function updatePartialPayment(Request $request, $id)
{
    $request->validate(['partial_amount' => 'required|numeric|min:0']);
    $invoice = Invoice::findOrFail($id);
    $invoice->partial_payment = $request->partial_amount;
    $invoice->save();
    return redirect()->back()->with('success', 'Partial payment updated successfully.');
}



}
