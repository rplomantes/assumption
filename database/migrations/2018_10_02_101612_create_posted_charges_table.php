<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostedChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posted_charges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('due_date');
            $table->date('date_posted');
            $table->decimal('amount', 10,2);
            $table->integer('is_reversed')->default(0);
            $table->string('posted_by');
            $table->foreign('idno')
                    ->references('idno')->on('users')
                    ->onUpdate('cascade');
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
        Schema::dropIfExists('posted_charges');
    }
}
