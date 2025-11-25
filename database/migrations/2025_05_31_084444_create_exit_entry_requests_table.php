<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitEntryRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('exit_entry_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('exit_date');
            $table->date('re_entry_date');
            $table->string('reason');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exit_entry_requests');
    }
}