<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('delivery_notes', function (Blueprint $table) {
    $table->id();
    $table->string('delivery_note_no')->unique();
    $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
    $table->date('delivery_date')->default(DB::raw('CURRENT_DATE'));
    $table->json('items')->nullable(); // stores variation (color, size, qty)
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_notes');
    }
};
