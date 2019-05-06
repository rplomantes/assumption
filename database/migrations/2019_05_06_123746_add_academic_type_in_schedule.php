<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcademicTypeInSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('individual_schedules', function(Blueprint $table) {
            $table->string('academic_type');
        });
        Schema::table('group_schedules', function(Blueprint $table) {
            $table->string('academic_type');
        });
        Schema::table('interview_schedules', function(Blueprint $table) {
            $table->string('academic_type');
        });
        Schema::table('testing_schedules', function(Blueprint $table) {
            $table->string('academic_type');
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
        Schema::table('testing_schedules', function(Blueprint $table) {
        $table->dropColumn('academic_type');
        });
        Schema::table('interview_schedules', function(Blueprint $table) {
        $table->dropColumn('academic_type');
        });
        Schema::table('group_schedules', function(Blueprint $table) {
        $table->dropColumn('academic_type');
        });
        Schema::table('individual_schedules', function(Blueprint $table) {
        $table->dropColumn('academic_type');
        });
    }
}
