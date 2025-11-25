<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('training_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('training_title');
            $table->text('description');
            $table->date('proposed_date');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('training_requests');
    }
}