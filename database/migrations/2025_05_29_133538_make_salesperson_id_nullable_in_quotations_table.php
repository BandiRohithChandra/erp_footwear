<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSalespersonIdNullableInQuotationsTable extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Only modify if the column exists
            if (Schema::hasColumn('quotations', 'salesperson_id')) {
                $table->bigInteger('salesperson_id')->unsigned()->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (Schema::hasColumn('quotations', 'salesperson_id')) {
                $table->bigInteger('salesperson_id')->unsigned()->nullable(false)->change();
            }
        });
    }
}