<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddingDroppingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adding_droppings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('course_id')->nullable();
            $table->string('course_code')->nullable();
            $table->string('course_name')->nullable();
            $table->string('level')->nullable();
            $table->string('lec')->nullable();
            $table->string('lab')->nullable();
            $table->string('hours')->nullable();
            $table->string('percent_tuition')->nullable();
            $table->string('srf')->nullable();
            $table->string('action');
            $table->integer('is_done')->default(0);
            $table->string('posted_by');
            $table->foreign('posted_by')
                    ->references('idno')->on('users')
                    ->onUpdate('cascade');
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
        Schema::dropIfExists('adding_droppings');
    }
}
