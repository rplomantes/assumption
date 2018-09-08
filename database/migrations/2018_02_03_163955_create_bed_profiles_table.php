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
