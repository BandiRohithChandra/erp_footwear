<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarningLettersTable extends Migration
{
    public function up()
    {
        Schema::create('warning_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->string('reason');
            $table->text('description');
            $table->date('issue_date');
            $table->string('status')->default('issued'); // issued, signed, uploaded
            $table->string('file_path')->nullable(); // For storing the uploaded signed letter
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('warning_letters');
    }
}