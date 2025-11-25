<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'amount', 'payment_date', 'description',
        'region', 'tax_rate', 'tax_amount', 'total_amount',
        'status', 'manager_id', 'finance_approver_id', 'disbursed_at'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'disbursed_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function financeApprover()
    {
        return $this->belongsTo(User::class, 'finance_approver_id');
    }

    public function calculateTax()
    {
        $region = config('taxes.regions.' . $this->region, config('taxes.regions.' . config('taxes.default_region')));
        $taxRate = $region['tax_rate'];
        $this->tax_rate = $taxRate;
        $this->tax_amount = $this->amount * $taxRate;
        $this->total_amount = $this->amount + $this->tax_amount;
    }
}