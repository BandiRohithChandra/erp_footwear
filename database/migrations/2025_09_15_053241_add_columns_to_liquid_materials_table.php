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
        Schema::table('liquid_materials', function (Blueprint $table) {
            if (!Schema::hasColumn('liquid_materials', 'quantity')) {
                $table->integer('quantity')->default(0)->after('unit');
            }
            if (!Schema::hasColumn('liquid_materials', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('liquid_materials', 'per_unit_length')) {
                $table->decimal('per_unit_length', 10, 2)->nullable()->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('liquid_materials', function (Blueprint $table) {
            if (Schema::hasColumn('liquid_materials', 'quantity')) {
                $table->dropColumn('quantity');
            }
            if (Schema::hasColumn('liquid_materials', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('liquid_materials', 'per_unit_length')) {
                $table->dropColumn('per_unit_length');
            }
        });
    }
};
