<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('school_year');
            $table->string('period')->nullable();
            $table->string('level')->nullable();
            $table->string('strand')->nullable();
            $table->string('discount_code')->nullable();
            $table->string('discount_description')->nullable();
            $table->string('accounting_code')->nullable();
            $table->integer('tuition_fee')->default(0);
            $table->integer('other_fee')->default(0);
            $table->integer('misc_fee')->default(0);
            $table->integer('depository_fee')->default(0);
            $table->integer('discount_type')->default(0);
            $table->decimal('amount',10,2)->default(0);
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
        Schema::dropIfExists('discount_lists');
    }
}
