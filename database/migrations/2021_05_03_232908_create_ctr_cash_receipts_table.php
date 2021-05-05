<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrCashReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_cash_receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->string("accounting_code");
            $table->string("debit_or_credit");
            $table->integer("sort_no")->default(0);
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
        Schema::dropIfExists('ctr_cash_receipts');
    }
}
