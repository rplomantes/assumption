<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHoldGrade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        
        Schema::table('hold_grades', function(Blueprint $table) {
            $table->string('subject_code')->nullable();
            $table->string('hold_by')->nullable();
        });
        
        Schema::table('student_ecrs', function(Blueprint $table) {
            $table->integer('is_hold_for_viewing')->default(0);
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
        Schema::table('hold_grades', function(Blueprint $table) {
            $table->dropColumn('subject_code');
            $table->dropColumn('hold_by');
        });
        
        Schema::table('student_ecrs', function(Blueprint $table) {
            $table->dropColumn('is_hold_for_viewing');
        });
    }
}
