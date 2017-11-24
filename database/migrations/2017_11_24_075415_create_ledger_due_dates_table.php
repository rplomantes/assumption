<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgerDueDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger_due_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('school_year');
            $table->string('period');
            $table->integer('due_switch');
            $table->date('due_date');
            $table->decimal('amount',10,2);
            $table->foreign('idno')
                    ->references('idno')->on('users')
                    ->onUpdate('cascade');
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
        Schema::dropIfExists('ledger_due_dates');
    }
}
