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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_type'); // e.g., "Sales", "Purchase", etc.
            $table->date('date');
            $table->string('inv_number')->nullable();
            $table->boolean('itr_include')->nullable();
            $table->foreignId('contact')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('organization_id')->constrained('org_setups')->onDelete('cascade');
            $table->string('reference')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->decimal('vat_amount', 10, 2)->nullable();
            $table->decimal('vatable_sales', 10, 2)->nullable();
            $table->decimal('vatable_purchase', 10, 2)->nullable();
            $table->decimal('non_vatable_sales', 10, 2)->nullable();
            $table->decimal('non_vatable_purchase', 10, 2)->nullable();
            $table->decimal('total_amount_credit', 10, 2)->nullable();
            $table->decimal('total_amount_debit', 10, 2)->nullable();
            $table->enum('status', ['draft', 'posted'])->default('draft');
            $table->enum('Paidstatus', ['Paid', 'Unpaid'])->default('Unpaid');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
