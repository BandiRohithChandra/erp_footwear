<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagerIdToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if the column does not exist before adding it
            if (!Schema::hasColumn('users', 'manager_id')) {
                $table->bigInteger('manager_id')->unsigned()->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Only drop the column if it exists
            if (Schema::hasColumn('users', 'manager_id')) {
                $table->dropColumn('manager_id');
            }
        });
    }
}