<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Use raw SQL to rename the column if it exists
        if (Schema::hasColumn('liquid_materials', 'per_unit_length')) {
            DB::statement('ALTER TABLE liquid_materials CHANGE per_unit_length per_unit_volume DECIMAL(10,2) NULL');
        }
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE liquid_materials CHANGE per_unit_volume per_unit_length DECIMAL(10,2) NULL');
    }
};
