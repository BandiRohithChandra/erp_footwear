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
                if (!Schema::hasColumn('batches', 'created_by')) {
                    $table->string('created_by')->nullable(); // optional
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('batches') && Schema::hasColumn('batches', 'created_by')) {
            Schema::table('batches', function (Blueprint $table) {
                $table->dropColumn('created_by');
            });
        }
    }

};
