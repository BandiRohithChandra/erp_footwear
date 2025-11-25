<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];


    public function orders()
{
    return $this->hasMany(Order::class);
}

public function salesRep()
{
    return $this->belongsTo(User::class, 'sales_rep_id'); // clients.sales_rep_id -> users.id
}

public function commissions()
{
    return $this->hasMany(SalesCommission::class, 'client_id');
}


}