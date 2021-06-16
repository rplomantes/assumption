<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaascuSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paascu_surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('name');
            $table->string('relationship');
            $table->string('religion');
            $table->string('nationality');
            $table->string('highest_education');
            $table->string('professional');
            $table->string('prof_time');
            $table->string('education');
            $table->string('educ_time');
            $table->string('trade');
            $table->string('trade_time');
            $table->string('agri_fishing');
            $table->string('agri_fishing_time');
            $table->string('industry_craft');
            $table->string('industry_craft_time');
            $table->string('civil_gov');
            $table->string('civil_gov_time');
            $table->string('unemployed_retired');
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
        Schema::dropIfExists('paascu_surveys');
    }
}
