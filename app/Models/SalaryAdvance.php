<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryAdvance extends Model
{
    use HasFactory;

   protected $fillable = ['employee_id', 'amount', 'used_amount', 'date', 'status'];
protected $casts = [
    'date' => 'date',
    'amount' => 'decimal:2',
    'used_amount' => 'decimal:2',
];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    
}
