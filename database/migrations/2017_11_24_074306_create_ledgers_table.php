<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('department');
            $table->string('program_code')->nullable();
            $table->string('track')->nullable();
            $table->string('level');
            $table->string('school_year');
            $table->string('period');
            $table->string('category');
            $table->string('subsidiary');
            $table->string('receipt_details');
            $table->string('accounting_code');
            $table->integer('category_switch');
            $table->decimal('amount',10,2);
            $table->decimal('payment',10,2)->default(0.00);
            $table->decimal('discount',10,2)->default(0.00);
            $table->decimal('esc',10,2)->default(0.00);
            $table->decimal('debit_memo',10,2)->default(0.00);
            $table->integer('discount_code')->nullable();
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
        Schema::dropIfExists('ledgers');
    }
}
