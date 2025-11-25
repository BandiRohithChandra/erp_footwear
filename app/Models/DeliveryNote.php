<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    protected $fillable = [
    'delivery_note_no',
    'batch_id',
    'client_id',
    'assigned_qty',
    'delivery_date',
    'items',
];


    protected $casts = [
        'items' => 'array',
        'delivery_date' => 'date',
        'variations' => 'array',
    ];


   public function client()
{
    return $this->belongsTo(\App\Models\User::class, 'client_id');
}





    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
