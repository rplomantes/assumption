<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreRegistrationSettingCollegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_registration_setting_colleges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('program_code');
            $table->string('program_name');
            $table->integer('is_on')->default(1);
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
        Schema::dropIfExists('pre_registration_setting_colleges');
    }
}
