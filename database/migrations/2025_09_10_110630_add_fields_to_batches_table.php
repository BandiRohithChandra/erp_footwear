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
    Schema::table('batches', function (Blueprint $table) {
        $table->string('batch_no')->unique()->after('id');
        $table->string('name')->after('batch_no');
        $table->unsignedBigInteger('product_id')->after('name');
        $table->integer('quantity')->after('product_id');
        $table->string('status')->default('pending')->after('quantity');
        $table->string('priority')->default('normal')->after('status');
        $table->date('start_date')->nullable()->after('priority');
        $table->date('end_date')->nullable()->after('start_date');
        $table->string('created_by')->nullable()->after('end_date');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            //
        });
    }
};
