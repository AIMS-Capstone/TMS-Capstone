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
        Schema::create('atcs', function (Blueprint $table) {
            $table->id();
            $table->string('tax_code')->unique();
            $table->enum('transaction_type',['sales','purchase']);
            $table->string('category');
            $table->string('coverage');
            $table->string('description');
            $table->string('type')->nullable();
            $table->decimal('tax_rate', 5, 2); // Assuming tax_rate is a decimal value with 2 decimal places
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atcs');
    }
};
