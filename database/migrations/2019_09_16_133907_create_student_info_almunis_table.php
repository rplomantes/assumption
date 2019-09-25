<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentInfoAlmunisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_info_almunis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('name')->nullable();
            $table->string('relationship')->nullable();
            $table->string('year_graduated')->nullable();
            $table->string('department')->nullable();
            $table->string('location')->nullable();
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
        Schema::dropIfExists('student_info_almunis');
    }
}
