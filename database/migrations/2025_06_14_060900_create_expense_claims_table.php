<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseClaimsTable extends Migration
{
    public function up()
    {
        Schema::create('expense_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->string('description');
            $table->string('status')->default('pending');
            $table->date('expense_date');
            $table->string('attachment_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expense_claims');
    }
}