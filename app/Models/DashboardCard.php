<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'title',
        'count_type',
        'link',
        'icon',
        'position',
    ];
}
