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
        Schema::create('advance_deductions', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('salary_advance_id');
    $table->unsignedBigInteger('employee_id');
    $table->unsignedBigInteger('batch_id')->nullable();
    $table->decimal('deducted_amount', 10, 2);
    $table->timestamps();

    $table->foreign('salary_advance_id')->references('id')->on('salary_advances')->onDelete('cascade');
    $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
    $table->foreign('batch_id')->references('id')->on('employee_batch')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_deductions');
    }
};
