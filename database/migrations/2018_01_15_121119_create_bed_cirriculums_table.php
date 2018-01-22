<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBedCirriculumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bed_cirriculums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('department');
            $table->string('level');
            $table->string('track')->nullable();
            $table->integer("subject_type")->default(0);
            $table->string("subject_code");
            $table->string("subject_name");
            $table->integer("points")->nullable();
            $table->integer("weighted")->nullable();
            $table->integer("sort_to")->default(0);
            $table->integer("is_display_card")->default(1);
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
        Schema::dropIfExists('bed_cirriculums');
    }
}
