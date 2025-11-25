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
    Schema::table('products', function (Blueprint $table) {
        // Commission as percentage (e.g., 5 = 5%)
        $table->decimal('commission', 5, 2)->nullable()->after('profit');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('commission');
    });
}

};
