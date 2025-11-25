<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\Sole;           // <-- Add this
use App\Models\RawMaterial;    // <-- Add this
use App\Models\LiquidMaterial; // <-- Add this
use App\Models\ProductionProcess;
use App\Models\Stock;         // <-- Add this
use App\Models\StockMovement; // <-- Add this
use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $products = Product::query()
            ->with('warehouses')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->paginate(10);


        return view('products.index', compact('products'));
    }




    public function create()
    {
        // Distinct product details
        $articleNames = Product::distinct()->pluck('name');
        $articleNos = Product::distinct()->pluck('sku');
        $articleTypes = Product::distinct()->pluck('category');

        // Sole details
        // Sole details
        $soleNamesWithDetails = Sole::select(
            'id',
            'name',
            'color',
            'sole_type as subtype',
            'price',      // ✅ correctly named column for Blade template
            'quantity',
            'sizes_qty'
        )->get();



        $materialNamesWithDetails = RawMaterial::where('type', 'Material')
            ->select('name', 'color', 'unit', 'quantity', 'per_unit_length as qty_per_unit', 'price')
            ->get();

        $liquidNamesWithDetails = LiquidMaterial::select(
            'name',
            'unit',
            'quantity',
            'per_unit_volume as qty_per_unit',
            'price'
        )
            ->get();


        // Default processes
        $defaultProcesses = ['Upper Part', 'Lower Part', 'Finished Part'];

        // Pass all to view
        return view('products.create', compact(
            'articleNames',
            'articleNos',
            'articleTypes',
            'soleNamesWithDetails',
            'materialNamesWithDetails',
            'liquidNamesWithDetails',
            'defaultProcesses'
        ));
    }




    public function addItem(Request $request)
    {
        try {
            // 🔄 Normalize field names for frontend
            if ($request->type === 'material') {
                $request->merge([
                    'material_name' => $request->input('name'),
                    'material_color' => $request->input('color'),
                    'material_unit' => $request->input('unit'),
                    'material_quantity' => $request->input('quantity'),
                    'material_price' => $request->input('price'),
                    'material_per_unit_length' => $request->input('per_unit_length'),
                ]);
            }

            if ($request->type === 'liquid') {
                $request->merge([
                    'liquid_name' => $request->input('name'),
                    'liquid_unit' => $request->input('unit'),
                    'liquid_quantity' => $request->input('quantity'),
                    'liquid_price' => $request->input('price'),
                    'liquid_per_unit_volume' => $request->input('per_unit_volume'),
                ]);
            }

            // ✅ Validation rules
            $request->validate([
                'type' => 'required|in:sole,material,liquid',

                // Sole
                'sole_id' => 'nullable|exists:soles,id',
                'name' => 'required_if:type,sole|string|max:255',
                'color' => 'nullable|string|max:100',
                'price' => 'required_if:type,sole|numeric|min:0',
                'sole_type' => 'nullable|string|max:100',
                'quantity' => 'nullable|numeric|min:0|max:999999.99',
                'sizes_qty' => 'nullable|array',
                'sizes_qty.*' => 'nullable|numeric|min:0|max:999999.99',

                // Material
                'material_name' => 'required_if:type,material|string|max:255',
                'material_color' => 'nullable|string|max:100',
                'material_unit' => 'required_if:type,material|in:kg,g,metre,piece,litre,ml',
                'material_quantity' => 'required_if:type,material|numeric|min:0|max:999999.99',
                'material_price' => 'nullable|numeric|min:0|max:999999.99',
                'material_per_unit_length' => 'required_if:material_unit,piece|numeric|min:0.01|max:999999.99|nullable',

                // Liquid
                'liquid_name' => 'required_if:type,liquid|string|max:255',
                'liquid_unit' => 'required_if:type,liquid|in:litre,ml,kg,g,piece',
                'liquid_quantity' => 'required_if:type,liquid|numeric|min:0|max:999999.99',
                'liquid_price' => 'required_if:type,liquid|numeric|min:0|max:999999.99',
                'liquid_per_unit_volume' => 'required_if:liquid_unit,piece|numeric|min:0.01|max:999999.99|nullable',
            ]);

            $itemType = $request->input('type');
            $responseData = [];

            DB::beginTransaction();

            switch ($itemType) {
                // 🟢 SOLE
                case 'sole':
                    if ($request->filled('sole_id')) {
                        $sole = Sole::findOrFail($request->sole_id);
                        $responseData = [
                            'id' => $sole->id,
                            'name' => $sole->name,
                            'color' => $sole->color,
                            'sole_type' => $sole->sole_type,
                            'quantity' => floatval($sole->quantity),
                            'price' => floatval($sole->price),
                            'sizes_qty' => json_decode($sole->sizes_qty, true),
                            'type' => 'Sole',
                            'linked_existing' => true,
                        ];
                    } else {
                        $normalizedName = strtolower(trim($request->input('name')));

                        // 🧠 Check duplicate sole (ignore color)
                        $duplicate = Sole::whereRaw('LOWER(name) = ?', [$normalizedName])->first();

                        if ($duplicate) {
                            return response()->json([
                                'success' => false,
                                'message' => "Duplicate sole found: '{$duplicate->name}' already exists.",
                            ], 409);
                        }

                        // ✅ Create Sole
                        $sizes_qty = $request->input('sizes_qty', []);
                        foreach ($sizes_qty as $size => $qty) {
                            $sizes_qty[$size] = floatval($qty) ?: 0;
                        }
                        $total_qty = array_sum($sizes_qty);

                        $sole = Sole::create([
                            'name' => $request->input('name'),
                            'color' => $request->input('color', '-'),
                            'sole_type' => $request->input('sole_type'),
                            'quantity' => $total_qty,
                            'price' => $request->input('price', 0),
                            'sizes_qty' => json_encode($sizes_qty),
                        ]);

                        foreach ($sizes_qty as $size => $qty) {
                            // 🧩 Only create stock if the sole already existed earlier
                            $isNewSole = $sole->wasRecentlyCreated || $sole->created_at->gt(now()->subMinutes(2));

                            if ($qty > 0 && !$isNewSole) {
                                // Update or create stock record for existing soles
                                $stock = Stock::updateOrCreate(
                                    [
                                        'item_id' => $sole->id,
                                        'type' => 'sole',
                                        'size' => $size,
                                    ],
                                    [
                                        'qty_available' => $qty,
                                    ]
                                );

                                // Record the stock movement
                                StockMovement::create([
                                    'item_id' => $sole->id,
                                    'type' => 'sole',
                                    'size' => $size,
                                    'change' => $qty,
                                    'qty_after' => $stock->qty_available,
                                    'description' => "Manual update for existing sole size {$size}",
                                ]);

                                \Log::info("✅ Stock & Movement created for existing sole ID {$sole->id}, size {$size}");
                            } else {
                                // Skip for brand new soles
                                \Log::info("🛑 Skipped stock creation for new sole ID {$sole->id}, size {$size}");
                            }
                        }


                        $responseData = [
                            'id' => $sole->id,
                            'name' => $sole->name,
                            'color' => $sole->color,
                            'sole_type' => $sole->sole_type,
                            'quantity' => floatval($sole->quantity),
                            'price' => floatval($sole->price),
                            'sizes_qty' => $sizes_qty,
                            'type' => 'Sole',
                            'linked_existing' => false,
                        ];
                    }
                    break;

                // 🟠 MATERIAL
                case 'material':
                    $normalizedName = strtolower(trim($request->input('material_name')));
                    $normalizedColor = strtolower(trim($request->input('material_color', '-')));
                    $unit = $request->input('material_unit');

                    // 🧠 Check duplicate material
                    $duplicate = RawMaterial::whereRaw('LOWER(name) = ?', [$normalizedName])
                        ->whereRaw('LOWER(color) = ?', [$normalizedColor])
                        ->where('unit', $unit)
                        ->where('type', 'Material')
                        ->whereNull('product_id')
                        ->first();

                    if ($duplicate) {
                        return response()->json([
                            'success' => false,
                            'message' => "Duplicate material found: {$duplicate->name} ({$duplicate->color}) already exists.",
                        ], 409);
                    }

                    $quantity = floatval($request->input('material_quantity', 0));
                    $per_unit_length = $request->has('material_per_unit_length')
                        ? floatval($request->input('material_per_unit_length', 0))
                        : null;

                    $price = floatval($request->input('material_price', 0));

                    $material = RawMaterial::create([
                        'name' => $request->input('material_name'),
                        'color' => $request->input('material_color', '-'),
                        'unit' => $unit,
                        'quantity' => $quantity,
                        'price' => $price,
                        'type' => 'Material',
                        'per_unit_length' => $per_unit_length,
                    ]);

                    if ($quantity > 0) {
                        $stock = Stock::create([
                            'item_id' => $material->id,
                            'type' => 'material',
                            'qty_available' => $quantity,
                        ]);
                        StockMovement::create([
                            'item_id' => $material->id,
                            'type' => 'material',
                            'change' => $quantity,
                            'qty_after' => $stock->qty_available,
                            'description' => 'Initial stock for material',
                        ]);
                    }

                    $responseData = [
                        'id' => $material->id,
                        'name' => $material->name,
                        'color' => $material->color,
                        'unit' => $material->unit,
                        'quantity' => floatval($material->quantity),
                        'price' => floatval($material->price),
                        'per_unit_length' => $material->per_unit_length,
                        'type' => 'Material',
                    ];
                    break;

                // 🔵 LIQUID
                case 'liquid':
                    $unit = $request->input('liquid_unit');
                    $quantity = floatval($request->input('liquid_quantity', 0));
                    $per_unit_volume = $unit === 'piece' ? floatval($request->input('liquid_per_unit_volume', 1)) : null;
                    $price = floatval($request->input('liquid_price', 0));

                    $liquid = LiquidMaterial::create([
                        'name' => $request->input('liquid_name'),
                        'unit' => $unit,
                        'quantity' => $quantity,
                        'price' => $price,
                        'per_unit_volume' => $per_unit_volume,
                    ]);

                    if ($quantity > 0) {
                        $stock = Stock::create([
                            'item_id' => $liquid->id,
                            'type' => 'liquid',
                            'qty_available' => $quantity,
                        ]);
                        StockMovement::create([
                            'item_id' => $liquid->id,
                            'type' => 'liquid',
                            'change' => $quantity,
                            'qty_after' => $stock->qty_available,
                            'description' => 'Initial stock for liquid material',
                        ]);
                    }

                    $responseData = [
                        'id' => $liquid->id,
                        'name' => $liquid->name,
                        'unit' => $liquid->unit,
                        'quantity' => floatval($liquid->quantity),
                        'price' => floatval($liquid->price),
                        'per_unit_volume' => $liquid->per_unit_volume,
                        'type' => 'Liquid Material',
                    ];
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ucfirst($itemType) . ' added successfully',
                'data' => $responseData,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }



    public function store(Request $request)
    {
        \Log::info('🟢 Store method called', ['request_data' => $request->all()]);

        // 1️⃣ Validation
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:255|unique:products,sku',
                'category' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',

                // Variations
                'color' => 'required|array|min:1',
                'color.*' => 'required|string|max:50',
                'size' => 'required|array|min:1',
                'size.*' => 'required|array|min:1',
                'size.*.*' => 'required|string|max:50',
                'price' => 'nullable|numeric|min:0',

                'quantity' => 'nullable|array',
                'quantity.*' => 'nullable|integer|min:0',
                'images.*.*' => 'sometimes|image|max:10240',

                // Commission
                'commission' => 'nullable|numeric|min:0|max:100',

                // Processes
                'process_flow' => 'nullable|array',
                'process_flow.*' => 'nullable|string|max:255',
                'process_qty' => 'nullable|array',
                'process_qty.*' => 'nullable|integer|min:0',
                'labor_rate' => 'nullable|array',
                'labor_rate.*' => 'nullable|numeric|min:0',
                'process_order' => 'nullable|array',
                'process_order.*' => 'nullable|integer|min:1',

                // Materials
                'material_name.*' => 'nullable|string|max:255',
                'material_color.*' => 'nullable|string|max:255',
                'material_unit.*' => 'nullable|in:kg,g,metre,piece',
                'material_quantity.*' => 'nullable|numeric|min:0',
                'material_per_unit_length.*' => 'nullable|required_if:material_unit.*,piece|numeric|min:0.01',
                'material_price.*' => 'nullable|numeric|min:0', // Add price validation

                // Soles
                'sole_id.*' => 'nullable|integer|exists:soles,id', // for existing soles
                'sole_name_or_article_no.*' => 'nullable|string|max:255', // for new soles
                'sole_color.*' => 'nullable|string|max:255',
                'sole_price.*' => 'nullable|numeric|min:0',
                'sole_sub_type.*' => 'nullable|string|max:255',
                'sole_quantity.*' => 'nullable|numeric|min:0',
                'sizes_qty' => 'nullable|array',
            ]);
            \Log::info('✅ Validation passed');
        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Log::error('❌ Validation failed', ['errors' => $ve->errors()]);
            return back()->withErrors($ve->errors())->withInput();
        }

        DB::beginTransaction();

        try {
            // 2️⃣ Product Variations
            $variationsData = [];
            $totalQuantity = 0;

            foreach ($validated['color'] as $i => $color) {
                $imagePaths = [];

                // ✅ Use old working logic for image uploads
                if ($request->hasFile("images.$i")) {
                    foreach ($request->file("images.$i") as $imageFile) {
                        if ($imageFile->isValid()) {
                            $filename = time() . '_' . $imageFile->getClientOriginalName();

                            // ⬇️ OLD PATH (same as your working version)
                            $destination = $_SERVER['DOCUMENT_ROOT'] . '/storage/products/variations';

                            if (!file_exists($destination)) {
                                mkdir($destination, 0777, true);
                            }

                            $imageFile->move($destination, $filename);
                            $imagePaths[] = 'products/variations/' . $filename;
                        }
                    }
                }

                $qty = $validated['quantity'][$i] ?? 0;
                $totalQuantity += $qty;

                $variationsData[] = [
                    'color' => $color,
                    'sizes' => $validated['size'][$i] ?? [],
                    'quantity' => $qty,
                    'hsn_code' => '6402',
                    'images' => $imagePaths,
                ];
            }

            // 3️⃣ Create Product
            $product = Product::create([
                'name' => $validated['name'],
                'sku' => $validated['sku'],
                'category' => $validated['category'] ?? 'Uncategorized',
                'description' => $validated['description'] ?? null,
                'variations' => $variationsData,
                'price' => $validated['price'] ?? null,

                'total_quantity' => $totalQuantity,
                'hsn_code' => '6402',
                'commission' => $validated['commission'] ?? 0,
            ]);
            \Log::info('✅ Product created', ['product_id' => $product->id]);

            // 4️⃣ Attach Processes
            $processPivotData = [];

            if (!empty($validated['process_flow'])) {
                foreach ($validated['process_flow'] as $i => $processName) {
                    if ($processName) {
                        $process = Process::firstOrCreate(['name' => $processName]);

                        $processPivotData[$process->id] = [
                            'labor_rate' => $validated['labor_rate'][$i] ?? 0,
                            'quantity' => $validated['process_qty'][$i] ?? 0,
                            'process_order' => $validated['process_order'][$i] ?? 0,
                        ];
                    }
                }
            }

            // Replace existing processes with exactly these
            $product->processes()->sync($processPivotData);

            // 5️⃣ Raw Materials
            $materialPivotData = [];

            if (!empty($validated['material_name'])) {
                foreach ($validated['material_name'] as $i => $name) {
                    if ($name && !empty($validated['material_quantity'][$i])) {
                        $color = $validated['material_color'][$i] ?? '-';
                        $unit = $validated['material_unit'][$i] ?? 'metre';
                        $quantity = floatval($validated['material_quantity'][$i] ?? 0);
                        $per_unit_length = $unit === 'piece' ? floatval($validated['material_per_unit_length'][$i] ?? 0) : null;
                        $price = floatval($validated['material_price'][$i] ?? 0);

                        // Calculate total measurement
                        $total_measurement = $unit === 'piece' ? $quantity * $per_unit_length : $quantity;

                        // Check for existing global material
                        $material = RawMaterial::where([
                            'name' => $name,
                            'color' => $color,
                            'unit' => $unit,
                            'type' => 'Material',
                            'product_id' => null, // Global material
                        ])->first();

                        if (!$material) {
                            \Log::info('Creating new global material...', [
                                'name' => $name,
                                'color' => $color,
                                'unit' => $unit,
                            ]);

                            // Create new global material
                            $material = RawMaterial::create([
                                'name' => $name,
                                'color' => $color,
                                'unit' => $unit,
                                'type' => 'Material',
                                'product_id' => null,
                                'quantity' => $total_measurement,
                                'price' => $price,
                                'per_unit_length' => $per_unit_length,
                            ]);
                        } else {
                            \Log::info('Updating existing global material...', [
                                'material_id' => $material->id,
                                'name' => $name,
                                'color' => $color,
                            ]);

                            // Update quantity for existing material
                            $material->update([
                                'quantity' => $material->quantity + $total_measurement,
                                'price' => $price,
                                'per_unit_length' => $per_unit_length,
                                'type' => 'Material',
                            ]);
                        }

                        // Add to pivot table data
                        $materialPivotData[$material->id] = [
                            'quantity_used' => $quantity,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        // Update stock for the global material
                        if ($total_measurement > 0) {
                            \Log::info('Updating stock for material...', ['material_id' => $material->id]);
                            Stock::updateOrCreate(
                                [
                                    'item_id' => $material->id,
                                    'type' => 'material',
                                    'size' => null,
                                ],
                                [
                                    'qty_available' => $material->quantity,
                                    'in_transit_qty' => 0,
                                ]
                            );

                            StockMovement::create([
                                'item_id' => $material->id,
                                'type' => 'material',
                                'change' => $total_measurement,
                                'qty_after' => $material->quantity,
                                'description' => 'Stock updated for material linked to product ' . $product->id,
                            ]);
                        }
                    }
                }
            }

            // Attach materials to product via pivot table
            if (!empty($materialPivotData)) {
                \Log::info('Linking materials to product...', ['product_id' => $product->id, 'materials' => $materialPivotData]);
                $product->materials()->syncWithoutDetaching($materialPivotData);
            }

            // 6️⃣ Soles: Link existing or create new
            $solePivotData = [];
            $newSoleIds = []; // track newly created soles

            $totalSoles = max(
                count($validated['sole_id'] ?? []),
                count($validated['sole_name_or_article_no'] ?? [])
            );

            for ($i = 0; $i < $totalSoles; $i++) {
                $sole = null;

                // 1️⃣ Existing sole by ID
                if (!empty($validated['sole_id'][$i])) {
                    $sole = Sole::find($validated['sole_id'][$i]);
                    if ($sole) {
                        $solePivotData[$sole->id] = ['quantity_used' => 1];
                    }
                }

                // 2️⃣ New sole by name + color
                if (!$sole && !empty($validated['sole_name_or_article_no'][$i])) {
                    $name = trim($validated['sole_name_or_article_no'][$i]);
                    $color = trim($validated['sole_color'][$i] ?? '');
                    if (empty($name) || empty($color))
                        continue;

                    $sub_type = $validated['sole_sub_type'][$i] ?? null;
                    $price = $validated['sole_price'][$i] ?? 0;

                    // Initialize sizes (35–44)
                    $sizes_qty = $validated['sizes_qty'][$i] ?? [];
                    foreach (range(35, 44) as $size) {
                        $sizes_qty[$size] = isset($sizes_qty[$size]) ? floatval($sizes_qty[$size]) : 0;
                    }
                    $total_qty = array_sum($sizes_qty);

                    // Check if sole already exists (by name + color)
                    $sole = Sole::where('name', $name)->where('color', $color)->first();

                    if (!$sole) {
                        // 🆕 Create new sole
                        $sole = Sole::create([
                            'product_id' => $product->id,
                            'name' => $name,
                            'color' => $color,
                            'sole_type' => $sub_type,
                            'unit_price' => $price,
                            'quantity' => $total_qty,
                            'sizes_qty' => json_encode($sizes_qty),
                        ]);

                        $newSoleIds[] = $sole->id; // mark as new
                    }

                    // 🧱 Handle stock initialization / update
                    foreach ($sizes_qty as $size => $qty) {
                        $qty = floatval($qty ?? 0);

                        if (in_array($sole->id, $newSoleIds)) {
                            // Brand-new sole → always initialize with 0 qty
                            Stock::firstOrCreate([
                                'item_id' => $sole->id,
                                'type' => 'sole',
                                'size' => $size,
                            ], [
                                'qty_available' => 0,
                                'in_transit_qty' => 0,
                            ]);

                            StockMovement::create([
                                'item_id' => $sole->id,
                                'type' => 'sole',
                                'size' => $size,
                                'change' => 0,
                                'qty_after' => 0,
                                'description' => "Initialized stock for new sole '{$sole->name}' (size {$size})",
                            ]);

                            \Log::info("🆕 Initialized new sole '{$sole->name}' (ID {$sole->id}, size {$size})");
                        } else {
                            // Existing sole → only update if qty > 0
                            if ($qty > 0) {
                                $stock = Stock::updateOrCreate(
                                    [
                                        'item_id' => $sole->id,
                                        'type' => 'sole',
                                        'size' => $size,
                                    ],
                                    [
                                        'qty_available' => DB::raw("GREATEST(qty_available + {$qty}, 0)"),
                                    ]
                                );

                                StockMovement::create([
                                    'item_id' => $sole->id,
                                    'type' => 'sole',
                                    'size' => $size,
                                    'change' => $qty,
                                    'qty_after' => $stock->qty_available,
                                    'description' => "Initial stock update for existing sole '{$sole->name}' (size {$size})",
                                ]);

                                \Log::info("✅ Updated existing sole '{$sole->name}' (ID {$sole->id}, size {$size}, qty {$qty})");
                            }
                        }
                    }

                    // Link sole to product
                    $solePivotData[$sole->id] = ['quantity_used' => 1];
                }
            }

            // Attach soles to product
            if (!empty($solePivotData)) {
                $product->soles()->syncWithoutDetaching($solePivotData);
            }


            DB::commit();
            \Log::info('🎯 Product stored successfully', ['product_id' => $product->id]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product added successfully',
                    'product' => $product,
                ]);
            }

            return redirect()->route('products.index')->with('success', 'Product added successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('💥 Store error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Server error: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Server error: ' . $e->getMessage())->withInput();
        }
    }


    // Show the import form
    public function importForm()
    {
        return view('products.import'); // create this Blade file
    }




    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $file = $request->file('file');
        $path = $file->getRealPath();

        if (($handle = fopen($path, 'r')) === false) {
            return back()->with('error', 'Unable to open file.');
        }

        $header = fgetcsv($handle);
        $header = array_map(fn($h) => strtolower(trim($h)), $header);
        $inserted = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            if (!$data || empty($data['name']))
                continue;

            // Sanitize N/A, NULL, empty values
            foreach ($data as $key => $value) {
                if (in_array(strtoupper(trim($value)), ['N/A', 'NULL', ''])) {
                    $data[$key] = null;
                }
            }

            // Create or update product by SKU
            $product = \App\Models\Product::updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'name' => $data['name'],
                    'category' => $data['category'] ?? 'Uncategorized',
                    'description' => $data['description'] ?? null,
                    'total_quantity' => (int) ($data['total_quantity'] ?? 0),
                    'commission' => (float) ($data['commission'] ?? 0),
                    'hsn_code' => $data['hsn_code'] ?? null,
                    'variations' => $this->decodeVariations($data['variations'] ?? null),
                ]
            );

            // ✅ MATERIALS
            if (!empty($data['material details'])) {
                $materials = explode('|', $data['material details']);
                foreach ($materials as $m) {
                    if (preg_match('/^(.*?)\s*\((.*?),\s*(.*?),\s*Used:\s*([\d\.]+)/i', trim($m), $matches)) {
                        [$full, $name, $color, $unit, $qty] = $matches;
                        $qty = (float) $qty;

                        $material = \App\Models\RawMaterial::firstOrCreate([
                            'name' => trim($name),
                            'color' => trim($color),
                            'unit' => trim($unit),
                            'type' => 'Material',
                        ], ['quantity' => $qty]);

                        $product->materials()->syncWithoutDetaching([
                            $material->id => ['quantity_used' => $qty],
                        ]);
                    }
                }
            }

            // ✅ SOLES (Safe stock handling)
            if (!empty($data['sole details'])) {
                $soles = explode('|', $data['sole details']);
                foreach ($soles as $s) {
                    if (preg_match('/^(.*?)\s*\((.*?),\s*Used:\s*([\d\.]+)/i', trim($s), $matches)) {
                        [$full, $name, $color, $usedQty] = $matches;
                        $usedQty = (float) $usedQty;

                        $sole = \App\Models\Sole::firstOrCreate([
                            'name' => trim($name),
                            'color' => trim($color),
                        ]);

                        // 🚫 Skip any stock creation for imported soles
                        $isNewSole = $sole->wasRecentlyCreated || $sole->created_at->gt(now()->subMinutes(2));
                        if ($isNewSole) {
                            \Log::info("🛑 Skipped stock creation for imported sole ID {$sole->id} ({$sole->name})");
                        }

                        // Attach to product regardless (used for linking)
                        $product->soles()->syncWithoutDetaching([
                            $sole->id => ['quantity_used' => $usedQty],
                        ]);
                    }
                }
            }

            // ✅ PROCESSES
            if (!empty($data['process details'])) {
                $processes = explode('|', $data['process details']);
                foreach ($processes as $p) {
                    if (
                        preg_match(
                            '/^(.*?)\s*\(.*?Order:\s*(\d+).*?Qty:\s*(\d+).*?Rate:\s*([\d\.]+)/i',
                            trim($p),
                            $matches
                        )
                    ) {
                        [$full, $name, $order, $qty, $rate] = $matches;
                        $process = \App\Models\Process::firstOrCreate(['name' => trim($name)]);

                        $product->processes()->syncWithoutDetaching([
                            $process->id => [
                                'process_order' => (int) $order,
                                'quantity' => (int) $qty,
                                'labor_rate' => (float) $rate,
                            ],
                        ]);
                    }
                }
            }

            $inserted++;
        }

        fclose($handle);

        return redirect()
            ->route('products.index')
            ->with('success', "✅ $inserted Articles  imported successfully (with processes, materials & soles)!");
    }



    // Decode variations safely
    private function decodeVariations($json)
    {
        if ($this->isJson($json))
            return json_decode($json, true);
        return [];
    }

    // Check valid JSON
    private function isJson($string)
    {
        if (!is_string($string))
            return false;
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }




    // In ProductController.php
// ProductController.php
    public function details($id)
    {
        $product = Product::with([
            'soles',
            'materials',
            'liquidMaterials',
            'processes' // eager load pivot
        ])->find($id);

        if (!$product) {
            \Log::error('Product not found', ['id' => $id]);
            return response()->json(['error' => 'Product not found'], 404);
        }

        // --------------------------
        // Variations
        // --------------------------
        $variations = $product->variations_array ?? [];
        $formattedVariations = array_map(function ($variation) {
            $sizes = is_array($variation['sizes'] ?? null) ? $variation['sizes'] : [];
            $images = isset($variation['main_image'])
                ? [$variation['main_image']]
                : ($variation['images'] ?? []);

            return [
                'color' => $variation['color'] ?? '',
                'sole_color' => $variation['sole_color'] ?? '',
                'sizes' => array_merge(array_fill_keys(range(35, 44), 0), $sizes),
                'images' => $images,
            ];
        }, $variations);

        // --------------------------
        // Representative Image
        // --------------------------
        $representativeImage = $product->image
            ? asset('storage/' . $product->image)
            : (isset($formattedVariations[0]['images'][0])
                ? asset('storage/' . $formattedVariations[0]['images'][0])
                : 'https://via.placeholder.com/150?text=No+Image');

        \Log::info('Product image data', [
            'product_id' => $product->id,
            'product_image' => $product->image,
            'representative_image' => $representativeImage,
            'variation_images' => $formattedVariations[0]['images'] ?? [],
        ]);

        // --------------------------
        // Materials
        // --------------------------
        $materials = $product->materials->map(function ($m) {
            $stock = Stock::where('item_id', $m->id)->where('type', 'material')->first();

            return [
                'id' => $m->id,
                'name' => $m->name,
                'color' => $m->color ?? 'N/A',
                'unit' => $m->unit ?? 'N/A',
                'qty_available' => $stock?->qty_available ?? ($m->quantity ?? 0),
                'quantity_used' => $m->pivot->quantity_used ?? 0,
            ];
        })->toArray();

        // --------------------------
        // Liquid Materials
        // --------------------------
        $liquidMaterials = $product->liquidMaterials->map(function ($l) {
            $stock = Stock::where('item_id', $l->id)->where('type', 'liquid')->first();

            return [
                'id' => $l->id,
                'name' => $l->name ?? 'N/A',
                'unit' => $l->unit ?? 'N/A',
                'qty_available' => $stock?->qty_available ?? ($l->quantity ?? 0),
                'quantity_used' => $l->pivot->quantity_used ?? 0,
            ];
        })->toArray();

        // --------------------------
        // Soles
        // --------------------------
        $soles = $product->soles->map(function ($s) {
            $stocks = Stock::where('item_id', $s->id)->where('type', 'sole')->get();

            $sizesQty = is_string($s->sizes_qty)
                ? json_decode($s->sizes_qty, true) ?? []
                : ($s->sizes_qty ?? []);

            $totalQty = $stocks->sum('qty_available') ?: array_sum($sizesQty);

            return [
                'id' => $s->id,
                'name' => $s->name,
                'color' => $s->color ?? 'N/A',
                'sub_type' => $s->sole_type ?? 'N/A',
                'price' => $s->selling_price ?? 0,
                'qty_available' => $totalQty,
                'quantity_used' => $s->pivot->quantity_used ?? 0,
                'sizes_qty' => $sizesQty,
            ];
        })->toArray();

        // --------------------------
        // Processes (DYNAMIC)
        // --------------------------
        $processes = $product->processes
            ->filter(fn($p) => $p->pivot != null) // only linked processes
            ->unique('id')
            ->sortBy('pivot.process_order')      // optional: order by pivot
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'stage' => $p->stage ?? 'Pending',
                    'status' => $p->status ?? 'Pending',
                    'assigned_quantity' => (int) ($p->pivot->assigned_quantity ?? 0),
                    'completed_quantity' => (int) ($p->pivot->completed_quantity ?? 0),
                    'labor_rate' => (float) ($p->pivot->labor_rate ?? 0),
                    'process_order' => (int) ($p->pivot->process_order ?? 0),
                ];
            })
            ->values() // reset array keys
            ->toArray();

        // --------------------------
        // Response
        // --------------------------
        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku ?? 'N/A',
                'description' => $product->description ?? '',
                'image' => $representativeImage,
            ],
            'variations' => $formattedVariations,
            'materials' => $materials,
            'liquid_materials' => $liquidMaterials,
            'soles' => $soles,
            'processes' => $processes,
        ]);
    }




    // ProductController.php
    public function show(Product $product)
    {
        $variations = is_string($product->variations) ? json_decode($product->variations, true) : ($product->variations ?? []);
        $soles = is_string($product->soles) ? json_decode($product->soles, true) : ($product->soles ?? []);

        $totalMaterialCost = $product->materials->sum(fn($m) => (float) ($m->unit ?? 0) * (float) ($m->price ?? 0));
        $totalLiquidCost = $product->liquidMaterials->sum(fn($l) => (float) ($l->unit ?? 0) * (float) ($l->price ?? 0));

        $soleCost = collect($soles)->sum(fn($s) => (float) ($s['price'] ?? 0) * (int) ($s['quantity'] ?? 1));

        $laborRates = ["Upper Part" => 20, "Lower Part" => 15, "Finished Part" => 10];
        $totalQuantity = collect($variations)->sum(fn($v) => (int) ($v['quantity'] ?? 0));
        $laborCost = $product->processes->sum(fn($process) => ($laborRates[$process->name] ?? 0) * $totalQuantity);

        $totalProductionCost = $totalMaterialCost + $totalLiquidCost + $soleCost + $laborCost;
        $totalSellingPrice = collect($variations)->sum(fn($v) => (float) ($v['price'] ?? 0) * (int) ($v['quantity'] ?? 0));
        $profit = $totalSellingPrice - $totalProductionCost;

        return view('products.show', compact(
            'product',
            'variations',
            'soles',
            'totalMaterialCost',
            'totalLiquidCost',
            'soleCost',
            'laborCost',
            'totalProductionCost',
            'totalSellingPrice',
            'profit'
        ));
    }





    public function edit(Product $product)
    {
        // === 1. GLOBAL LISTS – SAME AS CREATE() ===
        $articleNames = Product::distinct()->pluck('name');
        $articleNos = Product::distinct()->pluck('sku');
        $articleTypes = Product::distinct()->pluck('category');

        $soleNamesWithDetails = Sole::select(
            'id',
            'name',
            'color',
            'sole_type as subtype',
            'price',      // ✅ correctly named column for Blade template
            'quantity',
            'sizes_qty'
        )->get();

        $materialNamesWithDetails = RawMaterial::where('type', 'Material')
            ->select('name', 'color', 'unit', 'quantity', 'per_unit_length as qty_per_unit', 'price')
            ->get();

        $liquidNamesWithDetails = LiquidMaterial::select(
            'name',
            'unit',
            'quantity',
            'per_unit_volume as qty_per_unit',
            'price'
        )->get();

        $defaultProcesses = ['Upper Part', 'Lower Part', 'Finished Part'];

        // === 2. PRODUCT-SPECIFIC DATA ===
        $variations = is_string($product->variations)
            ? json_decode($product->variations, true)
            : $product->variations ?? [];

        $soles = $product->soles()->get()->map(function ($sole) use ($product) {
            return [
                'id' => $sole->id,
                'name' => $sole->name,
                'color' => $sole->color,
                'sub_type' => $sole->sole_type,
                'price' => $sole->price,
                'quantity_used' => $sole->pivot->quantity_used ?? 0,
            ];
        })->toArray();

        $materials = $product->materials()->get()->map(function ($material) {
            return [
                'id' => $material->id,
                'name' => $material->name,
                'color' => $material->color,
                'unit' => $material->unit,
                'quantity_used' => $material->pivot->quantity_used ?? 0,
            ];
        })->toArray();

        $liquidMaterials = $product->liquidMaterials()->get()->map(function ($liquid) {
            return [
                'id' => $liquid->id,
                'name' => $liquid->name,
                'unit' => $liquid->unit,
                'quantity_used' => $liquid->pivot->quantity_used ?? 0,
            ];
        })->toArray();

        $product->load('processes');

        // === 3. RETURN VIEW WITH ALL DATA ===
        return view('products.edit', compact(
            'product',
            'variations',
            'soles',
            'materials',
            'liquidMaterials',

            // ← THESE ARE THE MISSING ONES
            'articleNames',
            'articleNos',
            'articleTypes',
            'soleNamesWithDetails',
            'materialNamesWithDetails',
            'liquidNamesWithDetails',
            'defaultProcesses'
        ));
    }
    public function update(Request $request, Product $product)
    {
        \Log::info('Update method called', ['product_id' => $product->id, 'request_data' => $request->all()]);

        /* -----------------------------------------------------------------
         * 1. VALIDATION – same rules as store()
         * ----------------------------------------------------------------- */
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
                'category' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'color' => 'required|array|min:1',
                'color.*' => 'required|string|max:50',
                'size' => 'required|array|min:1',
                'size.*' => 'required|array|min:1',
                'size.*.*' => 'required|string|max:50',
                'quantity' => 'nullable|array',
                'quantity.*' => 'nullable|integer|min:0',
                'images.*.*' => 'sometimes|image|max:10240',
                'commission' => 'nullable|numeric|min:0|max:100',

                // Processes
                'process_flow' => 'nullable|array',
                'process_flow.*' => 'nullable|string|max:255',
                'process_qty' => 'nullable|array',
                'process_qty.*' => 'nullable|integer|min:0',
                'labor_rate' => 'nullable|array',
                'labor_rate.*' => 'nullable|numeric|min:0',
                'process_order' => 'nullable|array',
                'process_order.*' => 'nullable|integer|min:1',

                // Materials
                'material_name.*' => 'nullable|string|max:255',
                'material_color.*' => 'nullable|string|max:255',
                'material_unit.*' => 'nullable|in:kg,g,metre,piece',
                'material_quantity.*' => 'nullable|numeric|min:0',
                'material_per_unit_length.*' => 'nullable|required_if:material_unit.*,piece|numeric|min:0.01',
                'material_price.*' => 'nullable|numeric|min:0',

                // Soles
                'sole_id.*' => 'nullable|integer|exists:soles,id',
                'sole_name_or_article_no.*' => 'nullable|string|max:255',
                'sole_color.*' => 'nullable|string|max:255',
                'sole_price.*' => 'nullable|numeric|min:0',
                'sole_sub_type.*' => 'nullable|string|max:255',
                'sole_quantity.*' => 'nullable|numeric|min:0',
                'sizes_qty' => 'nullable|array',
            ]);
            \Log::info('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Log::error('Validation failed', ['errors' => $ve->errors()]);
            return back()->withErrors($ve->errors())->withInput();
        }

        DB::beginTransaction();

        try {
            /* -----------------------------------------------------------------
             * 2. VARIATIONS (keep existing images, delete requested, add new)
             * ----------------------------------------------------------------- */
            $variationsData = [];
            $totalQuantity = 0;

            foreach ($validated['color'] as $i => $color) {
                // Existing images for this variation (from DB)
                $oldVariation = $product->variations[$i] ?? [];
                $imagePaths = $oldVariation['images'] ?? [];

                // 1. Delete requested images
                $toDelete = $request->input("delete_images.$i", []);
                foreach ($toDelete as $delIdx) {
                    $path = $imagePaths[$delIdx] ?? null;
                    if ($path && file_exists(public_path("storage/$path"))) {
                        unlink(public_path("storage/$path"));
                    }
                    unset($imagePaths[$delIdx]);
                }
                $imagePaths = array_values($imagePaths);   // re-index

                // 2. Add new uploaded images
                if ($request->hasFile("images.$i")) {
                    foreach ($request->file("images.$i") as $file) {
                        if ($file->isValid()) {
                            $filename = time() . '_' . $file->getClientOriginalName();
                            $dest = public_path('storage/products/variations');
                            if (!file_exists($dest))
                                mkdir($dest, 0777, true);
                            $file->move($dest, $filename);
                            $imagePaths[] = 'products/variations/' . $filename;
                        }
                    }
                }

                $qty = $validated['quantity'][$i] ?? 0;
                $totalQuantity += $qty;

                $variationsData[] = [
                    'color' => $color,
                    'sizes' => $validated['size'][$i] ?? [],
                    'quantity' => $qty,
                    'hsn_code' => '6402',
                    'images' => $imagePaths,
                ];
            }

            /* -----------------------------------------------------------------
             * 3. UPDATE PRODUCT RECORD
             * ----------------------------------------------------------------- */
            $product->update([
                'name' => $validated['name'],
                'sku' => $validated['sku'],
                'category' => $validated['category'] ?? 'Uncategorized',
                'description' => $validated['description'] ?? null,
                'variations' => $variationsData,
                'total_quantity' => $totalQuantity,
                'hsn_code' => '6402',
                'commission' => $validated['commission'] ?? 0,
            ]);
            \Log::info('Product record updated', ['product_id' => $product->id]);

            /* -----------------------------------------------------------------
             * 4. PROCESSES – sync exactly like store()
             * ----------------------------------------------------------------- */
            $processPivot = [];
            if (!empty($validated['process_flow'])) {
                foreach ($validated['process_flow'] as $i => $name) {
                    if ($name) {
                        $proc = Process::firstOrCreate(['name' => $name]);
                        $processPivot[$proc->id] = [
                            'labor_rate' => $validated['labor_rate'][$i] ?? 0,
                            'quantity' => $validated['process_qty'][$i] ?? 0,
                            'process_order' => $validated['process_order'][$i] ?? 0,
                        ];
                    }
                }
            }
            $product->processes()->sync($processPivot);
            \Log::info('Processes synced');

            /* -----------------------------------------------------------------
             * 5. RAW MATERIALS – same global-material logic as store()
             * ----------------------------------------------------------------- */
            $materialPivot = [];

            if (!empty($validated['material_name'])) {
                foreach ($validated['material_name'] as $i => $name) {
                    if (!$name || empty($validated['material_quantity'][$i]))
                        continue;

                    $color = $validated['material_color'][$i] ?? '-';
                    $unit = $validated['material_unit'][$i] ?? 'metre';
                    $qty = floatval($validated['material_quantity'][$i]);
                    $perUnit = $unit === 'piece' ? floatval($validated['material_per_unit_length'][$i] ?? 0) : null;
                    $price = floatval($validated['material_price'][$i] ?? 0);

                    $totalMeasurement = $unit === 'piece' ? $qty * $perUnit : $qty;

                    // 1. Find existing GLOBAL material
                    $material = RawMaterial::where([
                        'name' => $name,
                        'color' => $color,
                        'unit' => $unit,
                        'type' => 'Material',
                        'product_id' => null,
                    ])->first();

                    if (!$material) {
                        // 2. Create new global material
                        $material = RawMaterial::create([
                            'name' => $name,
                            'color' => $color,
                            'unit' => $unit,
                            'type' => 'Material',
                            'product_id' => null,
                            'quantity' => $totalMeasurement,
                            'price' => $price,
                            'per_unit_length' => $perUnit,
                        ]);
                    } else {
                        // 3. Update quantity
                        $material->increment('quantity', $totalMeasurement);
                        $material->update([
                            'price' => $price,
                            'per_unit_length' => $perUnit,
                        ]);
                    }

                    // 4. Pivot entry
                    $materialPivot[$material->id] = [
                        'quantity_used' => $qty,
                    ];

                    // 5. Stock handling (same as store)
                    if ($totalMeasurement > 0) {
                        Stock::updateOrCreate(
                            ['item_id' => $material->id, 'type' => 'material', 'size' => null],
                            ['qty_available' => $material->quantity, 'in_transit_qty' => 0]
                        );

                        StockMovement::create([
                            'item_id' => $material->id,
                            'type' => 'material',
                            'change' => $totalMeasurement,
                            'qty_after' => $material->quantity,
                            'description' => "Update for product {$product->id}",
                        ]);
                    }
                }
            }

            // Sync (keeps existing links that are not in the new list)
            $product->materials()->sync($materialPivot);
            \Log::info('Materials synced');

            /* -----------------------------------------------------------------
             * 6. SOLES – same global-sole logic as store()
             * ----------------------------------------------------------------- */
            $solePivot = [];

            $totalSoles = max(
                count($validated['sole_id'] ?? []),
                count($validated['sole_name_or_article_no'] ?? [])
            );

            for ($i = 0; $i < $totalSoles; $i++) {
                $sole = null;

                // 1. Existing sole by ID
                if (!empty($validated['sole_id'][$i])) {
                    $sole = Sole::find($validated['sole_id'][$i]);
                    if ($sole) {
                        $solePivot[$sole->id] = ['quantity_used' => 1];
                    }
                    continue;
                }

                // 2. New sole by name+color
                if (!empty($validated['sole_name_or_article_no'][$i])) {
                    $name = trim($validated['sole_name_or_article_no'][$i]);
                    $color = trim($validated['sole_color'][$i] ?? '');
                    if (!$name || !$color)
                        continue;

                    $sub_type = $validated['sole_sub_type'][$i] ?? null;
                    $price = $validated['sole_price'][$i] ?? 0;

                    // Size quantities (35-44)
                    $sizes_qty = $validated['sizes_qty'][$i] ?? [];
                    foreach (range(35, 44) as $sz) {
                        $sizes_qty[$sz] = isset($sizes_qty[$sz]) ? floatval($sizes_qty[$sz]) : 0;
                    }
                    $total_qty = array_sum($sizes_qty);

                    // Find existing global sole
                    $sole = Sole::where('name', $name)
                        ->where('color', $color)
                        ->first();

                    if (!$sole) {
                        // Create global sole
                        $sole = Sole::create([
                            'product_id' => null,
                            'name' => $name,
                            'color' => $color,
                            'sole_type' => $sub_type,
                            'unit_price' => $price,
                            'quantity' => $total_qty,
                            'sizes_qty' => json_encode($sizes_qty),
                        ]);

                        // Stock per size
                        foreach ($sizes_qty as $sz => $qty) {
                            if ($qty > 0) {
                                $stock = Stock::create([
                                    'item_id' => $sole->id,
                                    'type' => 'sole',
                                    'size' => $sz,
                                    'qty_available' => $qty,
                                ]);
                                StockMovement::create([
                                    'item_id' => $sole->id,
                                    'type' => 'sole',
                                    'size' => $sz,
                                    'change' => $qty,
                                    'qty_after' => $stock->qty_available,
                                    'description' => "Initial stock for sole size {$sz}",
                                ]);
                            }
                        }
                    } else {
                        // Update existing global sole
                        $sole->increment('quantity', $total_qty);
                        $sole->update([
                            'sole_type' => $sub_type,
                            'price' => $price,
                            'sizes_qty' => json_encode($sizes_qty),
                        ]);
                    }

                    $solePivot[$sole->id] = ['quantity_used' => 1];
                }
            }

            $product->soles()->sync($solePivot);
            \Log::info('Soles synced');

            /* -----------------------------------------------------------------
             * 7. COMMIT
             * ----------------------------------------------------------------- */
            DB::commit();
            \Log::info('Product updated successfully', ['product_id' => $product->id]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully',
                    'product' => $product,
                ]);
            }

            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Update failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Server error: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Server error: ' . $e->getMessage())->withInput();
        }
    }



    public function syncToOnline(Request $request)
    {
        // 🧱 Step 1: Fetch all unsynced data first
        $unsyncedClients = \App\Models\User::whereIn('category', ['wholesale', 'retail'])
            ->where('is_synced', 0)
            ->get();

        $unsyncedProducts = \App\Models\Product::where('added_by_offline', 1)
            ->where('is_synced', 0)
            ->get();

        $unsyncedQuotations = \App\Models\Quotation::where('is_synced', 0)->get();
        $unsyncedOrders = \App\Models\ProductionOrder::where('is_synced', 0)->get();
        $unsyncedInvoices = \App\Models\Invoice::where('is_synced', 0)->get();

        // 🧩 Step 2: Collect client IDs referenced by unsynced quotations, orders, and invoices
        $referencedClientIds = collect()
            ->merge($unsyncedQuotations->pluck('client_id'))
            ->merge($unsyncedOrders->pluck('client_id'))
            ->merge($unsyncedInvoices->pluck('client_id'))
            ->filter()
            ->unique();

        // 🧩 Step 3: Fetch all unsynced + referenced clients
        $unsyncedClients = \App\Models\User::whereIn('category', ['wholesale', 'retail'])
            ->where(function ($q) use ($referencedClientIds) {
                $q->where('is_synced', 0)
                    ->orWhereIn('id', $referencedClientIds);
            })
            ->get();

        \Log::info('👥 [CLIENTS PAYLOAD PREPARED]', [
            'count' => $unsyncedClients->count(),
            'ids' => $unsyncedClients->pluck('id')->toArray(),
        ]);

        \Log::info('🧾 [DEBUG: Offline Unsynced Invoices]', [
            'count' => $unsyncedInvoices->count(),
            'ids' => $unsyncedInvoices->pluck('id')->toArray(),
            'statuses' => $unsyncedInvoices->pluck('status', 'id')->toArray(),
        ]);

        // ✅ Step 4: If nothing to sync
        if (
            $unsyncedClients->isEmpty() &&
            $unsyncedProducts->isEmpty() &&
            $unsyncedQuotations->isEmpty() &&
            $unsyncedOrders->isEmpty() &&
            $unsyncedInvoices->isEmpty()
        ) {
            return redirect()->back()->with('success', '✅ No new Parties, Articles, Quotations, Orders, or Invoices to sync.');
        }

        try {
            /* ==========================================================
             * 👥 CLIENTS PAYLOAD
             * ========================================================== */
            $clientsPayload = $unsyncedClients->map(function ($client) {
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'phone' => $client->phone,
                    'address' => $client->address,
                    'business_name' => $client->business_name,
                    'gst_no' => $client->gst_no,
                    'category' => $client->category,
                    'state' => $client->state,
                    'city' => $client->city,
                    'pincode' => $client->pincode,
                    'status' => $client->status,
                    'created_at' => $client->created_at,
                    'updated_at' => $client->updated_at,
                ];
            })->toArray();

            /* ==========================================================
             * 🧱 PRODUCTS PAYLOAD (with full image paths)
             * ========================================================== */
            $productsPayload = $unsyncedProducts->map(function ($product) {

                // Decode variations safely
                $variations = is_array($product->variations)
                    ? $product->variations
                    : (json_decode($product->variations, true) ?? []);

                // 🔥 Append full image paths for each variation
                $variations = collect($variations)->map(function ($v) {

                    $images = $v['images'] ?? [];

                    // Add new full-path array so online ERP can copy files
                    $v['image_paths'] = collect($images)->map(function ($img) {
                        return storage_path('app/public/' . $img);
                    })->toArray();

                    return $v;
                })->toArray();

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => (float) $product->price,
                    'unit_price' => (float) $product->unit_price,
                    'category' => $product->category ?? 'Uncategorized',

                    // send updated variations
                    'variations' => $variations,

                    'hsn_code' => $product->hsn_code ?? null,
                    'added_by_offline' => 1,
                    'total_quantity' => (int) $product->total_quantity,
                    'description' => $product->description ?? '',
                ];
            })->toArray();


            /* ==========================================================
             * 📑 QUOTATIONS PAYLOAD
             * ========================================================== */
            $quotationsPayload = $unsyncedQuotations->map(function ($q) {
                return [
                    'id' => $q->id,
                    'client_id' => $q->client_id,
                    'warehouse_id' => $q->warehouse_id,
                    'salesperson_id' => $q->salesperson_id,
                    'status' => $q->status,
                    'subtotal' => $q->subtotal,
                    'tax' => $q->tax,
                    'grand_total' => $q->grand_total,
                    'created_at' => $q->created_at,
                    'updated_at' => $q->updated_at,
                ];
            })->toArray();

            /* ==========================================================
             * 🧾 QUOTATION PRODUCTS PAYLOAD (with SKU)
             * ========================================================== */
            $quotationProductsPayload = \DB::table('product_quotation')
                ->whereIn('quotation_id', $unsyncedQuotations->pluck('id'))
                ->join('products', 'product_quotation.product_id', '=', 'products.id')
                ->select(
                    'product_quotation.quotation_id',
                    'product_quotation.product_id as offline_product_id',
                    'products.sku',
                    'product_quotation.quantity',
                    'product_quotation.unit_price',
                    'product_quotation.variations'
                )
                ->get()
                ->map(function ($qp) {
                    return [
                        'quotation_id' => $qp->quotation_id,
                        'offline_product_id' => (int) $qp->offline_product_id,
                        'sku' => $qp->sku,
                        'quantity' => (int) $qp->quantity,
                        'unit_price' => (float) $qp->unit_price,
                        'variations' => is_string($qp->variations)
                            ? $qp->variations
                            : json_encode($qp->variations ?? []),
                    ];
                })
                ->toArray();

            /* ==========================================================
             * 🏭 PRODUCTION ORDERS PAYLOAD
             * ========================================================== */
            $ordersPayload = $unsyncedOrders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'stage' => (int) $order->stage,
                    'quotation_id' => $order->quotation_id,
                    'client_order_id' => $order->client_order_id,
                    'status' => $order->status,
                    'due_date' => $order->due_date,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                    'added_by_offline' => 1,
                ];
            })->toArray();

            /* ==========================================================
             * 🧾 INVOICES PAYLOAD (OFFLINE → ONLINE)
             * ========================================================== */
            $invoicesPayload = $unsyncedInvoices->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'po_no' => $invoice->po_no,
                    'type' => $invoice->type,
                    'quotation_id' => $invoice->quotation_id,
                    'order_id' => $invoice->order_id,
                    'client_id' => $invoice->client_id,
                    'amount' => (float) $invoice->amount,
                    'amount_paid' => (float) $invoice->amount_paid,
                    'items' => $invoice->items,
                    'payment_type' => $invoice->payment_type,
                    'due_date' => $invoice->due_date,
                    'status' => $invoice->status,
                    'created_at' => $invoice->created_at,
                    'updated_at' => $invoice->updated_at,
                ];
            })->toArray();

            /* ==========================================================
             * 🌐 COMBINE PAYLOAD
             * ========================================================== */
            $payload = [
                'clients' => $clientsPayload,
                'products' => $productsPayload,
                'quotations' => $quotationsPayload,
                'quotation_products' => $quotationProductsPayload,
                'production_orders' => $ordersPayload,
                'invoices' => $invoicesPayload,
            ];

            \Log::info('🟡 Sending sync request', [
                'clients_count' => count($clientsPayload),
                'products_count' => count($productsPayload),
                'quotations_count' => count($quotationsPayload),
                'quotation_products_count' => count($quotationProductsPayload),
                'orders_count' => count($ordersPayload),
                'invoices_count' => count($invoicesPayload),
            ]);

            /* ==========================================================
             * 🌐 SEND TO ONLINE ERP
             * ========================================================== */
            $response = \Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SYNC_API_TOKEN'),
                'Content-Type' => 'application/json',
            ])->post(env('SYNC_API_URL'), $payload);

            \Log::info('🟣 Online API response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            /* ==========================================================
             * ✅ HANDLE RESPONSE
             * ========================================================== */
            if ($response->successful()) {
                \App\Models\User::whereIn('id', $unsyncedClients->pluck('id'))->update(['is_synced' => 1]);
                \App\Models\Product::whereIn('id', $unsyncedProducts->pluck('id'))->update(['is_synced' => 1]);
                \App\Models\Quotation::whereIn('id', $unsyncedQuotations->pluck('id'))->update(['is_synced' => 1]);
                \App\Models\ProductionOrder::whereIn('id', $unsyncedOrders->pluck('id'))->update(['is_synced' => 1]);
                \App\Models\Invoice::whereIn('id', $unsyncedInvoices->pluck('id'))->update(['is_synced' => 1]);

                return redirect()->back()->with(
                    'success',
                    '✅ Parties, Articles, Quotations, Quotation Products, Orders, and Invoices synced successfully!'
                );
            }

            return redirect()->back()->with('error', '❌ Sync failed: ' . $response->body());

        } catch (\Exception $e) {
            \Log::error('🔴 Sync exception', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', '❌ Exception: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Helper to safely prepare variations for API
     */
    private function prepareVariations($variations)
    {
        if (is_array($variations)) {
            return $variations;
        }

        if (is_string($variations)) {
            $decoded = json_decode($variations, true);
            return $decoded ?? [];
        }

        return [];
    }




    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', __('Product deleted successfully!'));
    }

    public function export()
    {
        $products = \App\Models\Product::with(['materials', 'soles', 'processes'])->get();
        $filename = 'products_export_' . now()->format('Y-m-d_His') . '.csv';
        $handle = fopen($filename, 'w+');

        fputcsv($handle, [
            'Name',
            'SKU',
            'Category',
            'Description',
            'Total Quantity',
            'Variations',
            'Material Details',
            'Sole Details',
            'Process Details',
            'Image URLs',
            'Commission',
            'HSN Code',
            'Created At',
            'Updated At'
        ]);

        foreach ($products as $product) {
            // Variations
            $variations = is_string($product->variations)
                ? json_decode($product->variations, true)
                : ($product->variations ?? []);

            // Collect all images
            $variationImageUrls = collect($variations)
                ->pluck('images')
                ->flatten()
                ->filter()
                ->map(fn($img) => asset('storage/' . ltrim($img, '/')))
                ->values()
                ->toArray();

            $allImageUrls = [];
            if ($product->image)
                $allImageUrls[] = asset('storage/' . $product->image);
            $allImageUrls = array_merge($allImageUrls, $variationImageUrls);

            // Materials
            $materialsList = [];
            foreach ($product->materials as $m) {
                $materialsList[] = sprintf(
                    "%s (%s, %s, Used: %s %s)",
                    $m->name ?? 'N/A',
                    $m->color ?? '-',
                    $m->unit ?? '-',
                    $m->pivot->quantity_used ?? 0,
                    $m->unit ?? ''
                );
            }

            // Soles
            $solesList = [];
            foreach ($product->soles as $s) {
                $sizes = is_string($s->sizes_qty) ? json_decode($s->sizes_qty, true) : ($s->sizes_qty ?? []);
                $sizeDetails = collect($sizes)
                    ->map(fn($qty, $sz) => "Size $sz: $qty")
                    ->implode(', ');

                $solesList[] = sprintf(
                    "%s (%s, Used: %s, %s)",
                    $s->name ?? 'N/A',
                    $s->color ?? '-',
                    $s->pivot->quantity_used ?? 0,
                    $sizeDetails
                );
            }

            // Processes
            $processList = [];
            foreach ($product->processes as $p) {
                $processList[] = sprintf(
                    "%s (Order: %d, Qty: %d, Rate: %.2f)",
                    $p->name ?? 'N/A',
                    $p->pivot->process_order ?? 0,
                    $p->pivot->quantity ?? 0,
                    $p->pivot->labor_rate ?? 0
                );
            }

            // Write row
            fputcsv($handle, [
                $product->name,
                $product->sku,
                $product->category,
                $product->description,
                $product->total_quantity,
                json_encode($variations, JSON_UNESCAPED_SLASHES),
                implode(' | ', $materialsList),
                implode(' | ', $solesList),
                implode(' | ', $processList),
                implode(' | ', $allImageUrls),
                $product->commission,
                $product->hsn_code,
                $product->created_at?->format('d-m-Y H:i'),
                $product->updated_at?->format('d-m-Y H:i'),
            ]);
        }

        fclose($handle);
        return response()->download($filename)->deleteFileAfterSend(true);
    }


}
