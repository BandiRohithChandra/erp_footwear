<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
    'name',
    'sku',
    'category',
    'description',
    'variations',
    'price',
    'unit_price',
    'commission',
    'tax_rate',
    'tax_amount',
    'total_quantity',
    'total_amount',
    'production_cost',
    'profit',
    'low_stock_threshold',
    'hsn_code', // add this
];


    protected $casts = [
        'variations' => 'array',
        'price' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'commission' => 'decimal:2',
        'production_cost' => 'decimal:2',
        'profit' => 'decimal:2',
        'total_quantity' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'low_stock_threshold' => 'integer',
    ];

    protected $attributes = [
        'low_stock_threshold' => 10, // Default threshold for low stock
    ];

    // -------------------------
    // RELATIONSHIPS
    // -------------------------

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

public function processes()
{
    return $this->belongsToMany(Process::class, 'product_processes') // ✅ plural
                ->withPivot('labor_rate')
                ->withTimestamps();
}



protected static function booted()
{
    // ✅ 1. Mark products created offline as unsynced
    static::creating(function ($product) {
        $product->added_by_offline = 1;
        $product->is_synced = 0;
    });

    // ✅ 2. When product deleted offline → delete online via API
    static::deleted(function ($product) {
        try {
            \Log::info('🗑️ Attempting to sync deletion to online ERP', [
                'product_id' => $product->id,
                'sku' => $product->sku,
                'name' => $product->name,
            ]);

            $response = \Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SYNC_API_TOKEN'),
                'Content-Type'  => 'application/json',
            ])->delete(env('SYNC_API_URL') . '/' . $product->id, [
                'sku' => $product->sku, // ✅ send SKU to match online
            ]);

            if ($response->successful()) {
                \Log::info('✅ Product deletion synced successfully', [
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'response' => $response->json(),
                ]);
            } else {
                \Log::warning('⚠️ Product deletion failed', [
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('❌ Product deletion sync error', [
                'product_id' => $product->id,
                'sku' => $product->sku,
                'error' => $e->getMessage(),
            ]);
        }
    });
}




public function materials()
{
    return $this->belongsToMany(RawMaterial::class, 'article_raw_material', 'article_id', 'raw_material_id')
                ->withPivot('quantity_used')
                ->withTimestamps()
                ->where('raw_materials.type', 'Material');
}


    public function liquidMaterials()
    {
        return $this->belongsToMany(LiquidMaterial::class, 'product_liquid_material', 'product_id', 'liquid_material_id')
                    ->withPivot('quantity_used')
                    ->withTimestamps();
    }

    public function soles()
    {
        return $this->belongsToMany(Sole::class, 'product_sole', 'product_id', 'sole_id')
                    ->withPivot('quantity_used')
                    ->withTimestamps();
    }

    // -------------------------
    // HELPERS / ACCESSORS
    // -------------------------

    public function getVariationsArrayAttribute()
    {
        $variations = is_array($this->variations) ? $this->variations : json_decode($this->variations, true) ?? [];
        foreach ($variations as &$v) {
            if (!array_key_exists('hsn_code', $v)) {
                $v['hsn_code'] = null;
            }
        }
        return $variations;
    }

    public function getSolesDataAttribute()
    {
        return $this->soles->map(function ($sole) {
            return [
                'id' => $sole->id,
                'name' => $sole->name,
                'color' => $sole->color,
                'sub_type' => $sole->sole_type,
                'quantity' => floatval($sole->quantity),
                'unit_price' => floatval($sole->unit_price),
                'selling_price' => floatval($sole->selling_price),
                'sizes_qty' => is_array($sole->sizes_qty) ? $sole->sizes_qty : json_decode($sole->sizes_qty, true) ?? [],
                'quantity_used' => floatval($sole->pivot->quantity_used ?? 1),
            ];
        })->toArray();
    }

    public function getLiquidMaterialsArrayAttribute()
    {
        return $this->liquidMaterials->map(function ($liquid) {
            $unit = $liquid->unit;
            $quantity_used = floatval($liquid->pivot->quantity_used ?? 0);
            $total_measurement = $unit === 'piece' ? $quantity_used * floatval($liquid->per_unit_volume ?? 1) : $quantity_used;

            return [
                'id' => $liquid->id,
                'name' => $liquid->name,
                'unit' => $unit,
                'price' => floatval($liquid->price),
                'per_unit_volume' => floatval($liquid->per_unit_volume) ?? null,
                'quantity_used' => $quantity_used,
                'total_measurement' => $total_measurement,
            ];
        })->toArray();
    }

    public function getMaterialsArrayAttribute()
    {
        return $this->materials->map(function ($material) {
            $unit = $material->unit;
            $quantity_used = floatval($material->pivot->quantity_used ?? 0);
            $total_measurement = $unit === 'piece' ? $quantity_used * floatval($material->per_unit_length ?? 1) : $quantity_used;

            return [
                'id' => $material->id,
                'name' => $material->name,
                'unit' => $unit,
                'price' => floatval($material->price),
                'per_unit_length' => floatval($material->per_unit_length) ?? null,
                'quantity_used' => $quantity_used,
                'total_measurement' => $total_measurement,
            ];
        })->toArray();
    }

    public function getTotalQuantityAttribute()
    {
        $warehouseQty = $this->warehouses->sum('pivot.quantity');
        return $warehouseQty > 0 ? floatval($warehouseQty) : floatval($this->attributes['total_quantity'] ?? 0);
    }

    public function isLowStock()
    {
        return $this->total_quantity <= $this->low_stock_threshold;
    }

    // Calculate required pieces for stock deduction
    public function calculateRequiredQuantity($item, $type, $totalShoes)
{
    $quantityUsedPerShoe = floatval($item->pivot->quantity_used ?? 0); // per shoe
    if ($quantityUsedPerShoe <= 0) return 0;

    if ($item->unit === 'piece') {
        $perUnitMeasurement = $type === 'material' ? floatval($item->per_unit_length ?? 1) : floatval($item->per_unit_volume ?? 1);
        $totalMeasurement = $totalShoes * $quantityUsedPerShoe; // e.g., meters or liters
        return ceil($totalMeasurement / $perUnitMeasurement); // number of pieces/cans
    }

    // For liquids stored in direct quantity (like liters)
    return $totalShoes * $quantityUsedPerShoe;
}

}
