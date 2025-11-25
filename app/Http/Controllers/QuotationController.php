<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Quotation;
use App\Models\ProductionOrder;
use App\Models\Product;
use App\Models\Order;
use App\Models\Employee;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class QuotationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Add permission middleware if you're using Spatie Permission
        // $this->middleware('permission:manage sales', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of quotations with search and filters
     */
    /**
     * Display a listing of quotations with search and filters
     */
   public function index(Request $request)
{
    $search = $request->get('search');
    $status = $request->get('status');
    $clientSearch = $request->get('client');
    $dateFrom = $request->get('date_from');
    $dateTo = $request->get('date_to');

    $query = Quotation::with([
        'client:id,name,business_name,email',
        'salesperson:id,name,email',
        'warehouse:id,name'
    ])->select(
        'id', 'quotation_no', 'client_id', 'salesperson_id',
        'warehouse_id', 'status', 'subtotal', 'tax', 'grand_total',
        'created_at', 'updated_at'
    );

    // Search by quotation number or client name
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('quotation_no', 'like', "%{$search}%")
                ->orWhereHas('client', function ($clientQuery) use ($search) {
                    $clientQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('business_name', 'like', "%{$search}%");
                });
        });
    }

    // Filter by status
    if ($status && $status !== '') {
        $query->where('status', $status);
    }

    // Filter by client
    if ($clientSearch && $clientSearch !== '') {
        $query->where('client_id', $clientSearch);
    }

    // Filter by date range
    if ($dateFrom) {
        $query->whereDate('created_at', '>=', $dateFrom);
    }

    if ($dateTo) {
        $query->whereDate('created_at', '<=', $dateTo);
    }

    // 🔥 Always show latest quotations at the top
    $query->orderBy('id', 'desc');

    $quotations = $query->paginate(10)->appends($request->query());

    // Client dropdown
    $clients = User::whereIn('category', ['wholesale', 'retail'])
        ->select('id', 'name', 'business_name')
        ->get();

    $nextQuotationNo = Quotation::getNextQuotationNumber();

    return view('sales.quotations.index', compact(
        'quotations',
        'clients',
        'search',
        'status',
        'clientSearch',
        'dateFrom',
        'dateTo',
        'nextQuotationNo'
    ));
}


    /**
     * Show the form for creating a new quotation
     */
    public function create()
    {
        $products = Product::all();
        $clients = User::whereIn('category', ['wholesale', 'retail'])->get();
        $warehouses = Warehouse::all();
        $nextQuotationNo = Quotation::getNextQuotationNumber();
        $salesReps = Employee::where('employee_type', 'sales')->get(); // Add this line

        return view('sales.quotations.create', compact('products', 'clients', 'warehouses', 'nextQuotationNo', 'salesReps'));
    }

    /**
     * Store a newly created quotation in storage
     */
   public function store(Request $request)
{
    \Log::info('Quotation form submitted', $request->all());

    // If "Add New Party" was selected, set client_id to null
    if ($request->input('client_id') === 'add-new') {
        $request->merge(['client_id' => null]);
        \Log::info('Client ID was "add-new", set to null');
    }

    // Validation
    $validated = $request->validate([
        'client_id' => 'nullable|exists:users,id',
        'brand_name' => 'nullable|string|max:255',   // <-- UPDATED
        'warehouse_id' => 'nullable|exists:warehouses,id',
        'products' => 'required|array|min:1',
        'products.*.id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.unit_price' => 'required|numeric|min:0',
        'products.*.variations' => 'nullable|array',
        'products.*.variations.*.color' => 'nullable|string|max:50',
        'products.*.variations.*.sizes' => 'nullable|array',
        'products.*.variations.*.images' => 'nullable|array',
        'products.*.sizes' => 'nullable|array',
        'subtotal' => 'required|numeric|min:0',
        'tax' => 'required|numeric|min:0',
        'grand_total' => 'required|numeric|min:0',
        'tax_type' => 'required|in:cgst,igst',
        'notes' => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {

        // Create quotation
        $quotation = Quotation::create([
            'client_id' => $validated['client_id'],
            'brand_name' => $validated['brand_name'] ?? null,   // <-- UPDATED
            'warehouse_id' => $validated['warehouse_id'] ?? null,
            'salesperson_id' => auth()->id(),
            'status' => Quotation::STATUS_PENDING,
            'subtotal' => $validated['subtotal'],
            'tax' => $validated['tax'],
            'grand_total' => $validated['grand_total'],
            'tax_type' => $validated['tax_type'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Remove old products (cleanup)
        $quotation->products()->detach();

        // Attach products
        foreach ($validated['products'] as $productData) {
            $product = \App\Models\Product::find($productData['id']);

            $variations = [];
            $productVariations = is_string($product->variations)
                ? json_decode($product->variations, true)
                : ($product->variations ?? []);

            $firstImage = null;
            if (!empty($productData['variations'][0]['images'][0] ?? null)) {
                $firstImage = $productData['variations'][0]['images'][0];
            } elseif (!empty($productVariations[0]['images'][0] ?? null)) {
                $firstImage = $productVariations[0]['images'][0];
            }

            if (!empty($productData['variations']) && is_array($productData['variations'])) {
                foreach ($productData['variations'] as $variation) {
                    $variations[] = [
                        'color' => $variation['color'] ?? null,
                        'sizes' => $variation['sizes'] ?? [],
                        'images' => $variation['images'] ?? [],
                        'main_image' => $firstImage,
                    ];
                }
            } elseif (!empty($productData['sizes']) && is_array($productData['sizes'])) {
                $variations[] = [
                    'color' => 'Standard',
                    'sizes' => $productData['sizes'],
                    'images' => $productData['images'] ?? [],
                    'main_image' => $firstImage,
                ];
            } else {
                $variations[] = [
                    'color' => 'Standard',
                    'sizes' => [],
                    'images' => [],
                    'main_image' => $firstImage,
                ];
            }

            $pivotData = [
                'quantity' => $productData['quantity'],
                'unit_price' => $productData['unit_price'],
                'variations' => json_encode($variations),
            ];

            $quotation->products()->attach($productData['id'], $pivotData);
        }

        DB::commit();

        \Log::info('Quotation created successfully', [
            'quotation_id' => $quotation->id,
            'quotation_no' => $quotation->quotation_no,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('quotations.index')
            ->with('success', __('Quotation :number created successfully!', [
                'number' => $quotation->quotation_no
            ]));

    } catch (\Exception $e) {
        DB::rollBack();

        \Log::error('Quotation creation failed', [
            'message' => $e->getMessage(),
            'user_id' => auth()->id(),
            'request_data' => $request->all(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()->withInput()->with('error', __('Failed to create quotation. Please try again.'));
    }
}

    /**
     * Display the specified quotation
     */
    public function show(Quotation $quotation)
    {
        // Eager load relations with safe column selection
        $quotation->load([
            'client:id,name,business_name,email,phone',   // Only needed columns
            'warehouse:id,name,address',                  // Only needed columns
            'salesperson:id,name,email',                  // Only needed columns
            'products' => function ($query) {
                $query->withPivot('quantity', 'unit_price', 'variations') // removed article_no
                    ->select('products.id', 'products.name', 'products.sku', 'products.price');
            }

        ]);

        // Get next and previous quotations
        $nextQuotation = Quotation::where('id', '>', $quotation->id)
            ->orderBy('id')
            ->first();

        $previousQuotation = Quotation::where('id', '<', $quotation->id)
            ->orderBy('id', 'desc')
            ->first();

        return view('sales.quotations.show', compact('quotation', 'nextQuotation', 'previousQuotation'));
    }



    /**
     * Create an invoice from a quotation
     */

    public function createInvoice(Quotation $quotation)
    {
        if ($quotation->invoice) {
            return redirect()->route('sales.quotations.invoice', $quotation->invoice->id)
                ->with('info', 'Invoice already exists for this quotation.');
        }

        try {
            $items = $quotation->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                    'unit_price' => $product->pivot->unit_price,
                    'variations' => $product->pivot->variations,
                ];
            });

            $invoice = Invoice::create([
                'quotation_id' => $quotation->id,
                'client_id' => $quotation->client_id,
                'amount' => $quotation->grand_total,
                'amount_paid' => 0.00,
                'items' => $items->isNotEmpty() ? $items->toJson() : null,
                'payment_type' => 'pending',
                'status' => 'pending',
                'due_date' => now()->addDays(30),
            ]);

            return redirect()->route('sales.quotations.invoice', $invoice->id)
                ->with('success', 'Invoice created successfully.');

        } catch (\Exception $e) {
            \Log::error('Invoice creation failed', [
                'quotation_id' => $quotation->id,
                'message' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to create invoice. Please try again.');
        }
    }

    // Show invoice
    public function showInvoice(Invoice $invoice)
    {
        $invoice->load('quotation.client', 'quotation.products');
        return view('sales.quotations.invoice', compact('invoice'));
    }



    /**
     * Show the form for editing the specified quotation
     */
    public function edit(Quotation $quotation)
    {
        $products = Product::all();

        // ✅ Fetch clients by category
        $clients = User::whereIn('category', ['wholesale', 'retail'])->get();

        $warehouses = Warehouse::all();

        $quotation->load('products');

        $formProducts = $quotation->products->map(function ($product) {
            $pivot = $product->pivot;

            $productData = [
                'id' => $product->id,
                'quantity' => $pivot->quantity,
                'unit_price' => $pivot->unit_price,
                'article_no' => $pivot->article_no,
            ];

            if (is_string($pivot->variations)) {
                $decoded = json_decode($pivot->variations, true);
                $productData['variations'] = is_array($decoded) ? $decoded : [];
            } elseif (is_array($pivot->variations)) {
                $productData['variations'] = $pivot->variations;
            } else {
                $productData['variations'] = $product->variations_array ?? [];
            }

            return $productData;
        })->toArray();

        return view('sales.quotations.edit', compact('quotation', 'products', 'clients', 'warehouses', 'formProducts'));
    }


    /**
     * Update the specified quotation in storage
     */
    public function update(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
            'products.*.article_no' => 'nullable|string|max:50',
            'products.*.variations' => 'nullable|array',
            'products.*.variations.*.color' => 'nullable|string|max:50',
            'products.*.variations.*.sizes' => 'nullable|array',
            'products.*.sizes' => 'nullable|array',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'tax_type' => 'required|in:cgst,igst',
            'status' => [
                'sometimes',
                Rule::in([
                    Quotation::STATUS_PENDING,
                    Quotation::STATUS_SENT,
                    Quotation::STATUS_ACCEPTED,
                    Quotation::STATUS_REJECTED,
                    Quotation::STATUS_EXPIRED
                ])
            ],
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Update quotation main fields
            $quotation->update([
                'client_id' => $validated['client_id'],
                'warehouse_id' => $validated['warehouse_id'] ?? null,
                'salesperson_id' => auth()->id(),
                'subtotal' => $validated['subtotal'],
                'tax' => $validated['tax'],
                'grand_total' => $validated['grand_total'],
                'tax_type' => $validated['tax_type'],
                'status' => $validated['status'] ?? $quotation->status,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Prepare products pivot data
            $productData = [];
            foreach ($validated['products'] as $productInput) {
                $pivotData = [
                    'quantity' => $productInput['quantity'],
                    'unit_price' => $productInput['unit_price'],
                    'article_no' => $productInput['article_no'] ?? null,
                ];

                // Handle variations
                $variations = [];
                if (!empty($productInput['variations']) && is_array($productInput['variations'])) {
                    foreach ($productInput['variations'] as $variation) {
                        $variations[] = [
                            'color' => $variation['color'] ?? null,
                            'sizes' => $variation['sizes'] ?? [],
                        ];
                    }
                } elseif (!empty($productInput['sizes']) && is_array($productInput['sizes'])) {
                    $variations[] = [
                        'color' => 'Standard',
                        'sizes' => $productInput['sizes']
                    ];
                }

                $pivotData['variations'] = !empty($variations) ? json_encode($variations) : null;

                $productData[$productInput['id']] = $pivotData;
            }

            // Sync products with pivot data
            $quotation->products()->sync($productData);

            DB::commit();

            return redirect()->route('quotations.index')
                ->with('success', __('Quotation :number updated successfully!', [
                    'number' => $quotation->quotation_no
                ]));
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Quotation update failed: ' . $e->getMessage(), [
                'quotation_id' => $quotation->id,
                'user_id' => auth()->id(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->with('error', __('Failed to update quotation. Please try again.'));
        }
    }


    /**
     * Remove the specified quotation from storage
     */
    public function destroy(Quotation $quotation)
    {
        $quotation->products()->detach(); // detach products if pivot exists
        $quotation->delete();

        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }


    /**
     * Mark quotation as sent
     */
    public function send(Quotation $quotation)
    {
        if (!$quotation->isActionable()) {
            return redirect()->route('quotations.index')
                ->with('error', __('This quotation cannot be sent in its current status.'));
        }

        $quotation->markAsSent();

        // Here you could add email sending logic
        // Mail::to($quotation->client->email)->send(new QuotationSent($quotation));

        return redirect()->route('quotations.index')
            ->with('success', __('Quotation :number sent successfully!', [
                'number' => $quotation->quotation_no
            ]));
    }

    /**
     * Mark quotation as accepted and create production order
     */
    public function accept(Quotation $quotation)
    {
        // 1️⃣ Authorization
        if (!auth()->user()->hasRole('Admin') && auth()->id() !== $quotation->salesperson_id) {
            return redirect()->route('quotations.index')
                ->with('error', 'Unauthorized action.');
        }

        // 2️⃣ Create or find ProductionOrder
        $productionOrder = ProductionOrder::firstOrCreate(
            ['quotation_id' => $quotation->id],
            [
                'client_order_id' => $quotation->client_order_id ?? null, // ✅ use from quotation if available
                'stage' => 1,
                'status' => 'pending',
                'due_date' => now()->addDays(7),
            ]
        );


        // 3️⃣ Attach products if not already attached
        if ($productionOrder->products()->count() === 0) {
            foreach ($quotation->products as $product) {
                $productionOrder->products()->attach($product->id, [
                    'quantity' => $product->pivot->quantity ?? 1,
                    'price' => $product->pivot->unit_price ?? $product->price ?? 0,
                ]);
            }
        }

        // 4️⃣ Update quotation status
        $quotation->update(['status' => Quotation::STATUS_ACCEPTED]);

        // 5️⃣ Prepare products for session
        $productsForSession = $quotation->products->map(function ($product) {
            $variations = is_string($product->pivot->variations)
                ? json_decode($product->pivot->variations, true)
                : ($product->pivot->variations ?? []);

            $formattedVariations = collect($variations)->map(function ($v) {
                return [
                    'color' => $v['color'] ?? '',
                    'sole_color' => $v['sole_color'] ?? '',
                    'sizes' => $v['sizes'] ?? [],
                    'images' => $v['images'] ?? [],
                    'main_image' => $v['main_image'] ?? null,
                ];
            })->toArray();

            // ✅ Determine which image to use
            $image = null;
            if (!empty($formattedVariations[0]['images'][0])) {
                $image = asset('storage/' . $formattedVariations[0]['images'][0]);
            } elseif (!empty($formattedVariations[0]['main_image'])) {
                $image = asset('storage/' . $formattedVariations[0]['main_image']);
            } elseif ($product->image) {
                $image = asset('storage/' . $product->image);
            } else {
                $image = 'https://via.placeholder.com/150?text=No+Image';
            }

            return [
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku ?? 'N/A',
                'quantity' => $product->pivot->quantity ?? 0,
                'unit_price' => $product->pivot->unit_price ?? 0,
                'image' => $image,
                'description' => $product->description ?? null,
                'variations' => $formattedVariations,
                'soles' => $product->soles ?? [],
                'materials' => $product->materials ?? [],
                'processes' => $product->processes ?? [],
                'liquidMaterials' => $product->liquidMaterials ?? [],
            ];
        })->toArray();

        // 6️⃣ Store in session
        session([
            'client_details' => [
                'name' => $quotation->client?->name ?? 'N/A',
                'email' => $quotation->client?->email ?? null,
                'phone' => $quotation->client?->phone ?? null,
                'brand' => $quotation->brand ?? null,
            ],
            'quotation_products' => $productsForSession,
        ]);

        return redirect()
            ->route('production-orders.index')
            ->with('success', 'Quotation accepted. Production Order created.');
    }


    public function bulkAccept(Request $request)
{
    $ids = $request->quotation_ids ?? [];

    if (empty($ids)) {
        return redirect()->route('quotations.index')
            ->with('error', 'No quotations selected.');
    }

    $quotations = Quotation::whereIn('id', $ids)->get();

    foreach ($quotations as $quotation) {

        // Skip already accepted/rejected
        if (!in_array($quotation->status, ['pending', 'sent'])) {
            continue;
        }

        // Authorization check
        if (!auth()->user()->hasRole('Admin') && auth()->id() !== $quotation->salesperson_id) {
            continue;
        }

        // Create or find ProductionOrder
        $productionOrder = ProductionOrder::firstOrCreate(
            ['quotation_id' => $quotation->id],
            [
                'client_order_id' => $quotation->client_order_id ?? null,
                'stage' => 1,
                'status' => 'pending',
                'due_date' => now()->addDays(7),
            ]
        );

        // Attach products if not attached yet
        if ($productionOrder->products()->count() === 0) {
            foreach ($quotation->products as $product) {
                $productionOrder->products()->attach($product->id, [
                    'quantity' => $product->pivot->quantity ?? 1,
                    'price' => $product->pivot->unit_price ?? $product->price ?? 0,
                ]);
            }
        }

        // Update quotation status
        $quotation->update(['status' => Quotation::STATUS_ACCEPTED]);
    }

   return redirect()->route('production-orders.index')
    ->with('success', 'Selected quotations accepted successfully.');

}




    /**
     * Mark quotation as rejected
     */
    public function reject(Quotation $quotation)
    {
        if (!$quotation->isActionable()) {
            return redirect()->route('quotations.index')
                ->with('error', __('This quotation cannot be rejected in its current status.'));
        }

        $quotation->markAsRejected();

        return redirect()->route('quotations.index')
            ->with('success', __('Quotation :number rejected successfully!', [
                'number' => $quotation->quotation_no
            ]));
    }

    /**
     * Mark quotation as expired
     */
    public function expire(Quotation $quotation)
    {
        if (!$quotation->isActionable()) {
            return redirect()->route('quotations.index')
                ->with('error', __('This quotation cannot be marked as expired in its current status.'));
        }

        $quotation->markAsExpired();

        return redirect()->route('quotations.index')
            ->with('success', __('Quotation :number marked as expired!', [
                'number' => $quotation->quotation_no
            ]));
    }

    /**
     * Generate PDF for quotation
     */
    public function print(Quotation $quotation)
    {
        // Eager-load products and pivot data safely
        $quotation->load([
            'products' => function ($query) {
                $query->select('products.id', 'products.name', 'products.sku', 'products.price');
            },
            'client'
        ]);

        return view('quotations.show', compact('quotation'));
    }


    /**
     * Download PDF for quotation
     */
    public function download(Quotation $quotation)
    {
        $quotation->load([
            'client',
            'warehouse',
            'salesperson',
            'products'
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('sales.quotations.print', compact('quotation'))
            ->setPaper('a4', 'portrait');

        $filename = "quotation-{$quotation->quotation_no}-" . now()->format('YmdHis') . ".pdf";

        return $pdf->download($filename);
    }




    /**
     * Get available actions for AJAX requests
     */
    public function getAvailableActions(Quotation $quotation)
    {
        return response()->json($quotation->getAvailableActions());
    }



    public function updateStatus(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    Quotation::STATUS_PENDING,
                    Quotation::STATUS_SENT,
                    Quotation::STATUS_ACCEPTED,
                    Quotation::STATUS_REJECTED,
                    Quotation::STATUS_EXPIRED
                ])
            ],
        ]);

        $quotation->update(['status' => $validated['status']]);

        return redirect()->route('quotations.index')
            ->with('success', __('Quotation :number status updated to :status', [
                'number' => $quotation->quotation_no,
                'status' => ucfirst($validated['status'])
            ]));
    }


    /**
     * Bulk update status for multiple quotations
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'quotation_ids' => 'required|array',
            'quotation_ids.*' => 'exists:quotations,id',
            'action' => 'required|in:send,accept,reject,expire,delete',
        ]);

        $quotations = Quotation::whereIn('id', $validated['quotation_ids'])->get();

        $results = ['success' => 0, 'failed' => 0, 'messages' => []];

        foreach ($quotations as $quotation) {
            try {
                switch ($validated['action']) {
                    case 'send':
                        if ($quotation->isActionable()) {
                            $quotation->markAsSent();
                            $results['success']++;
                            $results['messages'][] = "Sent: {$quotation->quotation_no}";
                        } else {
                            $results['failed']++;
                            $results['messages'][] = "Cannot send {$quotation->quotation_no}: Invalid status";
                        }
                        break;

                    case 'accept':
                        if ($quotation->isActionable()) {
                            $quotation->markAsAccepted();
                            if (!$quotation->hasBeenConverted()) {
                                ProductionOrder::create([
                                    'quotation_id' => $quotation->id,
                                    'status' => 'pending',
                                ]);
                            }
                            $results['success']++;
                            $results['messages'][] = "Accepted: {$quotation->quotation_no}";
                        } else {
                            $results['failed']++;
                            $results['messages'][] = "Cannot accept {$quotation->quotation_no}: Invalid status";
                        }
                        break;

                    case 'reject':
                        if ($quotation->isActionable()) {
                            $quotation->markAsRejected();
                            $results['success']++;
                            $results['messages'][] = "Rejected: {$quotation->quotation_no}";
                        } else {
                            $results['failed']++;
                            $results['messages'][] = "Cannot reject {$quotation->quotation_no}: Invalid status";
                        }
                        break;

                    case 'expire':
                        if ($quotation->isActionable()) {
                            $quotation->markAsExpired();
                            $results['success']++;
                            $results['messages'][] = "Expired: {$quotation->quotation_no}";
                        } else {
                            $results['failed']++;
                            $results['messages'][] = "Cannot expire {$quotation->quotation_no}: Invalid status";
                        }
                        break;

                    case 'delete':
                        if (!$quotation->hasBeenConverted()) {
                            $quotation->products()->detach();
                            $quotation->delete();
                            $results['success']++;
                            $results['messages'][] = "Deleted: {$quotation->quotation_no}";
                        } else {
                            $results['failed']++;
                            $results['messages'][] = "Cannot delete {$quotation->quotation_no}: Has production order";
                        }
                        break;
                }
            } catch (\Exception $e) {
                $results['failed']++;
                $results['messages'][] = "Error processing {$quotation->quotation_no}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'results' => $results,
            'message' => $results['success'] > 0
                ? "Successfully processed {$results['success']} quotation(s)"
                : 'No quotations were processed'
        ]);
    }

    /**
     * API endpoint to get next quotation number
     */
    public function getNextNumber()
    {
        return response()->json([
            'quotation_no' => Quotation::getNextQuotationNumber(),
            'timestamp' => now()->toISOString()
        ]);
    }
}