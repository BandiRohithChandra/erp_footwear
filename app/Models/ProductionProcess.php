<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'process_id',
        'product_id',
        'employee_id',
        'assigned_quantity',
        'completed_quantity',
        'labor_rate',
        'name',
        'stage',
        'status',
        'operator',
        'start_date',  // add this
        'end_date',    // add this
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
