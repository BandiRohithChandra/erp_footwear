<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'location',
        'description',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_warehouse')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}