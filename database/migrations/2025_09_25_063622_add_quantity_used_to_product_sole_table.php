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
    Schema::table('product_sole', function (Blueprint $table) {
        $table->integer('quantity_used')->default(1);
    });
}

public function down()
{
    Schema::table('product_sole', function (Blueprint $table) {
        $table->dropColumn('quantity_used');
    });
}

};
