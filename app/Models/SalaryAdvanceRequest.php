<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryAdvanceRequest extends Model
{
    use HasFactory;

    protected $table = 'salary_advance_requests';

    protected $fillable = [
        'employee_id',
        'manager_id', // Added
        'amount',
        'reason',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}