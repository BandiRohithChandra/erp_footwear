<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('tax_rate', 5, 2)->nullable()->after('region');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('tax_rate', 5, 2)->nullable()->after('region');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('tax_rate');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('tax_rate');
        });
    }
};