<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyBatchCounter extends Model
{
    protected $table = 'daily_batch_counters';

    public $timestamps = false; // disable created_at and updated_at
}
