<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBedEcrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bed_ecrs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject_code');
            $table->string('subject_name');
            $table->string('component_name');
            $table->decimal('percentage',2);
            $table->string('subcomponent_name');
            $table->integer('hps');
            $table->string('school_year');
            $table->string('period')->nullable();
            $table->string('qtr');
            $table->integer('raw_score')->default(0);
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
        Schema::dropIfExists('bed_ecrs');
    }
}
