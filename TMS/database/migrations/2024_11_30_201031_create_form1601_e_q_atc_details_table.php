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
        Schema::create('1601EQ_atc_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_1601eq_id');
            $table->unsignedBigInteger('atc_id');
            $table->decimal('tax_base', 15, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0); // percentage
            $table->decimal('tax_withheld', 15, 2)->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('form_1601eq_id')->references('id')->on('1601EQ_forms')->onDelete('cascade');
            $table->foreign('atc_id')->references('id')->on('atcs')->onDelete('cascade');
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('1601EQ_atc_details');
    }
};
