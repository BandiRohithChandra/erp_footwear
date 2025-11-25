<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
        'unit_price',
        'discount',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'sale_date',
        'customer_name',
        'customer_email',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}