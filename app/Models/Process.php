<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
        'operator',
        'progress_percent',
        'status',
        'sequence',
    ];

    public function parent()
    {
        return $this->belongsTo(Process::class, 'parent_id');
    }

public function products()
{
    return $this->belongsToMany(Product::class, 'product_processes')
                ->withPivot('labor_rate')
                ->withTimestamps();
}



    public function children()
    {
        return $this->hasMany(Process::class, 'parent_id')->orderBy('sequence');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function productionProcesses()
    {
        return $this->hasMany(ProductionProcess::class);
    }
}
