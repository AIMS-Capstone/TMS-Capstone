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
        Schema::table('tax_option_rate', function (Blueprint $table) {
            // Drop the foreign key and column for `tax_return_id`
            $table->dropForeign(['tax_return_id']);
            $table->dropColumn('tax_return_id');

            // Add new foreign key referencing `individual_background_information`
            $table->foreignId('individual_background_information_id')
                ->nullable() // Add nullable in case not all rows have an individual linked yet
                ->constrained('individual_background_information')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_option_rate', function (Blueprint $table) {
            // Drop the new foreign key and column for `individual_background_information_id`
            $table->dropForeign(['individual_background_information_id']);
            $table->dropColumn('individual_background_information_id');

            // Add back the `tax_return_id` column
            $table->foreignId('tax_return_id')
                ->constrained()
                ->onDelete('cascade');


        });
    }
};
