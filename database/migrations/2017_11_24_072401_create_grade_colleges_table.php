<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeCollegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_colleges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->integer('course_offering_id')->nullable();
            $table->string('course_code');
            $table->string('course_name');
            $table->string('level');
            $table->integer('lec')->nullable();
            $table->integer('lab')->nullable();
            $table->decimal('hours',5,2)->nullable();
            $table->string('prelim')->nullable();
            $table->string('midterm')->nullable();
            $table->string('finals')->nullable();
            $table->string('final_grade')->nullable();
            $table->string('grade_point')->nullable();
            $table->string('completion')->nullable();
            $table->string('remarks')->nullable();
            $table->string('school_year');
            $table->string('period');
            $table->decimal('srf',10,2);
            $table->integer('percent_tuition');
            $table->integer('prelim_status')->default(0);
            $table->integer('midterm_status')->default(0);
            $table->integer('finals_status')->default(0);
            $table->integer('final_grade_status')->default(0);
            $table->integer('grade_point_status')->default(0);
            $table->integer('is_lock')->default(0);
            $table->integer('is_dropped')->default(0);
            $table->integer('is_advising')->default(1);
            $table->timestamps();
             $table->foreign('idno')
                    ->references('idno')->on('users')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grade_colleges');
    }
}
