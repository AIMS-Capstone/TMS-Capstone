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
        Schema::create('spouse_tax_option_rate', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spouse_information_id')
                ->constrained('spouse_information')
                ->onDelete('cascade'); // Links to Spouse Information
            $table->enum('rate_type', ['graduated_rates', '8_percent']); // Tax rate types
            $table->enum('deduction_method', ['itemized', 'osd'])->nullable(); // Only for Graduated Rates
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spouse_tax_option_rate');
    }
};
