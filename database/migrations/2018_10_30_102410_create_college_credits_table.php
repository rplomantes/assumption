<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollegeCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_credits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('course_code');
            $table->string('course_name');
            $table->string('lec');
            $table->string('credit_code')->nullable();
            $table->string('credit_name')->nullable();
            $table->string('finals');
            $table->string('completion')->nullable();;
            $table->string('school_year');
            $table->string('period');
            $table->string('school_name')->nullable();
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
        Schema::dropIfExists('college_credits');
    }
}
