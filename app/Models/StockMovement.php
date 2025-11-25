<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements';

    protected $fillable = [
        'batch_id',
        'item_id',
        'type',
        'change',
        'qty_after',
        'size',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($movement) {
            // 🛑 Prevent dummy stock movements from being saved
            if (
                (is_null($movement->change) || floatval($movement->change) == 0) &&
                (is_null($movement->qty_after) || floatval($movement->qty_after) == 0)
            ) {
                \Log::warning("⚠️ Skipped creating empty StockMovement for item_id={$movement->item_id}");
                return false; // cancel save
            }
        });
    }
}
