<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrBedSrvesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_bed_srves', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level');
            $table->string('strand');
            $table->string('category');
            $table->string('subsidiary');
            $table->string('receipt_details');
            $table->string('accounting_code');
            $table->integer('category_switch');
            $table->decimal('amount',10,2);
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
        Schema::dropIfExists('ctr_bed_srves');
    }
}
