<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'latitude',
        'longitude',
        'is_remote',
        'status', // Added status to fillable
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id'); // Updated to reference User model
    }
}