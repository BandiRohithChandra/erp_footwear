<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('quotation_no')->unique()->after('id');
            $table->index('quotation_no');
        });

        // Generate quotation numbers for existing records
        $quotations = DB::table('quotations')->get();
        foreach ($quotations as $index => $quotation) {
            DB::table('quotations')
                ->where('id', $quotation->id)
                ->update(['quotation_no' => 'QTN-' . str_pad($quotation->id, 6, '0', STR_PAD_LEFT)]);
        }
    }

    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('quotation_no');
        });
    }
};