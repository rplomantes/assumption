<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno')->unique();
            $table->string('applying_for');
            $table->string('level')->nullable();
            $table->string('strand')->nullable();
            $table->string('program_code')->nullable();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('extensionname')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality')->nullable();
            $table->string('province')->nullable();
            $table->string('zip')->nullable();
            $table->string('tel_no')->nullable();
            $table->string('cell_no')->nullable();
            $table->string('email')->unique();
            $table->string('date_of_birth');
            $table->string('lrn');
            $table->integer('is_foreign')->default(0);
            $table->integer('is_complete')->default(0);
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
        Schema::dropIfExists('pre_registrations');
    }
}
