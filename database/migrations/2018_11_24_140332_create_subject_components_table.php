<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_components', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject_code');
            $table->string('subject_name');
            $table->string('component_name');
            $table->decimal('percentage',2);
            $table->string('subcomponent_name')->nullable();
            $table->integer('hps')->nullable();
            $table->string('school_year');
            $table->string('period')->nullable();
            $table->string('qtr');
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
        Schema::dropIfExists('subject_components');
    }
}
