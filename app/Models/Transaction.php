<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Transaction extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'description',
        'type',
        'category',
        'amount',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'transaction_date',
        'region',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function calculateTax()
    {
        $region = config('taxes.regions.' . $this->region, config('taxes.regions.' . config('taxes.default_region')));
        $taxRate = $region['tax_rate'];
        $this->tax_amount = $this->amount * $taxRate;
        $this->total_amount = $this->amount + $this->tax_amount;
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['description', 'type', 'category', 'amount', 'tax_rate', 'tax_amount', 'total_amount', 'transaction_date', 'region', 'status', 'approved_by', 'approved_at'])
            ->logOnlyDirty()
            ->useLogName('transaction');
    }
}