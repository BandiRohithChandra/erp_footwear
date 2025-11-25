<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarningLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'issuer_id', // New field for the user who issued the letter
        'reason',
        'description', // New field for additional details
        'issue_date', // New field for the date the letter was issued
        'status', // New field for the status of the warning letter
        'signed_letter_path', // New field for the path to the signed letter document
    ];

    protected $dates = [
        'issue_date', // Ensure date_issued is treated as a date
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issuer_id');
    }
}