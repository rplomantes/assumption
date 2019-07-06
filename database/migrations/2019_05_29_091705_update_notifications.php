<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('college_notifications', function(Blueprint $table) {
            $table->string('idno');
            $table->string('department');
            $table->integer('is_active')->default(1);
            $table->foreign("idno")->references("idno")
                    ->on("users")->onUpdate("cascade");
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
        Schema::table('college_notifications', function(Blueprint $table) {
        $table->dropColumn('idno');
        $table->dropColumn('department');
        $table->dropColumn('is_active');
        });
    }
}
