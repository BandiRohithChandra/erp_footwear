<?php
// Create a new migration: php artisan make:migration make_product_id_nullable_in_soles_and_raw_materials

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('soles', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();
        });

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('soles', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
        });

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
        });
    }
};