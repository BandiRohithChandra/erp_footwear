<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stock;

class Sole extends Model
{
    use HasFactory;

    protected $table = 'soles'; // ensure correct table

    protected $fillable = [
        'product_id',
        'name',
        'color',
        'sole_type',
        'quantity',
        'qty_per_unit',
        'sizes_qty',
        'price',          // updated column
    ];

    protected $casts = [
        'sizes_qty'    => 'array',   // automatically cast JSON to array
        'price'        => 'decimal:2',
        'qty_per_unit' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Booted method to auto-sync stocks
     */


protected static function booted()
{
    static::saving(function ($sole) {
        if ($sole->price <= 0) {
            $original = $sole->getOriginal('price');
            if ($original > 0) {
                $sole->price = $original; // revert to old valid price
            }
        }
    });
}



    /**
     * Sync stocks table with current sizes_qty
     */
    // public function syncStocks()
    // {
    //     $sizes = $this->sizes_qty;

    //     if (!is_array($sizes) || empty($sizes)) {
    //         return;
    //     }

    //     // Delete old stock rows
    //     Stock::where('item_id', $this->id)
    //          ->where('type', 'sole')
    //          ->delete();

    //     // Insert new stock rows for all sizes (even 0)
    //     foreach ($sizes as $size => $qty) {
    //         Stock::create([
    //             'item_id'       => $this->id,
    //             'type'          => 'sole',
    //             'size'          => (int) $size,
    //             'qty_available' => (int) $qty,
    //         ]);
    //     }
    // }
}
