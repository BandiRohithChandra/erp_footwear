<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'type',          
        'size',          
        'qty_available',
        'in_transit_qty' // must match DB column
    ];

    protected $casts = [
        'qty_available' => 'decimal:2',
        'in_transit_qty' => 'decimal:2', // must match DB column
    ];

    public function addInTransit($qty)
    {
        $this->in_transit_qty += $qty; // use correct property
        $this->saveQuietly();
    }

    public function receiveStock($qty)
    {
        $this->qty_available += $qty;
        $this->in_transit_qty -= $qty;
        if ($this->in_transit_qty < 0) $this->in_transit_qty = 0;
        $this->saveQuietly();
    }
}
