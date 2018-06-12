<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->date('change_date');
            $table->string('original_plan');
            $table->string('change_plan');
            $table->decimal('original_amount',10,2);
            $table->decimal('change_amount',10,2);
            $table->string('posted_by');
             $table->foreign("idno")->references("idno")
                    ->on("users")->onUpdate("cascade");
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
        Schema::dropIfExists('change_plans');
    }
}
