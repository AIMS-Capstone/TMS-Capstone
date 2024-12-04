<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('individual_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_return_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->decimal('allocation_percentage', 5, 2)->nullable(); // Optional
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('individual_transactions');
    }
}
