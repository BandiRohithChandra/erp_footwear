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
        Schema::table('quotations', function (Blueprint $table) {
            $table->unsignedBigInteger('salesperson_id')->nullable()->after('warehouse_id');

            // Optional foreign key if you want to enforce constraints
            $table->foreign('salesperson_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign(['salesperson_id']);
            $table->dropColumn('salesperson_id');
        });
    }
};
