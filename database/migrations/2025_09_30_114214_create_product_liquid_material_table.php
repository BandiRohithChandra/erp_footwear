<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_liquid_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('liquid_material_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity_used', 10, 2)->default(0); // optional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_liquid_material');
    }
};
