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
   Schema::table('production_processes', function (Blueprint $table) {
    $table->unsignedBigInteger('process_id')->nullable()->after('product_id');
    $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
});

}

public function down()
{
    Schema::table('production_processes', function (Blueprint $table) {
        $table->dropColumn('process_order');
    });
}

};
