<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('advance_salary_requests', 'salary_advance_requests');
    }

    public function down(): void
    {
        Schema::rename('salary_advance_requests', 'advance_salary_requests');
    }
};