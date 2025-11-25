<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvanceDeduction extends Model
{
    protected $fillable = [
        'salary_advance_id',
        'employee_id',
        'batch_id',
        'deducted_amount',
    ];

    public function salaryAdvance()
    {
        return $this->belongsTo(SalaryAdvance::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function batch()
    {
        return $this->belongsTo(EmployeeBatch::class, 'batch_id');
    }
}
