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
        Schema::create('form1604_e_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('withholding_id'); // Reference to WithHolding
            $table->unsignedBigInteger('org_setup_id'); // Reference to OrgSetup
            $table->string('year', 4); // Year of the form
            $table->boolean('amended_return')->default(false); // Whether the form is amended
            $table->integer('number_of_sheets')->nullable(); // Number of attached sheets
            $table->enum('agent_category', ['Government', 'Private']); // Category of the withholding agent
            $table->enum('agent_top', ['Yes', 'No'])->nullable(); // If private, top withholding agent

            $table->timestamps();

            // Foreign keys
            $table->foreign('withholding_id')->references('id')->on('withholdings')->onDelete('cascade');
            $table->foreign('org_setup_id')->references('id')->on('org_setups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form1604_e_s');
    }
};
