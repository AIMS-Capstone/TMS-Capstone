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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
          
            $table->string('contact_type');
            $table->string('bus_name');
            $table->string('contact_email')->nullable(); // Email can be null
            $table->string('contact_phone')->nullable(); // Phone can be null
            $table->string('contact_tin');
          
            
            // Revenue Information
            $table->string('revenue_tax_type')->nullable(); // Tax Type can be null
            $table->string('revenue_atc')->nullable(); // ATC can be null
            $table->string('revenue_chart_accounts')->nullable(); // Chart of Accounts can be null
        
            // Expense Information
            $table->string('expense_tax_type')->nullable(); // Tax Type can be null
            $table->string('expense_atc')->nullable(); // ATC can be null
            $table->string('expense_chart_accounts')->nullable(); // Chart of Accounts can be null
        
            // Address Information
            $table->string('contact_address')->nullable(); // Address can be null
            $table->string('contact_city');
            $table->string('contact_zip');
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
