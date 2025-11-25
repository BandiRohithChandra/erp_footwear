<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('production_processes', function (Blueprint $table) {
        if (!Schema::hasColumn('production_processes', 'batch_id')) {
            $table->unsignedBigInteger('batch_id')->nullable()->after('product_id');

            // If you have a batches table and want a foreign key:
            // $table->foreign('batch_id')->references('id')->on('batches')->onDelete('set null');
        }
    });
}

public function down(): void
{
    Schema::table('production_processes', function (Blueprint $table) {
        if (Schema::hasColumn('production_processes', 'batch_id')) {
            $table->dropForeign(['batch_id']); // drop FK first if you created one
            $table->dropColumn('batch_id');
        }
    });
}

};
