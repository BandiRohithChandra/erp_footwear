<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchFlow extends Model
{
    protected $fillable = [
        'batch_id',
        'quotation_id',
        'status',
        'quantity',
        'priority',
        'start_date',
        'end_date',
        'created_by',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }


    public function assignments()
{
    return $this->hasMany(BatchFlowAssignment::class);
}

    
}
