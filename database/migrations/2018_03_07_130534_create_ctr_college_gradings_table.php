<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrCollegeGradingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_college_gradings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('academic_type');
            $table->integer('prelim')->default(1);
            $table->integer('midterm')->default(1);
            $table->integer('finals')->default(1);
            $table->integer('grade_point')->default(1);
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
        Schema::dropIfExists('ctr_college_gradings');
    }
}
