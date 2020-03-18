<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disbursements', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transaction_date');
            $table->string('reference_id');
            $table->string('voucher_no');
            $table->string('payee_name');
            $table->decimal('amount',10,2);
            $table->string('bank');
            $table->string('check_no');
            $table->string('remarks');
            $table->string('fiscal_year');
            $table->integer('type')->default(0);
            $table->string('processed_by');
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
        Schema::dropIfExists('disbursements');
    }
}
