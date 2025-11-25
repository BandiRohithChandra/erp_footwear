<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExitEntryRequestsTableEmployeeIdForeignKey extends Migration
{
    public function up()
    {
        Schema::table('exit_entry_requests', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('exit_entry_requests', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}