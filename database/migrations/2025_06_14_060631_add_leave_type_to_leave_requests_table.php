<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeaveTypeToLeaveRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->string('leave_type')->default('annual')->after('manager_id');
        });
    }

    public function down()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn('leave_type');
        });
    }
}