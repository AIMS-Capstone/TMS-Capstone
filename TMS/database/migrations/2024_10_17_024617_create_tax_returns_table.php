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
        Schema::create('tax_returns', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // To store the title of the tax return
            $table->unsignedInteger('year'); // To store the tax year
            $table->string('month'); // To store the month (1-12)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Foreign key for the user who created the tax return
            $table->foreignId('organization_id')->constrained('org_setups')->onDelete('cascade');
            $table->softDeletes();
            $table->string('status'); // To store the status of the tax return (e.g., Draft, Submitted, Completed)
            $table->timestamps(); // Created at and updated at timestamps

            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_returns');
    }
};
