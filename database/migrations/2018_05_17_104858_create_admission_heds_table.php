<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmissionHedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_heds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('applying_for');
            $table->string('strand')->nullable();
            $table->string('program_code')->nullable();
            $table->string('program_name')->nullable();
            $table->string('assumption_scholar')->nullable();
            $table->string('agreement')->nullable();
            $table->string('partner_scholar')->nullable();
            $table->string('summer_classes')->nullable();
            $table->string('student_status')->nullable();
            $table->string('tagged_as')->nullable();
            $table->integer('see_professional')->default(0);
            $table->string('condition')->nullable();
            $table->string('admission_status')->nullable();
            $table->integer('medical')->nullable();
            $table->integer('psychological')->nullable();
            $table->integer('learning_disability')->nullable();
            $table->integer('emotional')->nullable();
            $table->integer('social')->nullable();
            $table->integer('others')->nullable();
            $table->string('specify_condition')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('specify_citizenship')->nullable();
            $table->string('applying_for_sy')->nullable();
            $table->string('guardian_type')->nullable();
            
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
        Schema::dropIfExists('admission_heds');
    }
}
