<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit_slips', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->date('transaction_date');
            $table->string('bank_account')->nullable();
            $table->integer("deposit_type")->default(0);
            $table->string("particular")->nullable();
            $table->decimal('deposit_amount',10,2);
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
        Schema::dropIfExists('deposit_slips');
    }
}
