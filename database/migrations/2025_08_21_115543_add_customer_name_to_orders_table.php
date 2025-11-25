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
    Schema::table('orders', function (Blueprint $table) {
        // Simply add the column at the end of the table
        $table->string('customer_name')->nullable();
        
        // OR, if you want it after a specific existing column:
        // $table->string('customer_name')->nullable()->after('order_id'); 
        // Make sure 'order_id' exists in the table
    });
}


public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('customer_name');
    });
}

};
