<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('0619E_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('atc_id')->nullable()->after('tax_code');
            $table->foreign('atc_id')->references('id')->on('atcs')->onDelete('set null');
            $table->string('category')->default('Private');
        });
    }

    public function down(): void
    {
        Schema::table('0619E_forms', function (Blueprint $table) {
            $table->dropForeign(['atc_id']);
            $table->dropColumn('atc_id');
            $table->dropColumn('category');
        });
    }
};
