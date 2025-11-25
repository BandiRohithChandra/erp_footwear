<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('total_amount'); // pending, manager_approved, finance_approved, disbursed
            $table->unsignedBigInteger('manager_id')->nullable()->after('status');
            $table->unsignedBigInteger('finance_approver_id')->nullable()->after('manager_id');
            $table->timestamp('disbursed_at')->nullable()->after('finance_approver_id');

            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('finance_approver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropForeign(['finance_approver_id']);
            $table->dropColumn(['status', 'manager_id', 'finance_approver_id', 'disbursed_at']);
        });
    }
};