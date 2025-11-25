<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('tax_rate', 5, 2)->nullable()->after('price');
            $table->decimal('tax_amount', 10, 2)->nullable()->after('tax_rate');
            $table->decimal('total_price', 10, 2)->nullable()->after('tax_amount');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['tax_rate', 'tax_amount', 'total_price']);
        });
    }
};