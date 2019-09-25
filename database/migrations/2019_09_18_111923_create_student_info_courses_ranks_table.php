<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentInfoCoursesRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_info_courses_ranks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('rank_1')->nullable();
            $table->string('rank_2')->nullable();
            $table->string('rank_3')->nullable();
            $table->string('why_most_preferred')->nullable();
            $table->string('who_decided')->nullable();
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
        Schema::dropIfExists('student_info_courses_ranks');
    }
}
