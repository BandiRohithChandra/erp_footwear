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
        Schema::create('employee_batch', function (Blueprint $table) {
    $table->id();
    $table->foreignId('batch_id')->constrained()->onDelete('cascade');
    $table->foreignId('employee_id')->constrained()->onDelete('cascade');
    $table->unsignedBigInteger('process_id');
    $table->integer('quantity')->default(1);
    $table->decimal('labor_rate', 8, 2)->default(0);
    $table->timestamps();

    $table->unique(['batch_id', 'employee_id', 'process_id']); // prevent duplicates
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_batch');
    }
};
