<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShsStudentInfo extends Migration
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
            $table->string('senior_highschool')->nullable();
            $table->string('senior_highschool_address')->nullable();
            $table->string('senior_highschool_year')->nullable();
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
        $table->dropColumn('senior_highschool');
        $table->dropColumn('senior_highschool_address');
        $table->dropColumn('senior_highschool_year');
        });
    }
}
