<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiquidMaterial extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'unit',
        'quantity',
        'price',
        'per_unit_volume', // updated from qty_per_unit
    ];

    protected $casts = [
        'price'           => 'decimal:2',
        'per_unit_volume' => 'decimal:2',
        'quantity'        => 'decimal:2',
    ];

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_liquid_material',
            'liquid_material_id',
            'product_id'
        )->withPivot('quantity_used')->withTimestamps();
    }

    protected static function booted()
    {
        static::created(function ($liquid) {
            if ($liquid->product_id) {
                \DB::table('product_liquid_material')->updateOrInsert(
                    [
                        'product_id' => $liquid->product_id,
                        'liquid_material_id' => $liquid->id,
                    ],
                    [
                        'quantity_used' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        });
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'item_id')
                    ->where('type', 'liquid');
    }

    public function restock($addedQty)
    {
        $stock = Stock::firstOrCreate(
            ['item_id' => $this->id, 'type' => 'liquid', 'size' => null],
            ['qty_available' => 0]
        );

        $stock->qty_available += $addedQty;
        $stock->save();

        $this->quantity += $addedQty;
        $this->save();

        return $stock;
    }
}
