<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductQuotation extends Pivot
{
    protected $table = 'product_quotation';

    protected $fillable = ['quotation_id', 'product_id', 'quantity', 'unit_price', 'variations'];

    protected $casts = [
        'variations' => 'array', // Laravel will automatically cast JSON to array
    ];
}
