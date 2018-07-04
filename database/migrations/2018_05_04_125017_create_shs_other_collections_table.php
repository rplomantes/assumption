<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShsOtherCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shs_other_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category');
            $table->string('subsidiary');
            $table->string('receipt_details');
            $table->integer('category_switch');
            $table->string('accounting_code');
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
        Schema::dropIfExists('shs_other_collections');
    }
}
