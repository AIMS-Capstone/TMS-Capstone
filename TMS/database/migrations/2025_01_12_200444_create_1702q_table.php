<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('1702q', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_return_id')->constrained('tax_returns');
            
            // Basic Information
            $table->string('period');
            $table->string('year_ended');
            $table->string('quarter');
            $table->string('amended_return');
            $table->string('alphanumeric_tax_code');
            $table->string('tin');
            $table->string('rdo_code');
            $table->string('taxpayer_name');
            $table->text('registered_address');
            $table->string('zip_code');
            $table->string('contact_number');
            $table->string('email_address');
            $table->string('tax_relief');
            $table->string('yes_specify')->nullable();

            // Tax calculations
            $table->decimal('show_income_tax_due_regular', 15, 2)->nullable();
            $table->decimal('unexpired_excess_mcit', 15, 2)->nullable();
            $table->decimal('balance_tax_due_regular', 15, 2)->nullable();
            $table->decimal('show_income_tax_due_special', 15, 2)->nullable();
            $table->decimal('aggregate_tax_due', 15, 2)->nullable();
            $table->decimal('show_total_tax_credits', 15, 2)->nullable();
            $table->decimal('net_tax_payable', 15, 2)->nullable();

            // Penalties
            $table->decimal('surcharge', 15, 2)->nullable();
            $table->decimal('interest', 15, 2)->nullable();
            $table->decimal('compromise', 15, 2)->nullable();
            $table->decimal('total_penalties', 15, 2)->nullable();
            $table->decimal('total_amount_payable', 15, 2)->nullable();

            // Special rates
            $table->decimal('sales_receipts_special', 15, 2)->nullable();
            $table->decimal('cost_of_sales_special', 15, 2)->nullable();
            $table->decimal('gross_income_special', 15, 2)->nullable();
            $table->decimal('other_taxable_income_special', 15, 2)->nullable();
            $table->decimal('total_gross_income_special', 15, 2)->nullable();
            $table->decimal('deductions_special', 15, 2)->nullable();
            $table->decimal('taxable_income_quarter_special', 15, 2)->nullable();
            $table->decimal('prev_quarter_income_special', 15, 2)->nullable();
            $table->decimal('total_taxable_income_special', 15, 2)->nullable();
            $table->decimal('tax_rate_special', 15, 2)->nullable();
            $table->decimal('income_tax_due_special', 15, 2)->nullable();
            $table->decimal('other_agencies_share_special', 15, 2)->nullable();
            $table->decimal('net_tax_due_special', 15, 2)->nullable();

            // Regular rates
            $table->decimal('sales_receipts_regular', 15, 2)->nullable();
            $table->decimal('cost_of_sales_regular', 15, 2)->nullable();
            $table->decimal('gross_income_operation_regular', 15, 2)->nullable();
            $table->decimal('non_operating_income_regular', 15, 2)->nullable();
            $table->decimal('total_gross_income_regular', 15, 2)->nullable();
            $table->decimal('deductions_regular', 15, 2)->nullable();
            $table->decimal('taxable_income_quarter_regular', 15, 2)->nullable();
            $table->decimal('taxable_income_previous_regular', 15, 2)->nullable();
            $table->decimal('total_taxable_income_regular', 15, 2)->nullable();
            $table->decimal('income_tax_rate_regular', 15, 2)->nullable();
            $table->decimal('income_tax_due_regular', 15, 2)->nullable();
            $table->decimal('mcit_regular', 15, 2)->nullable();
            $table->decimal('final_income_tax_due_regular', 15, 2)->nullable();

            // MCIT
            $table->decimal('gross_income_first_quarter_mcit', 15, 2)->nullable();
            $table->decimal('gross_income_second_quarter_mcit', 15, 2)->nullable();
            $table->decimal('gross_income_third_quarter_mcit', 15, 2)->nullable();
            $table->decimal('total_gross_income_mcit', 15, 2)->nullable();
            $table->decimal('mcit_rate', 15, 2)->nullable();
            $table->decimal('minimum_corporate_income_tax_mcit', 15, 2)->nullable();

            // Tax credits
            $table->decimal('prior_year_excess_credits', 15, 2)->nullable();
            $table->decimal('previous_quarters_tax_payments', 15, 2)->nullable();
            $table->decimal('previous_quarters_mcit_payments', 15, 2)->nullable();
            $table->decimal('previous_quarters_creditable_tax', 15, 2)->nullable();
            $table->decimal('current_quarter_creditable_tax', 15, 2)->nullable();
            $table->decimal('previously_filed_tax_payment', 15, 2)->nullable();

            // Other tax credits
            $table->string('other_tax_specify')->nullable();
            $table->decimal('other_tax_amount', 15, 2)->nullable();
            $table->string('other_tax_specify2')->nullable();
            $table->decimal('other_tax_amount2', 15, 2)->nullable();
            $table->decimal('total_tax_credits', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('1702q');
    }
};