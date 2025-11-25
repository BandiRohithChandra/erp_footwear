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
    Schema::table('stock_arrivals', function (Blueprint $table) {
        $table->string('reason')->nullable();
        $table->string('party')->nullable();
        $table->string('article_no')->nullable();
    });
}

public function down()
{
    Schema::table('stock_arrivals', function (Blueprint $table) {
        $table->dropColumn(['reason','party','article_no']);
    });
}

};
