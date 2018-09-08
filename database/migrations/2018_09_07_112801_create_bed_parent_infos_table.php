<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBedParentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bed_parent_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('father')->nullable();
            $table->string('f_citizenship')->nullable();
            $table->string('f_is_living')->nullable();
            $table->string('f_religion')->nullable();
            $table->string('f_education')->nullable();
            $table->string('f_school')->nullable();
            $table->string('f_occupation')->nullable();
            $table->string('f_company_name')->nullable();
            $table->string('f_company_address')->nullable();
            $table->string('f_company_number')->nullable();
            $table->string('f_phone')->nullable();
            $table->string('f_cell_no')->nullable();
            $table->string('f_address')->nullable();
            $table->string('f_email')->nullable();
            $table->string('f_any_org')->nullable();
            $table->string('f_type_of_org')->nullable();
            $table->string('f_expertise')->nullable();
            
            $table->string('mother')->nullable();
            $table->string('m_citizenship')->nullable();
            $table->string('m_is_living')->nullable();
            $table->string('m_religion')->nullable();
            $table->string('m_education')->nullable();
            $table->string('m_school')->nullable();
            $table->string('m_occupation')->nullable();
            $table->string('m_company_name')->nullable();
            $table->string('m_company_address')->nullable();
            $table->string('m_company_number')->nullable();
            $table->string('m_phone')->nullable();
            $table->string('m_cell_no')->nullable();
            $table->string('m_address')->nullable();
            $table->string('m_email')->nullable();
            $table->string('m_any_org')->nullable();
            $table->string('m_type_om_org')->nullable();
            $table->string('m_expertise')->nullable();
            $table->string('m_alumna_gradeschool_year')->nullable();
            $table->string('m_alumna_highschool_year')->nullable();
            $table->string('m_alumna_college_year')->nullable();
            
            $table->string('parents_civil_status')->nullable();
            
            $table->string('guardian')->nullable();
            $table->string('g_relation')->nullable();
            $table->string('g_address')->nullable();
            $table->string('g_contact_no')->nullable();
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
        Schema::dropIfExists('bed_parent_infos');
    }
}
