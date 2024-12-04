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
        Schema::create('individual_background_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_return_id')->constrained('tax_returns')->onDelete('cascade'); // Links to the Tax Returns table
            $table->date('date_of_birth')->nullable();
            $table->enum('filer_type', ['single_proprietor', 'professional', 'estate', 'trust']);
            $table->string('alphanumeric_tax_code')->nullable();
            $table->enum('civil_status', ['single', 'married']);
            $table->string('citizenship')->nullable(); 
            $table->string('foreign_tax')->nullable(); 
            $table->boolean('claiming_foreign_credits')->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_background_information');
    }
};
