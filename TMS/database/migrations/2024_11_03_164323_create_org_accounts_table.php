<?php

use Illuminate\Database\Eloquent\SoftDeletes;
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
        Schema::create('org_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_setup_id')->constrained('org_setups')->onDelete('cascade'); // Link to organization
            $table->string('email')->unique();
            $table->string('password');
            $table->softDeletes();
            $table->timestamps();
        });

        if (!Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_accounts');
        Schema::dropIfExists('password_resets');
    }
};
