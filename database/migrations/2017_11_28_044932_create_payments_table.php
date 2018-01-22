<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transaction_date');
            $table->string('receipt_no');
            $table->string('reference_id');
            $table->string('idno');
            $table->string('paid_by');
            $table->string('program_code')->nullable();
            $table->string('tracks')->nullable();
            $table->string('strand')->nullable();
            $table->string('level')->nullable();
            $table->string('section')->nullable();
            $table->integer('payment_type')->default(0);
            $table->string('check_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->decimal('check_amount',10,2)->default(0.00);
            $table->decimal('amount_received',10,2)->default(0.00);
            $table->decimal('cash_amount',10,2)->default(0.00);
            $table->string('credit_card_number')->nullable();
            $table->string('approval_number')->nullable();
            $table->string('credit_card_bank')->nullable();
            $table->string('credit_card_type')->nullable();
            $table->decimal('credit_card_amount',10,2)->default(0.00);
            $table->string('deposit_reference')->nullable();
            $table->decimal('deposit_amount',10,2)->default(0.00);
            $table->integer('is_reverse')->default(0);
            $table->integer('is_current')->default(1);
            $table->string('school_year')->nullable();
            $table->string('period')->nullable();
            $table->string('remarks');
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
        Schema::dropIfExists('payments');
    }
}
