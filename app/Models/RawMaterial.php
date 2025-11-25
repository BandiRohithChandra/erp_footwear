<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $table = 'raw_materials';

    protected $fillable = [
        'product_id',
        'name',
        'color',
        'unit',
        'quantity',
        'price',
        'type',
        'per_unit_length', // updated from qty_per_unit
    ];

    protected $casts = [
        'price'           => 'decimal:2',
        'per_unit_length' => 'decimal:2',
        'quantity'        => 'decimal:2',
    ];

    // Many-to-Many with Product
    public function products()
    {
        return $this->belongsToMany(Product::class, 'article_raw_material')
                    ->withPivot('quantity_used')
                    ->withTimestamps();
    }

    // Stock relationship
    public function stock()
    {
        return $this->hasOne(Stock::class, 'item_id', 'id')
                    ->where('type', strtolower($this->type));
    }

    // Restock method
    public function restock($addedQty)
    {
        $stock = Stock::firstOrCreate(
            ['item_id' => $this->id, 'type' => strtolower($this->type), 'size' => null],
            ['qty_available' => 0]
        );

        $stock->qty_available += $addedQty;
        $stock->save();

        $this->quantity += $addedQty;
        $this->save();

        return $stock;
    }
}
