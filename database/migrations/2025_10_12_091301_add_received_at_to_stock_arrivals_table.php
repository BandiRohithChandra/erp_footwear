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
        $table->timestamp('received_at')->nullable()->after('updated_at');
    });
}

public function down()
{
    Schema::table('stock_arrivals', function (Blueprint $table) {
        $table->dropColumn('received_at');
    });
}

};
