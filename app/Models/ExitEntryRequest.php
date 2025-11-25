<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitEntryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'manager_id', // Added
        'exit_date',
        're_entry_date',
        'reason',
        'status',
    ];

    protected $dates = [
        'exit_date',
        're_entry_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Employee::class, 'id', 'id', 'employee_id', 'user_id');
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}