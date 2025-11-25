<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'manager_id',
        'amount',
        'description',
        'status',
        'expense_date',
        'attachment_path',
    ];

    protected $dates = [
        'expense_date',
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