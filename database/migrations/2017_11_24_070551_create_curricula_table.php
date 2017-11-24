<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurriculaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curricula', function (Blueprint $table) {
            $table->increments('id');
            $table->string('curriculum_year');
            $table->string('program_code');
            $table->string('program_name');
            $table->string('course_code')->unique();
            $table->string('course_name');
            $table->integer('lec')->nullable();
            $table->integer('lab')->nullable();
            $table->decimal('hours',5,2)->nullable();
            $table->string('level');
            $table->string('period');
            $table->integer('percent_tuition')->default(100);
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
        Schema::dropIfExists('curricula');
    }
}
