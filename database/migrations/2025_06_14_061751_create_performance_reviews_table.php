<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformanceReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null'); // Manager or HR
            $table->date('review_date');
            $table->text('feedback')->nullable();
            $table->integer('rating')->nullable(); // e.g., 1-5 scale
            $table->text('goals')->nullable(); // Future goals
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('performance_reviews');
    }
}