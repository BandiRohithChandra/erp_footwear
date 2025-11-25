<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('soles', function (Blueprint $table) {
            if (!Schema::hasColumn('soles', 'sizes_qty')) {
                $table->json('sizes_qty')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('soles', function (Blueprint $table) {
            if (Schema::hasColumn('soles', 'sizes_qty')) {
                $table->dropColumn('sizes_qty');
            }
        });
    }
};
