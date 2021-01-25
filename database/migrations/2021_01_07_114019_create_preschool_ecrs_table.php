<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreschoolEcrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preschool_ecrs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('subject_code')->nullable();
            $table->string('subject_name')->nullable();
            $table->string('group_code')->nullable();
            $table->string('group_name')->nullable();
            $table->string('card_name')->nullable();
            $table->string('school_year')->nullable();
            $table->string('level')->nullable();
            $table->string('section')->nullable();
            $table->string('qtr1')->nullable();
            $table->string('status1')->default(0);
            $table->string('qtr2')->nullable();
            $table->string('status2')->default(0);
            $table->string('qtr3')->nullable();
            $table->string('status3')->default(0);
            $table->string('qtr4')->nullable();
            $table->string('status4')->default(0);
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
        Schema::dropIfExists('preschool_ecrs');
    }
}
