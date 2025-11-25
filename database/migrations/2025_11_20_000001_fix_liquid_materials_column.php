<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('liquid_materials', function (Blueprint $table) {
            // If per_unit_length exists, rename it to per_unit_volume
            if (Schema::hasColumn('liquid_materials', 'per_unit_length') && !Schema::hasColumn('liquid_materials', 'per_unit_volume')) {
                $table->renameColumn('per_unit_length', 'per_unit_volume');
            }
            // If neither exists, add per_unit_volume
            elseif (!Schema::hasColumn('liquid_materials', 'per_unit_volume')) {
                $table->decimal('per_unit_volume', 10, 2)->nullable()->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('liquid_materials', function (Blueprint $table) {
            if (Schema::hasColumn('liquid_materials', 'per_unit_volume')) {
                $table->renameColumn('per_unit_volume', 'per_unit_length');
            }
        });
    }
};
