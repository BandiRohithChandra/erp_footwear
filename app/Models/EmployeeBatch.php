<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBatch extends Model
{
    protected $table = 'employee_batch';
protected $fillable = [
    'batch_id','employee_id','process_id','quantity','labor_rate','labor_status','start_date','end_date','advance_amount','paid_amount'
];

protected $casts = [
    'start_date' => 'date',
    'end_date'   => 'date',
    'advance_amount' => 'decimal:2',
    'paid_amount'    => 'decimal:2',
];

    // Relations
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

  public function process()
{
    return $this->belongsTo(ProductionProcess::class, 'process_id', 'process_id');
}


    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
