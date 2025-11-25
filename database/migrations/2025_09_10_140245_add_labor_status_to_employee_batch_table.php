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
        $table->string('labor_status')->default('pending')->after('labor_rate');
    });
}

public function down()
{
    Schema::table('employee_batch', function (Blueprint $table) {
        $table->dropColumn('labor_status');
    });
}

};
