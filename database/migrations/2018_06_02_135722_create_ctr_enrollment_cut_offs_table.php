<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrEnrollmentCutOffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_enrollment_cut_offs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('academic_type');
            $table->date('enrollment_start');
            $table->date('cut_off');
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
        Schema::dropIfExists('ctr_enrollment_cut_offs');
    }
}
