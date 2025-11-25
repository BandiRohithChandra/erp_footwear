<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop old foreign key
            $table->dropForeign(['client_id']);
            
            // Make sure client_id can accept null
            $table->unsignedBigInteger('client_id')->nullable()->change();

            // Add new foreign key pointing to users.id
            $table->foreign('client_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop new foreign key
            $table->dropForeign(['client_id']);

            // Optionally revert client_id change if needed
            $table->unsignedBigInteger('client_id')->nullable(false)->change();

            // Restore old foreign key to clients.id (if table exists)
            $table->foreign('client_id')
                  ->references('id')
                  ->on('clients')
                  ->onDelete('set null');
        });
    }
};
