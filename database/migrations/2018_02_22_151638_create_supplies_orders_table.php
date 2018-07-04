<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplies_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->string('item_group');
            $table->string('item_code');
            $table->string('description');
            $table->integer('qty');
            $table->decimal('amount',10,2);
            $table->integer('is_serve')->default(0);
            $table->date('serve_date')->nullable();
            $table->string('serve_type')->nullable();
            $table->string('remarks')->nullable();
            $table->string('release_by')->nullable();
            $table->string('receive_by')->nullable();
            $table->string('level');
            $table->string('school_year');
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
        Schema::dropIfExists('supplies_orders');
    }
}
