<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('withholding_id'); // Foreign key for WithHoldings
            $table->unsignedBigInteger('employee_id'); // Reference to Employee
            $table->unsignedBigInteger('employment_id')->nullable(); // Reference to Employment

            // Compensation details
            $table->date('payment_date'); // Date of payment
            $table->decimal('gross_compensation', 15, 2); // Gross Compensation
            $table->decimal('taxable_compensation', 15, 2)->default(0);// taxable compensation
            $table->decimal('tax_due', 15, 2)->default(0); // Tax Due

            // Non-taxable compensation details (nullable for AMWEs)
            $table->decimal('statutory_minimum_wage', 15, 2)->nullable()->default(0); // Statutory Minimum Wage
            $table->decimal('holiday_pay', 15, 2)->nullable()->default(0); // Holiday Pay
            $table->decimal('overtime_pay', 15, 2)->nullable()->default(0); // Overtime Pay
            $table->decimal('night_shift_differential', 15, 2)->nullable()->default(0); // Night Shift Differential Pay
            $table->decimal('hazard_pay', 15, 2)->nullable()->default(0); // Hazard Pay
            $table->decimal('month_13_pay', 15, 2)->nullable()->default(0); // 13th Month Pay
            $table->decimal('de_minimis_benefits', 15, 2)->nullable()->default(0); // De Minimis Benefits
            $table->decimal('sss_gsis_phic_hdmf_union_dues', 15, 2)->nullable()->default(0); // SSS, GSIS, PHIC, HDMF Contributions
            $table->decimal('other_non_taxable_compensation', 15, 2)->nullable()->default(0); // Other Non-Taxable Compensation

            $table->timestamps();

            // Define foreign keys
            $table->foreign('withholding_id')->references('id')->on('withholdings')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('employment_id')->references('id')->on('employments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sources');
    }
}
