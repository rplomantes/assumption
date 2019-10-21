<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjustmentLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustment_ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('subsidiary')->nullable();
            $table->string('level')->nullable();
            $table->string('amount_ledger')->nullable();
            $table->string('amount_to_be')->nullable();
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
        Schema::dropIfExists('adjustment_ledgers');
    }
}
