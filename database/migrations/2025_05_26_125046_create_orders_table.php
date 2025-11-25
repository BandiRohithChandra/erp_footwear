<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('quotation_id');
        $table->string('status')->default('pending');
        $table->timestamps();

        $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('orders');
}
};