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
        $table->string('transport_phone', 20)->nullable()->after('transport_id');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('transport_phone');
    });
}

};
