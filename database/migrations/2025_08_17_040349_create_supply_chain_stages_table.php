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
    Schema::create('supply_chain_stages', function (Blueprint $table) {
        $table->id();
        $table->string('status'); // e.g. 'completed', 'pending'
        $table->date('due_date'); // deadline
        $table->timestamps();     // created_at, updated_at
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_chain_stages');
    }
};
