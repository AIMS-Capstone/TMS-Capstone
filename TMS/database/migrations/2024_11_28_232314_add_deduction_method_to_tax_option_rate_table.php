<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeductionMethodToTaxOptionRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_option_rate', function (Blueprint $table) {
            // Add the deduction_method column (string or enum type)
            $table->string('deduction_method')->nullable()->after('rate_type');  // Add after 'rate_type' or adjust if needed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_option_rate', function (Blueprint $table) {
            // Drop the deduction_method column
            $table->dropColumn('deduction_method');
        });
    }
}
