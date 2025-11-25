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
        if (!Schema::hasTable('sales_commissions')) {
            Schema::create('sales_commissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('client_id');
                $table->unsignedBigInteger('order_id')->nullable(); // add order_id
                $table->decimal('commission_amount', 10, 2);
                $table->date('commission_date')->default(now());
                $table->string('notes')->nullable();
                $table->timestamps();

                // Optional: foreign keys if needed
                // $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
                // $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_commissions');
    }
};
