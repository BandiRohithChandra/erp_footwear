<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // raw_materials table updates
        Schema::table('raw_materials', function (Blueprint $table) {
            if (Schema::hasColumn('raw_materials', 'qty_per_unit')) {
                $table->renameColumn('qty_per_unit', 'per_unit_length');
            }
            $table->decimal('quantity', 10, 2)->default(0)->change();
        });

        // liquid_materials table updates
        Schema::table('liquid_materials', function (Blueprint $table) {
            if (Schema::hasColumn('liquid_materials', 'qty_per_unit')) {
                $table->renameColumn('qty_per_unit', 'per_unit_volume');
            }
            $table->decimal('quantity', 10, 2)->default(0)->change();
        });

        // stocks table updates
        Schema::table('stocks', function (Blueprint $table) {
            $table->decimal('qty_available', 10, 2)->default(0)->change();
            $table->string('size')->nullable()->change(); // changed from int to string
        });
    }

    public function down()
    {
        // rollback changes if needed
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->renameColumn('per_unit_length', 'qty_per_unit');
            $table->integer('quantity')->default(0)->change();
        });

        Schema::table('liquid_materials', function (Blueprint $table) {
            $table->renameColumn('per_unit_volume', 'qty_per_unit');
            $table->integer('quantity')->default(0)->change();
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->integer('qty_available')->default(0)->change();
            $table->integer('size')->nullable()->change();
        });
    }
};
