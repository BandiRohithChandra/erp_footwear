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
    Schema::create('processes', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->unsignedBigInteger('parent_id')->nullable(); // for hierarchy
        $table->string('operator')->nullable();
        $table->integer('progress_percent')->default(0);
        $table->string('status')->default('Pending'); // Pending, In Progress, Completed
        $table->integer('sequence')->default(0); // order of execution
        $table->timestamps();

        $table->foreign('parent_id')->references('id')->on('processes')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('processes');
}

};
