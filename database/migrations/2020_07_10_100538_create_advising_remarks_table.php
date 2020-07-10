<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvisingRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advising_remarks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('school_year');
            $table->string('period');
            $table->string('remarks');
            $table->string('remarks_by');
            
            $table->foreign('idno')
                    ->references('idno')->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('advising_remarks');
    }
}
