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
        Schema::table('1601eq_atc_details', function (Blueprint $table) {
            // Add withholding_id column
            $table->unsignedBigInteger('withholding_id')->nullable()->after('id');

            // Drop the form_1601eq_id foreign key and column
            $table->dropForeign(['form_1601eq_id']);
            $table->dropColumn('form_1601eq_id');

            // Create new foreign key for withholding_id
            $table->foreign('withholding_id')->references('id')->on('withholdings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('1601EQ_atc_details', function (Blueprint $table) {
            // Re-add form_1601eq_id column
            $table->unsignedBigInteger('form_1601eq_id')->nullable()->after('id');

            // Drop withholding_id and its foreign key
            $table->dropForeign(['withholding_id']);
            $table->dropColumn('withholding_id');

            // Restore original form_1601eq_id foreign key
            $table->foreign('form_1601eq_id')->references('id')->on('1601EQ_forms')->onDelete('cascade');
        });
    }
};
