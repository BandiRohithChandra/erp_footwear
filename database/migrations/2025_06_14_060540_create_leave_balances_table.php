<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveBalancesTable extends Migration
{
    public function up()
    {
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('leave_type')->default('annual'); // e.g., annual, sick
            $table->decimal('total_days', 5, 2)->default(0); // Total allocated days
            $table->decimal('used_days', 5, 2)->default(0); // Days used
            $table->decimal('remaining_days', 5, 2)->default(0); // Remaining days
            $table->year('year')->default(date('Y')); // Leave balance for a specific year
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_balances');
    }
}