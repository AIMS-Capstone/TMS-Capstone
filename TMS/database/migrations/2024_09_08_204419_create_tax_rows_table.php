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
        Schema::create('tax_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->foreignId('tax_code')->constrained('atcs')->nullable();
            $table->foreignId('tax_type')->constrained('tax_types')->nullable();
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('net_amount', 10, 2);
            $table->string('coa')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_rows');
    }
};
