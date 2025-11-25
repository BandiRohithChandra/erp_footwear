<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerPayroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'batch_id',
        'process_id',
        'amount',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'payment_date',
        'status',
        'manager_id',
        'finance_approver_id',
        'disbursed_at'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'disbursed_at' => 'datetime',
    ];

    // Relationships
    public function worker() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // WorkerPayroll.php

public function employee()
{
    return $this->belongsTo(Employee::class);
}

public function batch()
{
    return $this->belongsTo(Batch::class);
}

public function process()
{
    return $this->belongsTo(ProductionProcess::class, 'process_id');
}


    public function manager() {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function financeApprover() {
        return $this->belongsTo(User::class, 'finance_approver_id');
    }


    // Example in WorkerPayroll model
public function calculateAmount($completedUnits = 0, $daysWorked = 0)
{
    $worker = $this->worker;
    $amount = 0;

    if ($worker->salary_basis == 'daily') {
        $amount = $worker->labor_amount * $daysWorked;
    } elseif ($worker->salary_basis == 'production') {
        $amount = $worker->labor_amount * $completedUnits;
    } elseif ($worker->salary_basis == 'hourly') {
        $amount = $worker->labor_amount * $daysWorked; // hours worked can be passed as $daysWorked
    }

    $this->amount = $amount;
    
    // Tax calculation
    $taxRate = config('taxes.regions.' . $worker->region . '.tax_rate', 0);
    $this->tax_rate = $taxRate;
    $this->tax_amount = $amount * $taxRate;
    $this->total_amount = $amount + $this->tax_amount;

    return $this;
}

}
