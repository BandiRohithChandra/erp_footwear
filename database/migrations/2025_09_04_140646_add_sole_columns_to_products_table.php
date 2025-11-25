<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sole_name')->nullable()->after('description');
            $table->string('sole_color')->nullable()->after('sole_name');
            $table->string('article_subtype')->nullable()->after('sole_color');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sole_name', 'sole_color', 'article_subtype']);
        });
    }
};
