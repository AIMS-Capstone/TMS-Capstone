<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTax2550qTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('2550q', function (Blueprint $table) {
            $table->id();
            $table->string('period');
            $table->string('year_ended');
            $table->enum('quarter', ['1st', '2md', '3rd', '4th']); // Quarter 1, 2, 3, or 4
            $table->date('return_from');
            $table->date('return_to');
            $table->enum('amended_return', ['yes', 'no']);
            $table->enum('short_period_return', ['yes', 'no']);
            $table->string('tin');
            $table->string('rdo_code');
            $table->string('taxpayer_name');
            $table->string('registered_address');
            $table->string('zip_code');
            $table->string('contact_number');
            $table->string('email_address');
            $table->enum('taxpayer_classification', ['Micro', 'Small', 'Medium', 'Large']);
            $table->enum('tax_relief', ['yes', 'no']);
            $table->string('yes_specify')->nullable();
            $table->decimal('vatable_sales', 15, 2);
            $table->decimal('vatable_sales_tax_amount', 15, 2);
            $table->decimal('zero_rated_sales', 15, 2);
            $table->decimal('exempt_sales', 15, 2);
            $table->decimal('total_sales', 15, 2);
            $table->decimal('total_output_tax', 15, 2);
            $table->decimal('uncollected_receivable_vat', 15, 2);
            $table->decimal('recovered_uncollected_receivables', 15, 2);
            $table->decimal('total_adjusted_output_tax', 15, 2);
            $table->decimal('input_carried_over', 15, 2);
            $table->decimal('input_tax_deferred', 15, 2);
            $table->decimal('transitional_input_tax', 15, 2);
            $table->decimal('presumptive_input_tax', 15, 2);
            $table->string('other_specify')->nullable();
            $table->decimal('other_input_tax', 15, 2);
            $table->decimal('total_input_tax', 15, 2);
            $table->decimal('domestic_purchase', 15, 2);
            $table->decimal('domestic_purchase_input_tax', 15, 2);
            $table->decimal('services_non_resident', 15, 2);
            $table->decimal('services_non_resident_input_tax', 15, 2);
            $table->decimal('importations', 15, 2);
            $table->decimal('importations_input_tax', 15, 2);
            $table->string('purchases_others_specify')->nullable();
            $table->decimal('purchases_others_specify_amount', 15, 2);
            $table->decimal('purchases_others_specify_input_tax', 15, 2);
            $table->decimal('domestic_no_input', 15, 2);
            $table->decimal('tax_exempt_importation', 15, 2);
            $table->decimal('total_current_purchase', 15, 2);
            $table->decimal('total_current_purchase_input_tax', 15, 2);
            $table->decimal('total_available_input_tax', 15, 2);
            $table->decimal('importation_million_deferred_input_tax', 15, 2);
            $table->decimal('attributable_vat_exempt_input_tax', 15, 2);
            $table->decimal('vat_refund_input_tax', 15, 2);
            $table->decimal('unpaid_payables_input_tax', 15, 2);
            $table->string('other_deduction_specify')->nullable();
            $table->decimal('other_deduction_specify_input_tax', 15, 2);
            $table->decimal('total_deductions_input_tax', 15, 2);
            $table->decimal('settled_unpaid_input_tax', 15, 2);
            $table->decimal('adjusted_deductions_input_tax', 15, 2);
            $table->decimal('total_allowable_input_Tax', 15, 2);
            $table->decimal('excess_input_tax', 15, 2);
            $table->decimal('creditable_vat_withheld', 15, 2);
            $table->decimal('advance_vat_payment', 15, 2);
            $table->decimal('vat_paid_if_amended', 15, 2);
            $table->string('other_credits_specify')->nullable();
            $table->decimal('other_credits_specify_amount', 15, 2);
            $table->decimal('total_tax_credits', 15, 2);
            $table->decimal('tax_still_payable', 15, 2);
            $table->decimal('surcharge', 15, 2);
            $table->decimal('interest', 15, 2);
            $table->decimal('compromise', 15, 2);
            $table->decimal('total_penalties', 15, 2);
            $table->decimal('total_amount_payable', 15, 2);
            $table->foreignId('tax_return_id')->constrained('tax_returns')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('2550q');
    }
}
