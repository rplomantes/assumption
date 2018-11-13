<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountingPasscodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_passcodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('generated_by_idno');
            $table->datetime('datetime_generated');
            $table->string('passcode');
            $table->integer('is_used')->default(0);
            $table->string('used_by')->nullable();
            $table->datetime('datetime_used')->nullable();
            $table->foreign('generated_by_idno')
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
        Schema::dropIfExists('accounting_passcodes');
    }
}
