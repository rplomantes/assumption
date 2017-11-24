<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctr_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('discount_code')->unique();
            $table->string('discount_description');
            $table->string('accounting_code');
            $table->integer('tuition_fee')->default(0);
            $table->integer('other_fee')->default(0);
            $table->integer('misc_fee')->default(0);
            $table->integer('depository_fee')->default(0);
            $table->integer('discount_type')->default(0);
            $table->decimal('amount',10,2)->default(0);
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
        Schema::dropIfExists('ctr_discounts');
    }
}
