<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierReturn extends Model
{
    protected $table = 'supplier_returns';

    // Fillable fields
    protected $fillable = [
        'supplier_id',
        'order_id',
        'items',
        'remarks',
        'status',
    ];

    // Cast items JSON to array
    protected $casts = [
        'items' => 'array',
    ];

    // Relationship: Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship: Original Purchase Order
    public function order()
    {
        return $this->belongsTo(SupplierOrder::class, 'order_id');
    }
}
