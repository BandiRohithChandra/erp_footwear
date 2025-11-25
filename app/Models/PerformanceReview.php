<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'review_date',
        'feedback',
        'rating',
        'goals',
        'status',
    ];

    protected $dates = [
        'review_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}