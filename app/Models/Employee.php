<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory;

   protected $fillable = [
    'user_id',
    'employee_id',
    'name',
    'email',
    'position',
    'department',
    'skill',
    'role',
    'salary',
    'salary_basis',
    'labor_amount',
    'labor_type',
    'currency',
    'hire_date',
    'status',
    'phone',
    'emergency_contact',
    'date_of_birth',
    'iqama_national_id',
    'personal_email',
    'present_address_line1',
    'present_address_line2',
    'present_city',
    'present_address_arabic_line1',
    'present_address_arabic_line2',
    'present_city_arabic',
    'permanent_address_line1',
    'permanent_state',
    'permanent_pin_code',
    'payment_method',
    'personal_documents',

    // New Employee/Sales fields
    'employee_type',
    'employee_commission',
];


    protected $casts = [
        'hire_date' => 'date',
        'date_of_birth' => 'date',
    ];

    protected $appends = ['age'];

    // 🔹 Auto age
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }

    // Worker payrolls relationship
public function workerPayrolls() {
    return $this->hasMany(WorkerPayroll::class, 'employee_id');
}


public function employeeBatches()
{
    return $this->hasMany(EmployeeBatch::class, 'employee_id');
}


// app/Models/Employee.php

public function batches()
{
    return $this->belongsToMany(Batch::class, 'employee_batch', 'employee_id', 'batch_id')
        ->withPivot(['quantity','labor_rate','labor_status','start_date','end_date'])
        ->withTimestamps();
}



    // 🔹 Relationships
    public function primaryProcess() { return $this->belongsTo(Process::class, 'process_id'); }
    public function processes() {
        return $this->belongsToMany(ProductionProcess::class, 'employee_batch', 'employee_id', 'process_id')
            ->withPivot(['batch_id','quantity','labor_rate','labor_status','start_date','end_date'])
            ->withTimestamps();
    }
    public function salaryAdvances() { return $this->hasMany(SalaryAdvance::class); }
    public function payrolls() { return $this->hasMany(Payroll::class); }
    public function user() { return $this->belongsTo(User::class, 'user_id', 'id'); }
    public function attendances() { return $this->hasMany(Attendance::class); }
    public function leaveRequests() { return $this->hasMany(LeaveRequest::class); }
    public function salaryAdvanceRequests() { return $this->hasMany(SalaryAdvanceRequest::class); }
    public function warningLetters() { return $this->hasMany(WarningLetter::class); }
    public function expenseClaims() { return $this->hasMany(ExpenseClaim::class); }
    public function trainingRequests() { return $this->hasMany(TrainingRequest::class); }
    public function performanceReviews() { return $this->hasMany(PerformanceReview::class); }
    public function productionProcesses() { return $this->hasMany(ProductionProcess::class); }

    // 🔹 Document accessor
    public function getDocumentUrlAttribute()
    {
        return $this->personal_documents ? asset('storage/' . $this->personal_documents) : null;
    }

    public function commissions()
{
    return $this->hasMany(SalesCommission::class, 'employee_id');
}


// Get clients with commission for this employee
public function clientsWithCommissions()
{
    return $this->commissions()->with('client'); 
}


public function clients()
{
    return $this->commissions()->with('client', 'salesRep')->get()->map(function ($commission) {
        return [
            'id' => $commission->client->id ?? null,
            'name' => $commission->client->name ?? 'N/A',
            'sales_rep_name' => $commission->salesRep->name ?? 'N/A',
            'commission' => number_format($commission->commission_amount, 2)
        ];
    });
}


    // 🔹 Auto-generate employee ID
    protected static function booted()
    {
        static::creating(function ($employee) {
            $month = date('m');
            $year = date('y');
            $count = self::whereYear('created_at', date('Y'))
                        ->whereMonth('created_at', date('m'))
                        ->count() + 1;

            $employee->employee_id = sprintf('EMP-%s%s-%03d', $month, $year, $count);
        });
    }
}
