<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvisingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advisings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('school_year');
            $table->string('period');
            $table->string('program_code');
            $table->string('course_code');
            $table->string('course_name');
            $table->string('course_level');
            $table->string('course_period');
            $table->integer('lec')->nullable();
            $table->integer('lab')->nullable();
            $table->decimal('hours',10,2)->nullable();
            $table->foreign('idno')->references('idno')
                    ->on('users')->onUpdate('cascade');
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
        Schema::dropIfExists('advisings');
    }
}
