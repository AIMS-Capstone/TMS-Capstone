<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('org_account_sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('org_account_id')->constrained('org_accounts')->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity');
        });
    }

    public function down()
    {
        Schema::dropIfExists('org_account_sessions');
    }

};
