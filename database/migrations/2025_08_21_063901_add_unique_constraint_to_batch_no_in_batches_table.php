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
        if (Schema::hasTable('batches')) {
            Schema::table('batches', function (Blueprint $table) {
                if (!Schema::hasColumn('batches', 'batch_no')) return;
                $table->unique('batch_no');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('batches')) {
            Schema::table('batches', function (Blueprint $table) {
                $table->dropUnique(['batch_no']);
            });
        }
    }

};
