<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebitMemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debit_memos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->date('transaction_date');
            $table->string('reference_id');
            $table->string('dm_no');
            $table->string('explanation');
            $table->decimal('amount',10,2);
            $table->integer('is_current')->default(1);
            $table->integer('is_reverse')->default(0);
            $table->string('reservation_sy')->nullable();
            $table->string('posted_by');
            $table->string('levels_reference_id')->nullable();
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
        Schema::dropIfExists('debit_memos');
    }
}
