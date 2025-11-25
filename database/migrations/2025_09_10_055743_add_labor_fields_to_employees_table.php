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
    Schema::table('employees', function (Blueprint $table) {
        $table->enum('salary_basis', ['monthly','weekly','daily'])->nullable()->after('salary');
        $table->decimal('labor_amount', 10, 2)->nullable()->after('salary_basis');
    });
}

public function down()
{
    Schema::table('employees', function (Blueprint $table) {
        $table->dropColumn(['salary_basis', 'labor_amount']);
    });
}

};
