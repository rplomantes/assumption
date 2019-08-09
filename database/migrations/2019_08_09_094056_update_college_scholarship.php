<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCollegeScholarship extends Migration
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
            $table->integer('non_discounted')->default(0);
            $table->integer('srf')->default(0);
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
        Schema::table('admission_heds', function(Blueprint $table) {
        $table->dropColumn('non_discounted');
        $table->dropColumn('srf');
        });
    }
}
