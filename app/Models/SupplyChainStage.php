<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyChainStage extends Model
{
    protected $table = 'supply_chain_stages';

    protected $fillable = [
        'product_id', 'start_date', 'status', 'due_date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
