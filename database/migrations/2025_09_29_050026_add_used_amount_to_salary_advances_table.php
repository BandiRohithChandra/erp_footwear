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
    Schema::table('salary_advances', function ($table) {
        $table->decimal('used_amount', 10, 2)->default(0);
    });
}

public function down()
{
    Schema::table('salary_advances', function ($table) {
        $table->dropColumn('used_amount');
    });
}

};
