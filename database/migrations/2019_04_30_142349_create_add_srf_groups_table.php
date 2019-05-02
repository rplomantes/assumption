<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddSrfGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('curricula', function(Blueprint $table) {
            $table->string('srf_group')->nullable();
        });
        Schema::table('ledgers', function(Blueprint $table) {
            $table->string('srf_group')->nullable();
        });
        Schema::table('course_offerings', function(Blueprint $table) {
            $table->string('srf_group')->nullable();
        });
        Schema::table('grade_colleges', function(Blueprint $table) {
            $table->string('srf_group')->nullable();
        });
        Schema::table('ctr_electives', function(Blueprint $table) {
            $table->string('srf_group')->nullable();
        });
        Schema::table('adding_droppings', function(Blueprint $table) {
            $table->string('srf_group')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('curricula', function(Blueprint $table) {
            $table->dropColumn('srf_group');
        });
        Schema::table('ledgers', function(Blueprint $table) {
            $table->dropColumn('srf_group');
        });
        Schema::table('course_offerings', function(Blueprint $table) {
            $table->dropColumn('srf_group');
        });
        Schema::table('grade_colleges', function(Blueprint $table) {
            $table->dropColumn('srf_group');
        });
        Schema::table('ctr_electives', function(Blueprint $table) {
            $table->dropColumn('srf_group');
        });
        Schema::table('adding_droppings', function(Blueprint $table) {
            $table->dropColumn('srf_group');
        });
    }
}
