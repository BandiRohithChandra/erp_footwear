<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make sure the 'batches' and 'employees' tables exist before this migration runs
        if (Schema::hasTable('batches') && Schema::hasTable('employees') && !Schema::hasTable('batch_employee')) {
            Schema::create('batch_employee', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('batch_id');
                $table->unsignedBigInteger('employee_id');
                $table->timestamps();

                // Foreign keys
                $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_employee');
    }
};
