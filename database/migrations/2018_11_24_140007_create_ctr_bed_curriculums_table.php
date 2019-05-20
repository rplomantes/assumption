<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrBedCurriculumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_bed_curriculums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('academic_type');
            $table->string('level');
            $table->string('period')->nullable();
            $table->string('strand')->nullable();
            $table->string('display_subject_code');
            $table->string("subject_code");
            $table->string("subject_name");
            $table->string("group_id")->nullable();
            $table->string("group_name")->nullable();
            $table->integer("category")->default(1);
            $table->string("units")->nullable();
            $table->integer("points")->nullable();
            $table->integer("weighted")->nullable();
            $table->integer("sort_to")->default(0);
            $table->integer("is_automatic")->default(1);
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
        Schema::dropIfExists('ctr_bed_curriculums');
    }
}
