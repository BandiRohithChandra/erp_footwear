<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('client_order_id')->nullable()->after('quotation_id');
            $table->foreign('client_order_id')->references('id')->on('orders')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->dropForeign(['client_order_id']);
            $table->dropColumn('client_order_id');
        });
    }
};
