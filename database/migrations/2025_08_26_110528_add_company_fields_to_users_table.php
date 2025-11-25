<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('contact_person')->nullable();
        $table->string('designation')->nullable();
        $table->string('website')->nullable();
        $table->string('alt_email')->nullable();
        $table->string('alt_phone')->nullable();
        $table->string('city')->nullable();
        $table->string('state')->nullable();
        $table->string('pincode')->nullable();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'contact_person',
            'designation',
            'website',
            'alt_email',
            'alt_phone',
            'city',
            'state',
            'pincode',
        ]);
    });
}

};
