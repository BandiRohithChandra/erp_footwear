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
    Schema::table('batches', function ($table) {
        $table->json('labor_assignments')->nullable();
    });
}

public function down()
{
    Schema::table('batches', function ($table) {
        $table->dropColumn('labor_assignments');
    });
}

};
