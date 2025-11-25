<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('reference_type')->nullable()->after('category'); // invoice, supplier_order, worker_payroll, salary_advance, expense_claim
            $table->unsignedBigInteger('reference_id')->nullable()->after('reference_type');
            $table->index(['reference_type', 'reference_id'], 'txn_ref_idx');
        });
    }
    public function down(): void {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('txn_ref_idx');
            $table->dropColumn(['reference_type', 'reference_id']);
        });
    }
};
