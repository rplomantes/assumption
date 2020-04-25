<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeBasicEdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_basic_eds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('track')->nullable();
            $table->string('strand')->nullable();
            $table->integer("subject_type")->default(0);
            $table->string("subject_code");
            $table->string("subject_name");
            $table->string("display_subject_code");
            $table->string("group_code");
            $table->string("group_name");
            $table->string("units");
            $table->integer("points")->nullable();
            $table->integer("weighted")->nullable();
            $table->integer("sort_to")->default(0);
            $table->integer("is_display_card")->default(1);
            $table->decimal("first_grading",7,2)->nullable();
            $table->decimal("second_grading",7,2)->nullable();
            $table->decimal("third_grading",7,2)->nullable();
            $table->decimal("fourth_grading",7,2)->nullable();
            $table->decimal("final_grade",7,2)->nullable();
            $table->string("first_grading_letter")->nullable();
            $table->string("second_grading_letter")->nullable();
            $table->string("third_grading_letter")->nullable();
            $table->string("fourth_grading_letter")->nullable();
            $table->string("final_grade_letter")->nullable();
            $table->string("first_remarks")->null();
            $table->string("second_remarks")->null();
            $table->string("third_remarks")->null();
            $table->string("fourth_remarks")->null();
            $table->string("final_remarks")->null();
            $table->integer("ranking")->nullable();
            $table->integer("status")->default(0);
            $table->string("school_year");
            $table->string("period")->nullable();
            $table->string("teacher")->nullable();
            $table->string("stl")->nullable();
            $table->foreign("idno")->references("idno")
                    ->on("users")->onUpdate('cascade');
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
        Schema::dropIfExists('grade_basic_eds');
    }
}
