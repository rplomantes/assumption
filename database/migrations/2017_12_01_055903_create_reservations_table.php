<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_id');
            $table->string('idno');
            $table->date('transaction_date');
            $table->decimal('amount',10,2)->default(0.00);
            $table->integer('is_reverse')->default(0);
            $table->integer('is_consumed')->default(0);
            $table->integer('reservation_type');
            $table->string('consume_sy')->nullable();
            $table->string('posted_by');
            $table->foreign('idno')->references('idno')
                    ->on('users')->onUpdate('cascade');
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
        Schema::dropIfExists('reservations');
    }
}
