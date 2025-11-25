<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    protected $fillable = [
        'batch_no',
        'name',
        'product_id',
        'quantity',
        'status',
        'priority',
        'po_no',
        'start_date',
        'end_date',
        'created_by',
        'variations',
        'client_id',
        'labor_assignments', // ✅ Add this
    ];

    protected $casts = [
        'variations' => 'array',
        'labor_assignments' => 'array', // ✅ Add this line
        'start_date' => 'date',
        'end_date' => 'date',
        'client_id' => 'integer',
    ];

    // Relationships ↓
    public function materials()
    {
        return $this->hasMany(RawMaterial::class, 'batch_id')->where('type', 'Material');
    }

    public function liquidMaterials()
    {
        return $this->hasMany(RawMaterial::class, 'batch_id')->where('type', 'Liquid Material');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function clients()
    {
        return $this->belongsToMany(User::class, 'batch_client', 'batch_id', 'client_id')
                    ->whereIn('users.category', ['wholesale', 'retail']);
    }

    public function workers(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_batch')
                    ->withPivot('process_id', 'quantity', 'labor_rate', 'labor_status', 'start_date', 'end_date')
                    ->withTimestamps();
    }

    public function workerAssignments(): HasMany
    {
        return $this->hasMany(EmployeeBatch::class, 'batch_id');
    }

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class, 'batch_id');
    }

    public function batchFlows(): HasMany
    {
        return $this->hasMany(BatchFlow::class, 'batch_id');
    }

    public function employeeBatches()
    {
        return $this->hasMany(EmployeeBatch::class, 'batch_id');
    }

    public function productionProcesses(): HasMany
    {
        return $this->hasMany(ProductionProcess::class, 'batch_id');
    }


    public function deliveryNote()
{
    return $this->hasOne(DeliveryNote::class);
}

}
