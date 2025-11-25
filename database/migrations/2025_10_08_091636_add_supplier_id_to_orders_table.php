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
    Schema::table('orders', function (Blueprint $table) {
        $table->foreignId('supplier_id')->nullable()->after('id')->constrained('suppliers');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['supplier_id']);
        $table->dropColumn('supplier_id');
    });
}

};
