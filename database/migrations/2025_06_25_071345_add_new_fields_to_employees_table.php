<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToEmployeesTable extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable();
            $table->integer('age')->nullable();
            $table->string('igama_national_id', 10)->nullable();
            $table->string('personal_email')->nullable()->unique();
            $table->string('present_address_line1', 255)->nullable();
            $table->string('present_address_line2', 255)->nullable();
            $table->string('present_city', 100)->nullable();
            $table->string('present_address_arabic_line1', 255)->nullable();
            $table->string('present_address_arabic_line2', 255)->nullable();
            $table->string('present_city_arabic', 100)->nullable();
            $table->string('permanent_address_line1', 255)->nullable();
            $table->string('permanent_state', 100)->nullable();
            $table->string('permanent_pin_code', 10)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('personal_documents')->nullable();
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'age',
                'igama_national_id',
                'personal_email',
                'present_address_line1',
                'present_address_line2',
                'present_city',
                'present_address_arabic_line1',
                'present_address_arabic_line2',
                'present_city_arabic',
                'permanent_address_line1',
                'permanent_state',
                'permanent_pin_code',
                'payment_method',
                'personal_documents'
            ]);
        });
    }
}