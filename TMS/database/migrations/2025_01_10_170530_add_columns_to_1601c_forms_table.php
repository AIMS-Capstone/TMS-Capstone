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
        Schema::table('1601c_forms', function (Blueprint $table) {
            $table->string('agent_category')->nullable()->after('compromise');
            $table->boolean('tax_relief')->default(0)->after('agent_category');
            $table->decimal('adjustment_taxes_withheld', 15, 2)->nullable()->after('tax_relief');
            $table->decimal('tax_remitted_return', 15, 2)->nullable()->after('adjustment_taxes_withheld');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('1601c_forms', function (Blueprint $table) {
            $table->dropColumn('agent_category');
            $table->dropColumn('tax_relief');
            $table->dropColumn('adjustment_taxes_withheld');
            $table->dropColumn('tax_remitted_return');
        });
    }
};
