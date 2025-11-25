<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyProductsTableForWarehouses extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->integer('low_stock_threshold')->unsigned()->default(10)->after('price');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('quantity')->unsigned()->default(0);
            $table->dropColumn('low_stock_threshold');
        });
    }
}