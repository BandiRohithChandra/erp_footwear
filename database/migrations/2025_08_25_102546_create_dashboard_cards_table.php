<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dashboard_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('title');
            $table->string('count_type')->nullable(); // e.g., total_users, total_orders
            $table->string('link')->nullable();
            $table->string('icon')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_cards');
    }
};
