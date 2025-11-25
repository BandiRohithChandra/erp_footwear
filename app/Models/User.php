<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\SalesCommission;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'profile_picture',
        'manager_id',
        'is_remote',
        'region',
        'iqama_number',
        'iqama_expiry_date',
        'health_card_number',
        'country',
        'category',
        'status',
        'seen_onboarding',

        // Business & Registration fields
        'business_name',
        'company_document',
        'gst_no',
        'gst_certificate',
        'aadhar_number',
        'aadhar_certificate',
        'electricity_certificate',

        // Add these missing fields
        'contact_person',
        'designation',
        'alt_email',
        'alt_phone',
        'website',
        'sales_rep_id',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_remote' => 'boolean',
        'iqama_expiry_date' => 'date',
        'dashboard_cards' => 'array',
        'custom_card_labels' => 'array',
    ];

    // Relationships


    // For salesperson (sales rep)
    public function salesQuotations()
    {
        return $this->hasMany(Quotation::class, 'salesperson_id');
    }

    // For client
    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'client_id');
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            // Mark all new users created offline as unsynced
            if (app()->environment('local') || request()->is('offline/*')) {
                $user->is_synced = 0;
            }
        });
    }



    public function commissions()
    {
        return $this->hasMany(SalesCommission::class, 'employee_id'); // Use employee_id
    }




    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }


    public function salesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id'); // Assuming you store sales rep in `sales_rep_id`
    }


    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function leaveRequests()
    {
        return $this->hasManyThrough(LeaveRequest::class, Employee::class, 'user_id', 'employee_id', 'id', 'id');
    }

    public function salaryAdvanceRequests()
    {
        return $this->hasManyThrough(SalaryAdvanceRequest::class, Employee::class, 'user_id', 'employee_id', 'id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function managedLeaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'manager_id');
    }

    public function managedAdvanceSalaryRequests()
    {
        return $this->hasMany(SalaryAdvanceRequest::class, 'manager_id');
    }

    // Helper
    public function hasNotifications(): bool
    {
        return $this->notifications()->exists();
    }
}
