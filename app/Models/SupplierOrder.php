<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'po_number',
        'items',
        'total_amount',
        'paid_amount',
        'payment_status',
        'status',
        'order_date',
        'expected_delivery'
    ];

    protected $casts = [
        'items' => 'array',
        'order_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function returns()
{
    return $this->hasMany(SupplierReturn::class, 'order_id');
}



public function updateReceiveStatus()
{
    $orderedItems = collect($this->items);
    $stockArrivals = \App\Models\StockArrival::where('order_id', $this->id)
                        ->where('type', 'sole')
                        ->get();

    $totalOrdered = 0;
    $totalReceived = 0;

    foreach ($orderedItems as $item) {
        if ($item['type'] === 'sole') {
            foreach ($item['sizes_qty'] as $size => $orderedQty) {
                $totalOrdered += $orderedQty;

                $receivedQty = $stockArrivals
                    ->where('item_id', $item['id'])
                    ->where('size', $size)
                    ->sum(function ($row) {
                        // If quantity is negative (because return logic), treat as 0
                        return max(0, $row->original_quantity - $row->quantity);
                    });

                $totalReceived += $receivedQty;
            }
        }
    }

    if ($totalReceived == 0) {
        $this->status = 'pending';
    }
    elseif ($totalReceived < $totalOrdered) {
        $this->status = 'partially_received';
    }
    else {
        $this->status = 'received';   // or completed
    }

    $this->save();
}


}
