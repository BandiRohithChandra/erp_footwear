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
        Schema::create('batch_flows', function (Blueprint $table) {
    $table->id();
    
    // Use string to match job_batches.id type
    $table->string('batch_id'); 
    $table->foreign('batch_id')
          ->references('id')
          ->on('job_batches')
          ->onDelete('cascade');

    // Quotation foreign key (nullable)
    $table->foreignId('quotation_id')
          ->nullable()
          ->constrained('quotations')
          ->onDelete('set null');

    $table->string('status')->default('pending');
    $table->integer('quantity')->default(0);
    $table->integer('priority')->default(1);
    $table->date('start_date')->nullable();
    $table->date('end_date')->nullable();

    // Created by foreign key
    $table->foreignId('created_by')
          ->constrained('users')
          ->onDelete('cascade');

    $table->json('metadata')->nullable();
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_flows');
    }
};
