<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('business_name')->nullable();
        $table->string('phone', 15)->nullable()->change();
        $table->string('company_document')->nullable(); // for uploaded file
        $table->string('gst_no')->nullable();
        $table->enum('category', ['wholesale', 'retail'])->nullable();
    });
}
    

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['business_name', 'company_document', 'gst_no', 'category']);
    });
}
};
