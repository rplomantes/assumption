<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverpaymentMemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overpayment_memos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idno');
            $table->date('transaction_date');
            $table->string('op_no');
            $table->decimal('amount',10,2);
            $table->string('posted_by');
            $table->string('school_year');
            $table->string('period');
            $table->string('levels_reference_id')->nullable();
            $table->foreign("idno")->references("idno")
                    ->on("users")->onUpdate("cascade");
            $table->timestamps();
        });
        Schema::table('reference_ids', function(Blueprint $table) {
            $table->integer('op_no')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overpayment_memos');
        Schema::table('reference_ids', function(Blueprint $table) {
        $table->dropColumn('op_no');
        });
    }
}
