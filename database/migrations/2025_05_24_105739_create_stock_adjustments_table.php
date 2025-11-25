<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0); // Can be positive (add stock) or negative (remove stock)
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->date('adjustment_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_adjustments');
    }
}