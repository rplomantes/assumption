<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->date('date_application_finish')->nullable();
            $table->date('date_admission_finish')->nullable();
            $table->date('date_advised')->nullable();
            $table->date('date_registered')->nullable();
            $table->date('date_enrolled')->nullable();
            $table->date('date_dropped')->nullable();
            $table->string('academic_type')->nullable();
            $table->string('academic_code')->nullable();
            $table->integer('status')->default(0);
            $table->string('department')->nullable();
            $table->string('program_code')->nullable();
            $table->string('program_name')->nullable();
            $table->string('level')->nullable();
            $table->string('section')->nullable();
            $table->string('track')->nullable();
            $table->string('strand')->nullable();
            $table->string('school_year')->nullable();
            $table->string('period')->nullable();
            $table->integer('is_new')->default(1);
            $table->string('type_of_plan')->nullable();
            $table->string('type_of_account')->nullable();
            $table->string('type_of_discount')->nullable();
            $table->integer('esc')->default(0);
            $table->string('registration_no')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('is_audit')->default(0);
            $table->string('levels_reference_id')->nullable();
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
        Schema::dropIfExists('statuses');
    }
}
