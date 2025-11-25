<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    protected $fillable = [
        'bank_name', 'branch_name', 'account_holder', 'account_number', 'ifsc_code', 'upi_id'
    ];
}

