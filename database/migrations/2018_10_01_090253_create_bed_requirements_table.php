<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBedRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bed_requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->integer('psa')->default(0);
            $table->integer('recommendation_form')->default(0);
            $table->integer('baptismal_certificate')->default(0);
            $table->integer('passport_size_photo')->default(0);
            $table->integer('progress_report_card')->default(0);
            $table->integer('currentprevious_report_card')->default(0);
            $table->integer('narrative_assessment_report')->default(0);
            $table->integer('acr')->default(0);
            $table->integer('passport')->default(0);
            $table->integer('visa_parent')->default(0);
            $table->integer('photocopy_of_dual')->default(0);
            $table->integer('citizenship_passport')->default(0);
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
        Schema::dropIfExists('bed_requirements');
    }
}
