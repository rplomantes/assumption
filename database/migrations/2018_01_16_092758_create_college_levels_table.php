<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollegeLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_levels', function (Blueprint $table) {
            $table->increments('id');
             $table->string('idno');
            $table->date('date_advised')->nullable();
            $table->date('date_registered')->nullable();
            $table->date('date_enrolled')->nullable();
            $table->date('date_dropped')->nullable();
            $table->string('academic_type')->nullable();
            $table->string('department')->nullable();
            $table->string('program_code')->nullable();
            $table->string('program_name')->nullable();
            $table->string('level')->nullable();
            $table->integer('status')->default(0);
            $table->string('school_year')->nullable();
            $table->string('period')->nullable();
            $table->string('type_of_plan')->nullable();
            $table->string('type_of_account')->nullable();
            $table->string('type_of_discount')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('is_new')->default(0);
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
        Schema::dropIfExists('college_levels');
    }
}
