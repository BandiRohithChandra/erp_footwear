<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWarehouseIdToSalesTable extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->after('product_id');
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
        });
    }
}