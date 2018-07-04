<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferenceIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reference_ids', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->integer('registration_no')->default(1);
            $table->integer('petty_cash')->default(1);
            $table->integer('voucher_no')->default(1);
            $table->integer('start_receipt_no')->default(1);
            $table->integer('receipt_no')->default(1);
            $table->integer('end_receipt_no')->default(1);
            $table->integer('dm_no')->default(1);
            $table->foreign('idno')->references('idno')
                    ->on('users')
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
        Schema::dropIfExists('reference_ids');
    }
}
