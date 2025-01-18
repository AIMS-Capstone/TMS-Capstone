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
        Schema::create('1601eq_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_setup_id');
            $table->unsignedBigInteger('withholding_id');
            $table->integer('year');
            $table->integer('quarter');
            $table->boolean('amended_return')->default(false);
            $table->boolean('any_taxes_withheld')->default(false);

            // Tax computation fields
            $table->decimal('total_taxes_withheld', 15, 2)->default(0);
            $table->decimal('remittances_1st_month', 15, 2)->nullable();
            $table->decimal('remittances_2nd_month', 15, 2)->nullable();
            $table->decimal('remitted_previous', 15, 2)->nullable();
            $table->decimal('over_remittance', 15, 2)->default(0);
            $table->decimal('other_payments', 15, 2)->nullable();
            $table->decimal('total_remittances_made', 15, 2)->default(0);
            $table->decimal('tax_still_due', 15, 2)->default(0);

            // Penalty fields
            $table->decimal('surcharge', 15, 2)->default(0);
            $table->decimal('interest', 15, 2)->default(0);
            $table->decimal('compromise', 15, 2)->default(0);
            $table->decimal('penalties', 15, 2)->default(0);

            // Total amount due
            $table->decimal('total_amount_due', 15, 2)->default(0);

            // Timestamps
            $table->timestamps();

            // Foreign keys
            $table->foreign('org_setup_id')->references('id')->on('org_setups')->onDelete('cascade');
            $table->foreign('withholding_id')->references('id')->on('withholdings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('1601EQ_forms');
    }
};
