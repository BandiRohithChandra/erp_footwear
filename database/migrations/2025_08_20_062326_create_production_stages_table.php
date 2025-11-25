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
        Schema::create('production_stages', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('production_order_id'); // Link to production_orders
    $table->string('name'); // Upper, Lower, Full Body, Finishing
    $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
    $table->unsignedBigInteger('employee_id')->nullable(); // Worker assigned
    $table->integer('progress_percent')->default(0);
    $table->time('time_taken')->nullable();
    $table->timestamps();

    $table->foreign('production_order_id')->references('id')->on('production_orders')->onDelete('cascade');
    $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_stages');
    }
};
