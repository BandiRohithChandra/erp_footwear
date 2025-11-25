<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->string('region')->default('sa');
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['region', 'tax_amount', 'total_amount']);
        });
    }
};