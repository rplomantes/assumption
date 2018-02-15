<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrPeUniformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_pe_uniforms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category');
            $table->string('subsidiary');
            $table->string('receipt_details');
            $table->string('accounting_code');
            $table->string('category_switch');
            $table->string('size')->nullable();
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
        Schema::dropIfExists('ctr_pe_uniforms');
    }
}
