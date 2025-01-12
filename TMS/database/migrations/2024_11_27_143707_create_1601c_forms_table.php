<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create1601CFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('1601C_forms', function (Blueprint $table) {
            $table->id();

            // Filing period
            $table->date('filing_period'); // MM/YYYY

            // References
            $table->unsignedBigInteger('org_setup_id'); // Link to OrgSetup for organization details
            $table->unsignedBigInteger('atc_id'); // Link to ATC for alphanumeric tax code
            $table->unsignedBigInteger('withholding_id'); // Link to WithHolding

            // Tax details for 1601C
            $table->boolean('amended_return')->default(false);
            $table->boolean('any_taxes_withheld')->default(false);
            $table->unsignedInteger('number_of_sheets')->default(0);
            $table->decimal('total_compensation', 15, 2)->nullable(); // Computed dynamically
            $table->decimal('taxable_compensation', 15, 2)->nullable(); // Computed dynamically
            $table->decimal('tax_due', 15, 2)->nullable(); // Computed dynamically
            $table->decimal('other_remittances', 15, 2)->nullable();

            // Adjustment and penalties
            $table->decimal('adjustment', 15, 2)->nullable();
            $table->decimal('surcharge', 15, 2)->nullable();
            $table->decimal('interest', 15, 2)->nullable();
            $table->decimal('compromise', 15, 2)->nullable();
            $table->decimal('total_amount_due', 15, 2)->nullable();

            // Fields for 0619E
            $table->decimal('amount_of_remittance', 15, 2)->default(0);
            $table->decimal('remitted_previous', 15, 2)->default(0);
            $table->decimal('penalties', 15, 2)->default(0);
            $table->date('due_date')->nullable();

            // Timestamps
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('org_setup_id')->references('id')->on('org_setups')->onDelete('cascade');
            $table->foreign('atc_id')->references('id')->on('atcs')->onDelete('cascade');
            $table->foreign('withholding_id')->references('id')->on('withholdings')->onDelete('cascade');

            // Indexes for optimization
            $table->index('filing_period');
            $table->index('org_setup_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('1601C_forms');
    }
}
