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
        Schema::table('contacts', function (Blueprint $table) {
            // Add foreign key for organization_id
            $table->unsignedBigInteger('organization_id')->nullable()->after('contact_zip');
            $table->foreign('organization_id')->references('id')->on('org_setups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['organization_id']); // Drop foreign key
            $table->dropColumn('organization_id'); // Drop column
        });
    }
};
