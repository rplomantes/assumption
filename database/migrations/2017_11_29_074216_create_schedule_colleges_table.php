<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleCollegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_colleges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_offering_id');
            $table->string('schedule_id');
            $table->string('course_code');
            $table->string('school_year');
            $table->string('period');
            $table->string('room');
            $table->string('day');
            $table->time('time_start');
            $table->time('time_end');
            $table->string('instructor_id')->nullable();
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
        Schema::dropIfExists('schedule_colleges');
    }
}
