<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSaudiFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('region')->nullable()->after('email'); // To store the region (e.g., 'saudi_arabia')
            $table->string('iqama_number')->nullable()->after('region');
            $table->date('iqama_expiry_date')->nullable()->after('iqama_number');
            $table->string('health_card_number')->nullable()->after('iqama_expiry_date');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['region', 'iqama_number', 'iqama_expiry_date', 'health_card_number']);
        });
    }
}