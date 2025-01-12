<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('0619E_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_setup_id');
            $table->unsignedBigInteger('withholding_id');
            $table->string('for_month'); // YYYY-MM format for monthly reporting
            $table->date('due_date');
            $table->boolean('amended_return')->default(false);
            $table->string('tax_code');
            $table->boolean('any_taxes_withheld')->default(false);

            // Tax remittance fields
            $table->decimal('amount_of_remittance', 15, 2)->default(0);
            $table->decimal('remitted_previous', 15, 2)->default(0);
            $table->decimal('net_amount_of_remittance', 15, 2)->default(0);

            // Penalty fields
            $table->decimal('surcharge', 15, 2)->default(0);
            $table->decimal('interest', 15, 2)->default(0);
            $table->decimal('compromise', 15, 2)->default(0);
            $table->decimal('total_penalties', 15, 2)->default(0);

            // Total amount due
            $table->decimal('total_amount_due', 15, 2)->default(0);

            // Timestamps
            $table->timestamps();

            // Foreign keys
            $table->foreign('org_setup_id')->references('id')->on('org_setups')->onDelete('cascade');
            $table->foreign('withholding_id')->references('id')->on('withholdings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('0619E_forms');
    }
};
