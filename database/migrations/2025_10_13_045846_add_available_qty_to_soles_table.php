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
    Schema::table('soles', function (Blueprint $table) {
        $table->decimal('available_qty', 10, 2)->default(0);
        $table->json('available_qty_per_size')->nullable();
    });
}

public function down()
{
    Schema::table('soles', function (Blueprint $table) {
        $table->dropColumn(['available_qty', 'available_qty_per_size']);
    });
}

};
