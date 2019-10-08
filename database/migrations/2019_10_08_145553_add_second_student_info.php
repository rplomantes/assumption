<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecondStudentInfo extends Migration
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
            $table->string('dean')->nullable();
            $table->string('last_school_number')->nullable();
            $table->string('guidance_counselor')->nullable();
            $table->text('are_you_candidate')->nullable();
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
            $table->dropColumn('dean');
            $table->dropColumn('last_school_number');
            $table->dropColumn('guidance_counselor');
            $table->dropColumn('are_you_candidate');
        });
    }
}
