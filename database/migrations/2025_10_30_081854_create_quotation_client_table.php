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
        Schema::create('quotation_client', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('quotation_id')
                ->constrained('quotations')
                ->onDelete('cascade');

            $table->foreignId('client_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(['quotation_id', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_client');
    }
};
