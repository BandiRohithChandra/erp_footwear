<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('supplier_returns', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('supplier_id');
        $table->unsignedBigInteger('order_id')->nullable(); // PO reference
        $table->json('items');  // {sole_id, size, qty, reason}
        $table->string('status')->default('pending'); // pending, returned, completed
        $table->text('remarks')->nullable();
        $table->timestamps();

        $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        $table->foreign('order_id')->references('id')->on('supplier_orders')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_returns');
    }
};
