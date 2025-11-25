<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchFlowAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_flow_id',
        'process_id',
        'worker_id',  // <- use worker_id
        'assigned_at',
        'status',
    ];

    public $timestamps = false;

    // Relationships
    public function batchFlow()
    {
        return $this->belongsTo(BatchFlow::class, 'batch_flow_id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function employee()  // <-- you can still call it employee for UI clarity
    {
        return $this->belongsTo(Employee::class, 'worker_id');
    }
}
