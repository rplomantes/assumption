<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accountings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_id');
            $table->integer('reference_number')->nullable();
            $table->date('transaction_date');
            $table->integer('accounting_type');
            $table->string('category')->nullable();
            $table->string('subsidiary')->nullable();
            $table->string('receipt_details')->nullable();
            $table->string('fiscal_year');
            $table->string('particular')->nullable();
            $table->string('accounting_code');
            $table->string('accounting_name');
            $table->string('department')->default("None");
            $table->decimal('debit',10,2)->default(0.00);
            $table->decimal('credit',10,2)->default(0.00);
            $table->integer('is_reverse')->default(0);
            $table->string('posted_by');
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
        Schema::dropIfExists('accountings');
    }
}
