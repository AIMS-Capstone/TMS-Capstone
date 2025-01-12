<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTax1701qTable extends Migration
{
    public function up()
    {
        Schema::create('tax_1701q', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID for the tax_1701q table
            $table->foreignId('tax_return_id')->constrained('tax_returns')->onDelete('cascade'); // Foreign key to tax_returns table
            
            // Personal Information
            $table->string('for_the_year')->nullable();
            $table->string('quarter')->nullable();
            $table->string('amended_return')->nullable();
            $table->string('sheets')->nullable();
            $table->string('tin')->nullable();
            $table->string('rdo_code')->nullable();
            $table->string('filer_type')->nullable();
            $table->string('alphanumeric_tax_code')->nullable();
            $table->string('taxpayer_name')->nullable();
            $table->string('registered_address')->nullable();
            $table->string('zip_code')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('email_address')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('foreign_tax')->nullable();
            $table->boolean('claiming_foreign_credits')->nullable();
            $table->string('individual_rate_type')->nullable();

      

            // Tax Calculations
            $table->decimal('show_tax_due', 15, 2)->nullable();
            $table->decimal('show_tax_credits_payments', 15, 2)->nullable();
            $table->decimal('show_tax_payable', 15, 2)->nullable();
            $table->decimal('show_total_penalties', 15, 2)->nullable();
            $table->decimal('show_total_amount_payable', 15, 2)->nullable();
            $table->decimal('aggregate_amount_payable', 15, 2)->nullable();

            // Sales and Revenues
            $table->decimal('sales_revenues', 15, 2)->nullable();
            $table->decimal('cost_of_sales', 15, 2)->nullable();
            $table->decimal('gross_income', 15, 2)->nullable();
            $table->decimal('total_itemized_deductions', 15, 2)->nullable();
            $table->decimal('osd', 15, 2)->nullable();
            $table->decimal('net_income', 15, 2)->nullable();
            $table->decimal('taxable_income', 15, 2)->nullable();
            // Fields ending with _8
            $table->decimal('sales_revenues_8', 15, 2)->nullable();
            $table->decimal('non_op_specify_8', 15, 2)->nullable();
            $table->decimal('non_operating_8', 15, 2)->nullable();
            $table->decimal('total_income_8', 15, 2)->nullable();
            $table->decimal('total_prev_8', 15, 2)->nullable();
            $table->decimal('cumulative_taxable_income_8', 15, 2)->nullable();
            $table->decimal('allowable_reduction_8', 15, 2)->nullable();
            $table->decimal('taxable_income_8', 15, 2)->nullable();
            $table->decimal('tax_due_8', 15, 2)->nullable();
            $table->decimal('prior_year_credits', 15, 2)->nullable();
            $table->decimal('tax_payments_prev_quarters', 15, 2)->nullable();
            $table->decimal('creditable_tax_withheld_prev_quarters', 15, 2)->nullable();
            $table->decimal('creditable_tax_withheld_bir', 15, 2)->nullable();
            $table->decimal('tax_paid_prev_return', 15, 2)->nullable();
            $table->decimal('foreign_tax_credits', 15, 2)->nullable();
            $table->decimal('other_tax_credits', 15, 2)->nullable();
            $table->decimal('total_tax_credits', 15, 2)->nullable();
            $table->decimal('tax_payable', 15, 2)->nullable();
            $table->decimal('surcharge', 15, 2)->nullable();
            $table->decimal('interest', 15, 2)->nullable();
            $table->decimal('compromise', 15, 2)->nullable();
            $table->decimal('total_penalties', 15, 2)->nullable();

            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('tax_1701q');
    }
}
