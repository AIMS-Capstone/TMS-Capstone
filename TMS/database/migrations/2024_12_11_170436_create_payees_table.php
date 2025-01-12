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
        Schema::create('payees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('withholding_id');
            $table->unsignedBigInteger('atc_id');
            $table->unsignedBigInteger('contact_id');
            $table->decimal('amount', 15, 2); // Added amount field
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('org_setups')->onDelete('cascade');
            $table->foreign('withholding_id')->references('id')->on('withholdings')->onDelete('cascade');
            $table->foreign('atc_id')->references('id')->on('atcs')->onDelete('cascade');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payees');
    }
};
