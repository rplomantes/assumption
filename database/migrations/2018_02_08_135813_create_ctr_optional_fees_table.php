<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrOptionalFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_optional_fees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level');
            $table->string('schoolyear');
            $table->string('category');
            $table->string('subsidiary');
            $table->string('receipt_details');
            $table->string('accounting_code');
            $table->string('category_switch');
            $table->integer('default_qty')->default(1);
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
        Schema::dropIfExists('ctr_optional_fees');
    }
}
