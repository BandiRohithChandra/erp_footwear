<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Transaction;
use App\Models\Settings;
use App\Models\Quotation;
use App\Models\ProductionOrder;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index(Request $request)
    {

        
        $search = $request->query('search');
        $sales = Sale::query()
            ->with('product')
            ->when($search, function ($query, $search) {
                return $query->where('customer_name', 'like', "%{$search}%")
                             ->orWhere('customer_email', 'like', "%{$search}%")
                             ->orWhereHas('product', function ($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%");
                             });
            })
            ->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::with('warehouses')->get();
        $warehouses = Warehouse::all();
        return view('sales.create', compact('products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'tax_rate_select' => 'required|in:0,5,15,18,20,custom',
            'tax_rate' => 'required_if:tax_rate_select,custom|nullable|numeric|min:0|max:100',
            'sale_date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:1000',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $product = Product::find($validated['product_id']);
        $warehouse = Warehouse::find($validated['warehouse_id']);
        $currentQuantity = $product->warehouses()->where('warehouse_id', $warehouse->id)->first()->pivot->quantity ?? 0;

        if ($currentQuantity < $validated['quantity']) {
            return redirect()->back()->withErrors(['quantity' => __('Insufficient stock available in selected warehouse.')]);
        }

        $taxRate = $validated['tax_rate_select'] === 'custom' ? $validated['tax_rate'] : $validated['tax_rate_select'];
        $taxAmount = $validated['unit_price'] * $validated['quantity'] * ($taxRate / 100);
        $totalAmount = ($validated['unit_price'] * $validated['quantity'] - ($validated['discount'] ?? 0)) + $taxAmount;

        $sale = Sale::create([
            'product_id' => $validated['product_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'quantity' => $validated['quantity'],
            'unit_price' => $validated['unit_price'],
            'discount' => $validated['discount'] ?? 0,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'sale_date' => $validated['sale_date'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'notes' => $validated['notes'],
        ]);

        $product->warehouses()->syncWithoutDetaching([$warehouse->id => ['quantity' => $currentQuantity - $validated['quantity']]]);

        $region = Settings::get('default_region', config('taxes.default_region', 'in'));
        Transaction::create([
            'type' => 'income',
            'category' => 'Sales',
            'amount' => $totalAmount,
            'description' => "Sale of {$product->name} to {$validated['customer_name']}",
            'transaction_date' => $validated['sale_date'],
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'region' => $region,
        ]);

        return redirect()->route('sales.index')->with('success', __('Sale added successfully!'));
    }

    public function show(Sale $sale)
    {
        $sale->load('product', 'warehouse');
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $products = Product::with('warehouses')->get();
        $warehouses = Warehouse::all();
        return view('sales.edit', compact('sale', 'products', 'warehouses'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'tax_rate_select' => 'required|in:0,5,15,18,20,custom',
            'tax_rate' => 'required_if:tax_rate_select,custom|nullable|numeric|min:0|max:100',
            'sale_date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:1000',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $product = Product::find($validated['product_id']);
        $warehouse = Warehouse::find($validated['warehouse_id']);
        $currentQuantity = $product->warehouses()->where('warehouse_id', $warehouse->id)->first()->pivot->quantity ?? 0;

        $originalProduct = Product::find($sale->product_id);
        $originalWarehouse = Warehouse::find($sale->warehouse_id ?? $validated['warehouse_id']);
        $originalQuantity = $originalProduct->warehouses()->where('warehouse_id', $originalWarehouse->id)->first()->pivot->quantity ?? 0;
        $originalProduct->warehouses()->syncWithoutDetaching([$originalWarehouse->id => ['quantity' => $originalQuantity + $sale->quantity]]);

        if ($currentQuantity < $validated['quantity']) {
            return redirect()->back()->withErrors(['quantity' => __('Insufficient stock available in selected warehouse.')]);
        }

        $taxRate = $validated['tax_rate_select'] === 'custom' ? $validated['tax_rate'] : $validated['tax_rate_select'];
        $taxAmount = $validated['unit_price'] * $validated['quantity'] * ($taxRate / 100);
        $totalAmount = ($validated['unit_price'] * $validated['quantity'] - ($validated['discount'] ?? 0)) + $taxAmount;

        $transaction = Transaction::where('description', "Sale of {$originalProduct->name} to {$sale->customer_name}")->first();
        if ($transaction) {
            $transaction->update([
                'amount' => $totalAmount,
                'description' => "Sale of {$product->name} to {$validated['customer_name']}",
                'transaction_date' => $validated['sale_date'],
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
            ]);
        } else {
            $region = Settings::get('default_region', config('taxes.default_region', 'in'));
            Transaction::create([
                'type' => 'income',
                'category' => 'Sales',
                'amount' => $totalAmount,
                'description' => "Sale of {$product->name} to {$validated['customer_name']}",
                'transaction_date' => $validated['sale_date'],
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'region' => $region,
            ]);
        }

        $sale->update([
            'product_id' => $validated['product_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'quantity' => $validated['quantity'],
            'unit_price' => $validated['unit_price'],
            'discount' => $validated['discount'] ?? 0,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'sale_date' => $validated['sale_date'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'notes' => $validated['notes'],
        ]);

        $product->warehouses()->syncWithoutDetaching([$warehouse->id => ['quantity' => $currentQuantity - $validated['quantity']]]);

        return redirect()->route('sales.index')->with('success', __('Sale updated successfully!'));
    }

    public function destroy(Sale $sale)
    {
        $product = Product::find($sale->product_id);
        $warehouse = Warehouse::find($sale->warehouse_id);
        $currentQuantity = $product->warehouses()->where('warehouse_id', $warehouse->id)->first()->pivot->quantity ?? 0;
        $product->warehouses()->syncWithoutDetaching([$warehouse->id => ['quantity' => $currentQuantity + $sale->quantity]]);

        $transaction = Transaction::where('description', "Sale of {$product->name} to {$sale->customer_name}")->first();
        if ($transaction) {
            $transaction->delete();
        }

        $sale->delete();
        return redirect()->route('sales.index')->with('success', __('Sale deleted successfully!'));
    }

    public function export()
    {
        $sales = Sale::with('product')->get();
        $csv = \League\Csv\Writer::createFromString();
        $csv->insertOne(['Product Name', 'Quantity', 'Unit Price', 'Tax Rate', 'Tax Amount', 'Total Amount', 'Sale Date', 'Customer Name', 'Customer Email', 'Notes']);
        foreach ($sales as $sale) {
            $csv->insertOne([
                $sale->product->name,
                $sale->quantity,
                \App\Helpers\FormatMoney::format($sale->unit_price),
                $sale->tax_rate ? $sale->tax_rate . '%' : 'N/A',
                \App\Helpers\FormatMoney::format($sale->tax_amount),
                \App\Helpers\FormatMoney::format($sale->total_amount),
                $sale->sale_date,
                \App\Helpers\FormatMoney::format($sale->sale_date),
                $sale->customer_name,
                $sale->customer_email ?? 'N/A',
                $sale->notes ?? 'N/A',
            ]);
        }
        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sales.csv"',
        ]);
    }

    public function getProductDetails(Request $request)
    {
        Log::info('getProductDetails called (SalesController)', [
            'product_id' => $request->query('product_id'),
            'warehouse_id' => $request->query('warehouse_id'),
        ]);

        $productId = $request->query('product_id');
        $warehouseId = $request->query('warehouse_id');

        $product = Product::find($productId);
        $inventory = $product ? $product->warehouses()->where('warehouse_id', $warehouseId)->first() : null;

        $response = [
            'unit_price' => $product ? $product->unit_price : 0,
            'available_quantity' => $inventory ? $inventory->pivot->quantity : 0,
        ];

        Log::info('getProductDetails response (SalesController)', $response);

        return response()->json($response);
    }




public function dashboard()
{
    // ✅ Fetch production orders with quotation + invoice details
    $orders = ProductionOrder::with('quotation')->get();
    $quotationIds = $orders->pluck('quotation_id')->filter();

    // ✅ Fetch invoices linked via quotation_id
    $invoices = $quotationIds->isNotEmpty()
        ? Invoice::whereIn('quotation_id', $quotationIds)->get()
        : collect();

    // ✅ Total Sales (accepted or delivered production orders)
    $personalTotalSales = $orders
        ->filter(fn($order) => in_array($order->status, ['accepted', 'delivered']))
        ->sum(fn($order) => $order->quotation?->grand_total ?? 0);

    // ✅ Pending Payments (from invoices)
    $personalPendingPayments = $invoices
        ->whereIn('status', ['pending', 'partially_paid', 'not_paid', 'overdue'])
        ->sum(fn($invoice) => ($invoice->amount ?? 0) - ($invoice->amount_paid ?? 0));

    // ✅ Order Count
    $personalOrdersCount = $orders->count();

    // ✅ Clients Count (from Users table)
    $personalClientsCount = User::whereIn('category', ['wholesale', 'retail'])->count();

    // ✅ Invoice Overview
    $personalInvoiceOverviewData = [
        'overdue'        => $invoices->where('status', 'overdue')->sum(fn($i) => ($i->amount ?? 0) - ($i->amount_paid ?? 0)),
        'not_paid'       => $invoices->where('status', 'not_paid')->sum(fn($i) => ($i->amount ?? 0) - ($i->amount_paid ?? 0)),
        'partially_paid' => $invoices->where('status', 'partially_paid')->sum(fn($i) => ($i->amount ?? 0) - ($i->amount_paid ?? 0)),
        'fully_paid'     => $invoices->where('status', 'paid')->sum('amount_paid'),
        'draft'          => $invoices->where('status', 'draft')->sum('amount'),
    ];

    $invoiceOverviewLabels = array_keys($personalInvoiceOverviewData);
    $invoiceOverviewData = array_values($personalInvoiceOverviewData);

    // ✅ Monthly Breakdown
    $months = collect(range(5, 0))
        ->map(fn($i) => Carbon::now()->subMonths($i)->format('M'))
        ->toArray();

    // Monthly sales (quotation grand_total)
    $totalSalesData = collect($months)->map(fn($month) =>
        $orders
            ->filter(fn($order) =>
                in_array($order->status, ['accepted', 'delivered']) &&
                $order->created_at &&
                $order->created_at->format('M') === $month
            )
            ->sum(fn($order) => $order->quotation?->grand_total ?? 0)
    )->toArray();

    // Monthly order count
    $totalOrdersData = collect($months)->map(fn($month) =>
        $orders
            ->filter(fn($order) => $order->created_at && $order->created_at->format('M') === $month)
            ->count()
    )->toArray();

    // Monthly unique clients
    $totalClientsData = collect($months)->map(fn($month) =>
        $orders
            ->filter(fn($order) => $order->created_at && $order->created_at->format('M') === $month)
            ->pluck('quotation.client_id')
            ->unique()
            ->count()
    )->toArray();

    // ✅ Return view
    return view('sales.dashboard', compact(
        'personalTotalSales',
        'personalPendingPayments',
        'personalOrdersCount',
        'personalClientsCount',
        'personalInvoiceOverviewData',
        'invoiceOverviewLabels',
        'invoiceOverviewData',
        'months',
        'totalSalesData',
        'totalOrdersData',
        'totalClientsData'
    ));
}



public function pendingPayments()
{
    $salespersonId = auth()->id();

    // Fetch ProductionOrders linked to this salesperson
    $productionOrders = ProductionOrder::whereIn(
        'quotation_id',
        Quotation::where('salesperson_id', $salespersonId)->pluck('id')
    )->with('order.quotation.client')->get();

    // Fetch invoices via productionOrders
    $invoices = Invoice::whereIn('order_id', $productionOrders->pluck('id'))
                       ->whereIn('status', ['pending', 'partially_paid'])
                       ->with('order.quotation.client')
                       ->get();

    // Total pending payments
    $pendingPayments = $invoices->sum(fn($invoice) => $invoice->total_amount - $invoice->amount_paid);

    // Chart data
    $invoiceStatusLabels = ['Pending', 'Partially Paid'];
    $invoiceStatusData = [
        'pending' => $invoices->where('status','pending')->sum(fn($invoice) => $invoice->total_amount - $invoice->amount_paid),
        'partially_paid' => $invoices->where('status','partially_paid')->sum(fn($invoice) => $invoice->total_amount - $invoice->amount_paid),
    ];

    // Orders ready for invoicing (no invoice yet)
    $orders = $productionOrders->filter(fn($po) => !$po->invoice);

    return view('sales.invoices.pending-payments', compact(
        'invoices',
        'pendingPayments',
        'invoiceStatusLabels',
        'invoiceStatusData',
        'orders'
    ));
}

public function allInvoices()
{

    
    // Load invoices with client relationship
    $invoices = Invoice::with('client')->get();

    // Prepare chart data for the overview
    $invoiceStatusLabels = ['Pending', 'Partially Paid', 'Fully Paid', 'Overdue', 'Draft'];
    $invoiceStatusData = [
        'pending' => $invoices->where('status', 'pending')->sum('amount'),
        'partially_paid' => $invoices->where('status', 'partially_paid')->sum('amount'),
        'fully_paid' => $invoices->where('status', 'fully_paid')->sum('amount'),
        'overdue' => $invoices->where('status', 'overdue')->sum('amount'),
        'draft' => $invoices->where('status', 'draft')->sum('amount'),
    ];


    dd($invoices);


    return view('sales.invoices.index', compact('invoices', 'invoiceStatusLabels', 'invoiceStatusData'));
}


public function generateInvoice(Order $order)
{
    // Only accepted orders can generate invoices
    if ($order->status !== 'accepted') {
        return redirect()->back()->with('error', 'Invoice can only be generated for accepted orders.');
    }

    // Check if invoice already exists via production order
    if ($order->invoice) {
        return redirect()->route('sales.invoices.show', $order->invoice->id)
                         ->with('success', 'Invoice already exists.');
    }

    // Ensure the order has a production order
    $productionOrder = $order->productionOrder;
    if (!$productionOrder) {
        return redirect()->back()->with('error', 'No production order found for this order.');
    }

    // Create invoice using the productionOrder relationship
    $invoice = $productionOrder->invoice()->create([
        'client_id' => $order->client_id,
        'amount' => $order->total,
        'amount_paid' => 0,
        'remaining_balance' => $order->total,
        'status' => 'pending',
        'payment_type' => $order->payment_method ?? 'cod',
    ]);

    return redirect()->route('sales.invoices.show', $invoice->id)
                     ->with('success', 'Invoice generated successfully.');
}

public function showInvoice(Invoice $invoice)
{
    $invoice->load('order.quotation.client', 'order.quotation.products');

    return view('sales.invoices.show', compact('invoice'));
}



}