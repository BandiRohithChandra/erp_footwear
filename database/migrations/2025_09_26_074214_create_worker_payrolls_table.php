<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('worker_payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('batch_id')->nullable()->constrained('batches')->onDelete('set null');
            $table->foreignId('process_id')->nullable()->constrained('production_processes')->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->date('payment_date');
            $table->string('status')->default('pending');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('finance_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('disbursed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_payrolls');
    }
};
