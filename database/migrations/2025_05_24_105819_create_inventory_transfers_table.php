<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTransfersTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('to_warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->integer('quantity')->unsigned();
            $table->date('transfer_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_transfers');
    }
}