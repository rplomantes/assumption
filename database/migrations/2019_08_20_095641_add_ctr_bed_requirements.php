<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCtrBedRequirements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('ctr_bed_requirements', function(Blueprint $table) {
            $table->integer('question_parent')->default(0);
            $table->integer('question_student')->default(0);
            $table->integer('essay')->default(0);
            $table->integer('dpa')->default(0);
            $table->integer('adviser_guidance_reco')->default(0);
            $table->integer('principal_reco')->default(0);
        });
        Schema::table('bed_requirements', function(Blueprint $table) {
            $table->integer('question_parent')->default(0);
            $table->integer('question_student')->default(0);
            $table->integer('essay')->default(0);
            $table->integer('dpa')->default(0);
            $table->integer('adviser_guidance_reco')->default(0);
            $table->integer('principal_reco')->default(0);
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
        Schema::table('ctr_bed_requirements', function(Blueprint $table) {
        $table->dropColumn('question_parent');
        $table->dropColumn('question_student');
        $table->dropColumn('essay');
        $table->dropColumn('dpa');
        $table->dropColumn('adviser_guidance_reco');
        $table->dropColumn('principal_reco');
        });
        Schema::table('bed_requirements', function(Blueprint $table) {
        $table->dropColumn('question_parent');
        $table->dropColumn('question_student');
        $table->dropColumn('essay');
        $table->dropColumn('dpa');
        $table->dropColumn('adviser_guidance_reco');
        $table->dropColumn('principal_reco');
        });
    }
}
