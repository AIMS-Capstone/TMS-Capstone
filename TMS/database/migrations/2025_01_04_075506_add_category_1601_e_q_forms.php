<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('1601eq_forms', function (Blueprint $table) {
            $table->boolean('category')->default(false)->after('any_taxes_withheld');

        });
    }

    public function down(): void
    {
        Schema::table('1601eq_forms', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
