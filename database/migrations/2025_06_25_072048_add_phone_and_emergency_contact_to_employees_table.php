<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneAndEmergencyContactToEmployeesTable extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('hire_date');
            $table->string('emergency_contact', 20)->nullable()->after('phone');
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['phone', 'emergency_contact']);
        });
    }
}