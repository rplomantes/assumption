<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBedProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bed_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality')->nullable();
            $table->string('province')->nullable();
            $table->string('zip')->nullable();
            $table->string('tel_no')->nullable();
            $table->string('cell_no')->nullable();
            
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            
            //for non filipino
            $table->string('immig_status')->nullable();
            $table->string('auth_stay')->nullable();
            $table->string('passport')->nullable();
            $table->string('passport_exp_date')->nullable();
            $table->string('passport_place_issued')->nullable();
            $table->string('acr_no')->nullable();
            $table->string('acr_date_issued')->nullable();
            $table->string('acr_place_issued')->nullable();
            
            //parent info
            $table->string('father')->nullable();
            $table->integer('f_citizenship')->nullable();
            $table->integer('f_is_living')->nullable();
            $table->integer('f_religion')->nullable();
            $table->integer('f_education')->nullable();
            $table->integer('f_school')->nullable();
            $table->string('f_occupation')->nullable();
            $table->integer('f_company_name')->nullable();
            $table->integer('f_company_address')->nullable();
            $table->integer('f_company_number')->nullable();
            $table->string('f_phone')->nullable();
            $table->integer('f_cell_no')->nullable();
            $table->string('f_email')->nullable();
            $table->integer('f_any_org')->nullable();
            $table->integer('f_type_of_org')->nullable();
            $table->integer('f_expertise')->nullable();
            
            $table->string('mother')->nullable();
            $table->integer('m_citizenship')->nullable();
            $table->integer('m_is_living')->nullable();
            $table->integer('m_religion')->nullable();
            $table->integer('m_education')->nullable();
            $table->integer('m_school')->nullable();
            $table->string('m_occupation')->nullable();
            $table->integer('m_company_name')->nullable();
            $table->integer('m_company_address')->nullable();
            $table->integer('m_company_number')->nullable();
            $table->string('m_phone')->nullable();
            $table->integer('m_cell_no')->nullable();
            $table->string('m_email')->nullable();
            $table->integer('m_any_org')->nullable();
            $table->integer('m_type_om_org')->nullable();
            $table->integer('m_expertise')->nullable();
            $table->integer('m_alumna_gradeschool_year')->nullable();
            $table->integer('m_alumna_highschool_year')->nullable();
            $table->integer('m_alumna_college_year')->nullable();
            
            $table->integer('parents_civil_status')->nullable();
            
            $table->string('guardian')->nullable();
            $table->integer('g_relation')->nullable();
            $table->integer('g_address')->nullable();
            $table->integer('g_contact_no')->nullable();
            
            
            $table->string('present_school')->nullable();
            $table->string('present_school_address')->nullable();
            $table->string('present_principal')->nullable();
            $table->string('present_tel_no')->nullable();
            $table->string('present_guidance')->nullable();
            
            $table->string('primary')->nullable();
            $table->string('primary_address')->nullable();
            $table->string('primary_year')->nullable();
            
            $table->string('gradeschool')->nullable();
            $table->string('gradeschool_address')->nullable();
            $table->string('gradeschool_year')->nullable();
            
            $table->string('highschool')->nullable();
            $table->string('highschool_address')->nullable();
            $table->string('highschool_year')->nullable();
            
            $table->string('date_of_admission')->nullable();
            $table->string('award')->nullable();
            $table->string('date_of_grad')->nullable();
            $table->string('remarks')->nullable();
            
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
        Schema::dropIfExists('bed_profiles');
    }
}
