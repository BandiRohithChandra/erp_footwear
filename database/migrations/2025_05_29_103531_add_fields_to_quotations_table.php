<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToQuotationsTable extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (!Schema::hasColumn('quotations', 'warehouse_id')) {
                $table->bigInteger('warehouse_id')->unsigned()->notNull();
            }
            // add other fields similarly
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('warehouse_id');
            // drop other fields if needed
        });
    }
}