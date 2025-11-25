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
    Schema::table('employee_batch', function (Blueprint $table) {
        $table->decimal('advance_amount', 15,2)->default(0);
        $table->decimal('paid_amount', 15,2)->default(0);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_batch', function (Blueprint $table) {
            //
        });
    }
};
