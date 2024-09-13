<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Codec\TimestampLastCombCodec;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('org_setups', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['non-individual', 'individual']);
            $table->string('registration_name');
            $table->string('line_of_business');
            $table->string('address_line');  
            $table->string('region');  
            $table->string('city');    
            $table->string('zip_code'); 
            $table->string('contact_number');
            $table->string('email');
            $table->string('tin');
            $table->string('rdo');
            $table->string('tax_type');
            $table->date('registration_date');
            $table->date('start_date');
            $table->date('financial_year_end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_setups');
    }
};
