<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollegeAboutYousTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_about_yous', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('interest')->nullable();
            $table->string('goals')->nullable();
            $table->string('challenges')->nullable();
            $table->string('com_channel')->nullable();
            $table->integer('awareness')->default(0);
            $table->integer('commitment')->default(0);
            $table->integer('kindness')->default(0);
            $table->integer('simplicity')->default(0);
            $table->integer('humility')->default(0);
            $table->integer('integrity')->default(0);
            $table->integer('oneness')->default(0);
            $table->integer('nature')->default(0);
            $table->string('others')->nullable();
            $table->foreign('idno')->references('idno')
                    ->on('users')->onUpdate('cascade');
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
        Schema::dropIfExists('college_about_yous');
    }
}
