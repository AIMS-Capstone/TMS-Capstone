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
        Schema::create('2551q', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('tax_return_id')->constrained()->cascadeOnDelete(); // Foreign key to tax_returns table
            
            // Fields based on your validated data
            $table->string('period'); // The period for the tax return
            $table->string('year_ended'); // The year ended for the tax return
            $table->string('quarter'); // The quarter of the tax return (Q1, Q2, Q3, Q4)
            $table->enum('amended_return', ['yes','no']); // Whether it's an amended return
            $table->integer('sheets_attached')->nullable(); // Sheets attached count
            $table->string('tin'); // Taxpayer Identification Number
            $table->string('rdo_code'); // Revenue District Office code
            $table->string('taxpayer_name'); // Taxpayer name
            $table->string('registered_address'); // Registered address
            $table->string('zip_code'); // Zip code for the address
            $table->string('contact_number'); // Contact number
            $table->string('email_address'); // Email address
            $table->string('tax_relief'); // Type of tax relief
            $table->string('yes_specify')->nullable(); // If there's a specification for the relief
            $table->string('availed_tax_rate'); // The availed tax rate
            $table->decimal('tax_due', 15, 2)->nullable(); // Tax due amount
            $table->decimal('creditable_tax', 15, 2)->nullable(); // Creditable tax amount
            $table->decimal('amended_tax', 15, 2)->nullable(); // Amended tax amount
            $table->string('other_tax_specify')->nullable(); // Specify other tax
            $table->decimal('other_tax_amount', 15, 2)->nullable(); // Other tax amount
            $table->decimal('total_tax_credits', 15, 2)->nullable(); // Total tax credits
            $table->decimal('tax_still_payable', 15, 2)->nullable(); // Tax still payable
            $table->decimal('surcharge', 15, 2)->nullable(); // Surcharge amount
            $table->decimal('interest', 15, 2)->nullable(); // Interest amount
            $table->decimal('compromise', 15, 2)->nullable(); // Compromise amount (nullable)
            $table->decimal('total_penalties', 15, 2)->nullable(); // Total penalties (nullable)
            $table->decimal('total_amount_payable', 15, 2)->nullable(); // Total amount payable
    
            $table->timestamps(); // created_at and updated_at
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('2551q');
    }
};
