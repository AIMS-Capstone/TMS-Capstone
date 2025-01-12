<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForm1604cTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form1604c', function (Blueprint $table) {
            $table->id();
            // Foreign key for Withholding table
            $table->foreignId('withholding_id')->constrained()->onDelete('cascade');

            // Foreign key for OrgSetup table
            $table->foreignId('org_setup_id')->constrained()->onDelete('cascade');
            
            $table->string('year');
            $table->boolean('amended_return');
            $table->integer('number_of_sheets');
            $table->string('agent_category');
            $table->string('agent_top')->nullable(); // Only for Private agents

            // New fields you mentioned
            $table->boolean('over_remittances');
            $table->date('refund_date')->nullable();
            $table->decimal('total_over_remittances', 15, 2)->default(0);
            $table->date('first_month_remittances')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form1604c');
    }
}
