<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentInfoSchoolRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_info_school_ranks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->integer('academic_excellence')->nullable();
            $table->integer('family')->nullable();
            $table->integer('friend')->nullable();
            $table->integer('ac_student')->nullable();
            $table->integer('womens_college')->nullable();
            $table->integer('security')->nullable();
            $table->integer('assumption_career')->nullable();
            $table->integer('newspaper')->nullable();
            $table->integer('values_formation')->nullable();
            $table->integer('college_fair')->nullable();
            $table->integer('parents_choice')->nullable();
            $table->integer('career_opportunities')->nullable();
            $table->integer('flyer')->nullable();
            $table->integer('hs_counselor')->nullable();
            $table->integer('courses')->nullable();
            $table->integer('ac_graduate')->nullable();
            $table->integer('location')->nullable();
            $table->integer('prestige')->nullable();
            $table->string('others')->nullable();
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
        Schema::dropIfExists('student_info_school_ranks');
    }
}
