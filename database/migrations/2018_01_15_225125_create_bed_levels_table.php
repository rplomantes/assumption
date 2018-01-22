<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBedLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bed_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->date('date_registered')->nullable();
            $table->date('date_enrolled')->nullable();
            $table->date('date_dropped')->nullable();
            $table->string('department');
            $table->string('track')->nullable();
            $table->string('strand')->nullable();
            $table->string('level')->nullable();
            $table->string('section')->nullable();
            $table->string('school_year')->nullable();
            $table->string('period')->nullable();
            $table->string('type_of_plan')->nullable();
            $table->string('type_of_account')->nullable();
            $table->string('type_of_discount')->nullable();
            $table->integer('esc')->default(0);
            $table->string('registration_no')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('is_new')->default(0);
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
        Schema::dropIfExists('bed_levels');
    }
}
