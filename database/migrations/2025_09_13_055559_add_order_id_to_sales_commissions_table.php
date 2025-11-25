<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_commissions', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_commissions', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->after('client_id');
                // Optional FK:
                // $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales_commissions', function (Blueprint $table) {
            if (Schema::hasColumn('sales_commissions', 'order_id')) {
                $table->dropColumn('order_id');
            }
        });
    }
};
