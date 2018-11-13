<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBedExtraActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bed_extra_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('club')->nullable();
            $table->string('level')->nullable();
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
        Schema::dropIfExists('bed_extra_activities');
    }
}
