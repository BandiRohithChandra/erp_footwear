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
    Schema::table('delivery_notes', function (Blueprint $table) {
        $table->dropForeign(['client_id']);
        $table->foreign('client_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('delivery_notes', function (Blueprint $table) {
        $table->dropForeign(['client_id']);
        $table->foreign('client_id')
              ->references('id')
              ->on('clients')
              ->onDelete('cascade');
    });
}

};
