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
    Schema::table('batches', function (Blueprint $table) {
        $table->string('po_no')->nullable()->after('name'); // add nullable if not always required
    });
}

public function down(): void
{
    Schema::table('batches', function (Blueprint $table) {
        $table->dropColumn('po_no');
    });
}

};
