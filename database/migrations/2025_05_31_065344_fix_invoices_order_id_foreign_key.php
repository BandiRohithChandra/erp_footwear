<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixInvoicesOrderIdForeignKey extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['order_id']);

            // Add a new foreign key constraint referencing production_orders
            $table->foreign('order_id')
                  ->references('id')
                  ->on('production_orders')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop the new foreign key
            $table->dropForeign(['order_id']);

            // Revert to the original foreign key (referencing orders)
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');
        });
    }
}