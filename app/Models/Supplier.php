<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'business_name', // added
        'email',
        'phone',
        'address',
        'material_types',
        'gst_number',
    ];

    // Relation to normal orders (if needed)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relation to supplier-specific orders (used in show.blade)
    public function supplierOrders()
    {
        return $this->hasMany(SupplierOrder::class);
    }

    public function returns()
{
    return $this->hasMany(SupplierReturn::class);
}

}
