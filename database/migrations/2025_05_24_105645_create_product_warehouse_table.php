<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductWarehouseTable extends Migration
{
    public function up()
    {
        Schema::create('product_warehouse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_warehouse');
    }
}