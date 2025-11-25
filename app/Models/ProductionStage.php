<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionStage extends Model
{
    protected $fillable = [
        'production_order_id',
        'name',
        'status',
        'employee_id',
        'progress_percent',
        'time_taken',
    ];

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
