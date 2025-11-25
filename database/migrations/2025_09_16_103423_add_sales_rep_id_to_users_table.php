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
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('sales_rep_id')->nullable()->after('id');

        // optional: set foreign key if employees table has sales reps
        $table->foreign('sales_rep_id')->references('id')->on('employees')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['sales_rep_id']);
        $table->dropColumn('sales_rep_id');
    });
}

};
