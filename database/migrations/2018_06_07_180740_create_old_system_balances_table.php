<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOldSystemBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_system_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->decimal('balance',10,2);
            $table->string('remarks');
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
        Schema::dropIfExists('old_system_balances');
    }
}
