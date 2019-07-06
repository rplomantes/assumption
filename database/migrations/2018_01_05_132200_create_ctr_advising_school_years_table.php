<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrAdvisingSchoolYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_advising_school_years', function (Blueprint $table) {
            $table->increments('id');
            $table->string('academic_type');
            $table->string('school_year');
            $table->string('period');
            $table->integer('is_available')->default(0);
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
        Schema::dropIfExists('ctr_advising_school_years');
    }
}
