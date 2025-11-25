<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemsToInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->json('items')->nullable()->after('amount'); // Add a JSON column for storing quotation items
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('items');
        });
    }
}