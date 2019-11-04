<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentInfoParentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_info_parent_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            
            $table->string('g_personal_address')->nullable();
            $table->string('g_email')->nullable();
            $table->string('g_personal_phone')->nullable();
            $table->string('g_attainment')->nullable();
            $table->string('g_citizenship')->nullable();
            $table->string('g_company_name')->nullable();
            
            $table->string('f_personal_address')->nullable();
            $table->string('f_email')->nullable();
            $table->string('f_personal_phone')->nullable();
            $table->string('f_attainment')->nullable();
            $table->string('f_citizenship')->nullable();
            $table->string('f_company_name')->nullable();
            
            $table->string('m_personal_address')->nullable();
            $table->string('m_email')->nullable();
            $table->string('m_personal_phone')->nullable();
            $table->string('m_attainment')->nullable();
            $table->string('m_citizenship')->nullable();
            $table->string('m_company_name')->nullable();
            
            $table->string('s_personal_address')->nullable();
            $table->string('s_email')->nullable();
            $table->string('s_personal_phone')->nullable();
            $table->string('s_attainment')->nullable();
            $table->string('s_citizenship')->nullable();
            $table->string('s_company_name')->nullable();
            $table->string('s_dob')->nullable();
            
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
        Schema::dropIfExists('student_info_parent_infos');
    }
}
