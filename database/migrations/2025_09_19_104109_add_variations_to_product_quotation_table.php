<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_quotation', function (Blueprint $table) {
            // Only add variations column (no article_no needed)
            $table->json('variations')->nullable()->after('unit_price');
            
            // Add indexes for better performance
            $table->index(['quotation_id', 'created_at']);
            $table->index(['product_id', 'quantity']);
        });
    }

    public function down(): void
    {
        Schema::table('product_quotation', function (Blueprint $table) {
            $table->dropColumn('variations');
            $table->dropIndex(['quotation_id', 'created_at']);
            $table->dropIndex(['product_id', 'quantity']);
        });
    }
};