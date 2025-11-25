<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->foreignId('manager_id')->nullable()->change();
        });

        Schema::table('salary_advance_requests', function (Blueprint $table) {
            $table->foreignId('manager_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->foreignId('manager_id')->nullable(false)->change();
        });

        Schema::table('salary_advance_requests', function (Blueprint $table) {
            $table->foreignId('manager_id')->nullable(false)->change();
        });
    }
};