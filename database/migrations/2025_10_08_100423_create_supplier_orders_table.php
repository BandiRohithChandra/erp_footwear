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
    Schema::create('supplier_orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
        $table->string('po_number')->unique();
        $table->json('items'); // list of ordered raw materials
        $table->decimal('total_amount', 10, 2)->default(0);
        $table->decimal('paid_amount', 10, 2)->default(0);
        $table->string('payment_status')->default('pending'); // pending / partial / paid
        $table->string('status')->default('pending'); // pending / processing / delivered
        $table->date('order_date')->nullable();
        $table->date('expected_delivery')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_orders');
    }
};
