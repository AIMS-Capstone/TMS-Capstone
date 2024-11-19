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
        Schema::create('2551q_schedule', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('2551q_id')->constrained('2551q')->cascadeOnDelete(); // Foreign key to 2551q table
            $table->string('atc_code'); // ATC code
            $table->decimal('tax_base', 15, 2); // Tax base for the ATC
            $table->decimal('tax_rate', 5, 2); // Tax rate for the ATC
            $table->decimal('tax_due', 15, 2); // Calculated tax due
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('2551q_schedule');
    }
};
