<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
    $table->json('cart_items')->nullable()->after('customer_name');
    $table->decimal('subtotal', 12, 2)->default(0)->after('cart_items');
    $table->decimal('gst', 12, 2)->default(0)->after('subtotal');
    $table->decimal('total', 12, 2)->default(0)->after('gst');
    $table->string('payment_method')->nullable()->after('total');
});

    }

    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'gst', 'total', 'payment_method']);
        });
    }
};
