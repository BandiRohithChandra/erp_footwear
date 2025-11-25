<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitPriceToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add unit_price column if it doesn't exist
            if (!Schema::hasColumn('products', 'unit_price')) {
                $table->decimal('unit_price', 8, 2)->default(0)->after('price');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'unit_price')) {
                $table->dropColumn('unit_price');
            }
        });
    }
}