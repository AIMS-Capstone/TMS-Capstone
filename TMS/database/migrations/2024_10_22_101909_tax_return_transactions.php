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
        Schema::create('tax_return_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_return_id')->constrained('tax_returns')->onDelete('cascade'); // Foreign key to tax_returns table
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade'); // Foreign key to transactions table
            $table->decimal('allocation_percentage', 5, 2)->nullable(); // Optional field for partial allocations
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
