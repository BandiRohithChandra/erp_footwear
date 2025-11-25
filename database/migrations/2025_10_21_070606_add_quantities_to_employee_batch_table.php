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
        $table->json('quantities')->nullable()->after('process_id');
    });
}

public function down()
{
    Schema::table('employee_batch', function (Blueprint $table) {
        $table->dropColumn('quantities');
    });
}

};
