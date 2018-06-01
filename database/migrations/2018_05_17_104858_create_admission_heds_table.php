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
