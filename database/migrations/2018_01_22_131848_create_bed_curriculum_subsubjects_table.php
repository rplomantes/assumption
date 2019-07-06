<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBedCurriculumSubsubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bed_curriculum_subsubjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject_code');
            $table->string('sub_subject_code');
            $table->string('sub_subject_name');
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
        Schema::dropIfExists('bed_curriculum_subsubjects');
    }
}
