<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithholdingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withholdings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Monthly Remittance Return of Income Taxes Withheld on Compensation');
            $table->unsignedBigInteger('organization_id'); // Foreign key for Organization
            $table->string('type', 10); // Example: 1601C, etc.
            $table->unsignedTinyInteger('month')->nullable();
            $table->unsignedTinyInteger('quarter')->nullable();
            $table->unsignedSmallInteger('year');
            $table->enum('status', ['Filed', 'Unfiled'])->default('Unfiled'); 
            $table->timestamps();
            $table->softDeletes(); // For soft delete
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('organization_id')->references('id')->on('org_setups')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['organization_id', 'type', 'year', 'month', 'quarter']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withholdings');
    }
}
