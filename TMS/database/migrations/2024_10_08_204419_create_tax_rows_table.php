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
            $table->string('description')->nullable();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->decimal('amount', 10, 2)->nullable();
            $table->foreignId('tax_code')->nullable()->constrained('atcs')->nullOnDelete(); // Make nullable with constraint
            $table->foreignId('tax_type')->nullable()->constrained('tax_types')->nullOnDelete(); // Make nullable with constraint
            $table->decimal('tax_amount', 10, 2)->nullable(); // Make nullable
            $table->decimal('net_amount', 10, 2)->nullable(); // Make nullable
            $table->decimal('atc_amount', 10, 2)->nullable(); // Make nullable
            $table->decimal('credit', 10, 2)->nullable();
            $table->decimal('debit', 10, 2)->nullable();
            $table->foreignId('coa')->nullable()->constrained('coas')->nullOnDelete(); // Make nullable with constraint
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
