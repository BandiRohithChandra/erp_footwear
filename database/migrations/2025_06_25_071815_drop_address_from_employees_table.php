<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAddressFromEmployeesTable extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'address')) {
                $table->dropColumn('address');
            }
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('address', 255)->nullable();
        });
    }
}