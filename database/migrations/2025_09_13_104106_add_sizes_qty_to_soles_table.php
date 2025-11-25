<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('soles', function (Blueprint $table) {
    if (!Schema::hasColumn('soles', 'unit_price')) {
        $table->decimal('unit_price', 10, 2)->default(0); // removed 'after'
    }
});

}

public function down(): void
{
    Schema::table('soles', function (Blueprint $table) {
        if (Schema::hasColumn('soles', 'unit_price')) {
            $table->dropColumn('unit_price');
        }
    });
}


};
