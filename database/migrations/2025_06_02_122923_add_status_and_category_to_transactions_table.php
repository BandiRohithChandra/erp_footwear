<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Skip adding 'category' since it already exists
            $table->string('status')->default('pending')->after('category'); // pending, approved, rejected
            $table->unsignedBigInteger('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');

            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['status', 'approved_by', 'approved_at']);
        });
    }
};