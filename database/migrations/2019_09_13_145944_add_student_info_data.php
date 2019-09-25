<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStudentInfoData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('student_infos', function(Blueprint $table) {
            $table->string('guardian')->nullable();
            $table->integer('g_is_living')->nullable();
            $table->string('g_occupation')->nullable();
            $table->string('g_phone')->nullable();
            $table->string('g_address')->nullable();
            
            $table->text('applied_year_course')->nullable();
            $table->text('applied_leaving')->nullable();
            
            $table->text('is_expelled_reason')->nullable();
            
            $table->text('is_modelling')->nullable();
            $table->text('is_officer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('student_infos', function(Blueprint $table) {
            $table->dropColumn('guardian');
            $table->dropColumn('g_is_living');
            $table->dropColumn('g_occupation');
            $table->dropColumn('g_phone');
            $table->dropColumn('g_address');
            
            $table->dropColumn('applied_year_course');
            $table->dropColumn('applied_leaving');
            
            $table->dropColumn('is_expelled_reason');
            
            $table->dropColumn('is_modelling');
            $table->dropColumn('is_officer');
        });
    }
}
