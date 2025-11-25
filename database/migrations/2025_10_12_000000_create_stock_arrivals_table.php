<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('stock_arrivals')) {
            Schema::create('stock_arrivals', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('item_id');
                $table->string('type'); // 'sole', 'material', 'liquid'
                $table->string('color')->nullable();
                $table->string('size')->nullable(); // For soles
                $table->decimal('quantity', 10, 2)->default(0);
                $table->string('status')->default('pending'); // 'pending', 'received'
                $table->text('reason')->nullable();
                $table->unsignedBigInteger('supplier_id')->nullable();
                $table->string('reference')->nullable();
                $table->string('article_no')->nullable();
                $table->timestamp('received_at')->nullable();
                $table->timestamps();

                $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_arrivals');
    }
};
