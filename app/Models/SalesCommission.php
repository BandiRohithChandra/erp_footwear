<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesCommission extends Model
{
    protected $table = 'sales_commissions';

    protected $fillable = [
    'employee_id', // correct field in DB
    'client_id',
    'commission_amount',
    'commission_date',
    'notes',
    'order_id'
];


    // Link to the sales rep (User)
    public function salesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    // Link to the client
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
