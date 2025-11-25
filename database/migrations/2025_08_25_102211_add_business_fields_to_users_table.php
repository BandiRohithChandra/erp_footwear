<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('aadhar_number')->nullable()->after('category');
            $table->string('aadhar_certificate')->nullable()->after('aadhar_number');
            $table->string('gst_certificate')->nullable()->after('gst_no');
            $table->string('electricity_certificate')->nullable()->after('gst_certificate');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'aadhar_number',
                'aadhar_certificate',
                'gst_certificate',
                'electricity_certificate'
            ]);
        });
    }
};
