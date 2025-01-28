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
        Schema::table('org_setups', function (Blueprint $table) {
            $table->foreignId('accountant_id')
                  ->nullable()
                  ->constrained('users')  // Assuming accountants are users
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('org_setups', function (Blueprint $table) {
            $table->dropForeignKey(['accountant_id']);
            $table->dropColumn('accountant_id');
        });
    }
};