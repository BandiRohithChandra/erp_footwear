<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'manager_id',
        'leave_type',
        'start_date',
        'end_date',
        'reason',
        'status',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1; // Including both start and end dates
    }
}