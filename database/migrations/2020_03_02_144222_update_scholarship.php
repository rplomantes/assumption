<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateScholarship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('college_scholarships', function(Blueprint $table) {
            $table->integer('dorm')->default(0);
            $table->integer('meal')->default(0);
            $table->string('remarks')->nullable();
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
        Schema::table('college_scholarships', function(Blueprint $table) {
            $table->dropColumn('dorm');
            $table->dropColumn('meal');
            $table->dropColumn('remarks');
        });
    }
}
