<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('curriculum_year')->nullable();
            $table->string('program_code')->nullable();
            $table->string('program_name')->nullable();
            $table->string('birthdate')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality')->nullable();
            $table->string('province')->nullable();
            $table->string('zip')->nullable();
            $table->string('tel_no')->nullable();
            $table->string('cell_no')->nullable();
            
            $table->string('last_school_attended')->nullable();
            $table->string('last_school_address')->nullable();
            $table->string('last_school_year')->nullable();
            
            $table->string('immig_status')->nullable();
            $table->string('auth_stay')->nullable();
            $table->string('passport')->nullable();
            $table->string('passport_exp_date')->nullable();
            $table->string('passport_place_issued')->nullable();
            $table->string('acr_no')->nullable();
            $table->string('acr_date_issued')->nullable();
            $table->string('acr_place_issued')->nullable();
            
            $table->string('father')->nullable();
            $table->integer('f_is_living')->nullable();
            $table->string('f_occupation')->nullable();
            $table->string('f_phone')->nullable();
            $table->string('f_address')->nullable();
            $table->string('mother')->nullable();
            $table->integer('m_is_living')->nullable();
            $table->string('m_occupation')->nullable();
            $table->string('m_phone')->nullable();
            $table->string('m_address')->nullable();
            $table->string('spouse')->nullable();
            $table->integer('s_is_living')->nullable();
            $table->string('s_occupation')->nullable();
            $table->string('s_phone')->nullable();
            $table->string('s_address')->nullable();
            
            $table->string('primary')->nullable();
            $table->string('primary_address')->nullable();
            $table->string('primary_year')->nullable();
            
            $table->string('gradeschool')->nullable();
            $table->string('gradeschool_address')->nullable();
            $table->string('gradeschool_year')->nullable();
            
            $table->string('highschool')->nullable();
            $table->string('highschool_address')->nullable();
            $table->string('highschool_year')->nullable();
            
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
        Schema::dropIfExists('student_infos');
    }
}
