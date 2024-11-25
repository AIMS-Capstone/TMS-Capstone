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
            $table->foreignId('tax_return_id')->constrained()->onDelete('cascade');
            $table->date('date_of_birth');
            $table->enum('filer_type', ['single_proprietor', 'professional', 'estate', 'trust']);
            $table->string('tax_code');
            $table->enum('civil_status', ['single', 'married']);
            $table->text('other_information')->nullable();
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
