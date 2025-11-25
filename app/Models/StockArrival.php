<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockArrival extends Model
{
    protected $fillable = [
        'item_id',
        'type',
        'color',
        'size',
        'quantity',
        'status',
        'reason',
        'supplier_id', // updated from 'party'
        'article_no',
        'received_at',
    ];

    /**
     * Relationship to Sole
     * 
     * 
     * 
     */

    protected $casts = [
        'received_at' => 'datetime', // ensures Laravel converts it to Carbon
    ];

    public function sole()
    {
        return $this->belongsTo(Sole::class, 'item_id');
    }

    /**
     * Relationship to Supplier (User)
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

}
