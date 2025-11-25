<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('production_order_product', function (Blueprint $table) {
            $table->json('variations')->nullable()->after('unit_price');
        });
    }

    public function down(): void
    {
        Schema::table('production_order_product', function (Blueprint $table) {
            $table->dropColumn('variations');
        });
    }
};
