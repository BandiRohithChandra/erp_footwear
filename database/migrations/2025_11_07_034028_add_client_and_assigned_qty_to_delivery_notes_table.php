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
        Schema::table('delivery_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable()->after('batch_id');
            $table->integer('assigned_qty')->default(0)->after('client_id');

            // Add foreign key constraint for relational integrity
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_notes', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn(['client_id', 'assigned_qty']);
        });
    }
};
