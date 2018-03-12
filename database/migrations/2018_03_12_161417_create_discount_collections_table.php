<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('subsidiary');
            $table->decimal('discount_amount',10,2);
            $table->timestamps();
            $table->foreign('idno')->references('idno')
                    ->on('users')->onUpdate('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_collections');
    }
}
