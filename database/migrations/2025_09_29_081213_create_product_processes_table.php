<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('process_id')->constrained()->cascadeOnDelete();
            $table->decimal('labor_rate', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'process_id']); // prevent duplicates
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_processes');
    }
};
