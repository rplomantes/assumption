<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrAcademicProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_academic_programs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('academic_type');
            $table->string('department');
            $table->string('program_code');
            $table->string('program_name');
            $table->string('level');
            $table->string('track');
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
        Schema::dropIfExists('ctr_academic_programs');
    }
}
