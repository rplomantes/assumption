<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrTransmuLetterArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_transmu_letter_archives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('letter_grade_type');
            $table->string('grade');
            $table->string('letter_grade');
            $table->string('school_year');
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
        Schema::dropIfExists('ctr_transmu_letter_archives');
    }
}
