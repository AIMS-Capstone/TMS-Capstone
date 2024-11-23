<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentsTable extends Migration
{
    public function up()
    {
        Schema::create('employments', function (Blueprint $table) {
            $table->id();
            $table->string('employer_name');
            $table->date('employment_from');
            $table->date('employment_to')->nullable();
            $table->decimal('rate', 10, 2);
            $table->decimal('rate_per_month', 10, 2);
            $table->string('employment_status');
            $table->string('reason_for_separation')->nullable();
            $table->string('employee_wage_status');
            $table->boolean('substituted_filing')->default(false);
            $table->boolean('with_previous_employer')->default(false);
            $table->string('previous_employer_tin')->nullable();
            $table->string('prev_employment_from')->nullable();
            $table->string('prev_employment_to')->nullable();
            $table->string('prev_employment_status')->nullable();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employments');
    }
}
