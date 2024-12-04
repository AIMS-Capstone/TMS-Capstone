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
        Schema::table('spouse_information', function (Blueprint $table) {
            // Adding the new columns for spouse's additional information
            $table->string('alphanumeric_tax_code')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('foreign_tax_number')->nullable();
            $table->boolean('claiming_foreign_credits')->nullable(); // Storing the claim as true/false
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spouse_information', function (Blueprint $table) {
            // Removing the added columns in case of rollback
            $table->dropColumn([
                'alphanumeric_tax_code',
                'citizenship',
                'foreign_tax_number',
                'claiming_foreign_credits',
            ]);
        });
    }
};
