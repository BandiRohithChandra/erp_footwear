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
    Schema::table('orders', function (Blueprint $table) {
    if (!Schema::hasColumn('orders', 'address')) {
        $table->string('address')->nullable();
    } else {
        $table->string('address')->nullable()->change();
    }
});

}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('address')->nullable(false)->change();
    });
}

};
