<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmissionHedRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_hed_requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->integer('birth_certificate')->nullable();
            $table->integer('form138')->nullable();
            $table->integer('labtest')->nullable();
            $table->integer('admission_agreement')->nullable();
            $table->integer('parent_partnership')->nullable();
            $table->integer('school_rec')->nullable();
            $table->integer('tor')->nullable();
            $table->integer('honor_dismiss')->nullable();   
            $table->integer('course_desc')->nullable();
            $table->integer('cbc')->nullable();
            $table->integer('bt')->nullable();
            $table->integer('x_ray')->nullable();
            $table->integer('visa')->nullable();
            $table->integer('passport')->nullable();
            $table->integer('photocopy_diploma')->nullable();
            $table->integer('marriage_contract')->nullable();
            $table->integer('child_birth_cert')->nullable();
            $table->integer('school_rec')->nullable();
            $table->integer('medical_clearance')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('admission_hed_requirements');
    }
}
