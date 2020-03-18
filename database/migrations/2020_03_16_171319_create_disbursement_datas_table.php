<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisbursementDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disbursement_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transaction_date');
            $table->string('reference_id');
            $table->string('voucher_no');
            $table->integer('type')->default(0);
            $table->string('paid_by');
            $table->string('category');
            $table->string('description');
            $table->string('receipt_details');
            $table->string('accounting_code');
            $table->string('category_switch');
            $table->integer('entry_type');
            $table->integer('fiscal_year');
            $table->string('receipt_type')->default('D');
            $table->decimal('debit',10,2)->default(0);
            $table->decimal('credit',10,2)->default(0);
            $table->string('particular')->nullable();
            $table->integer('isreverse')->default(0);
            $table->string('posted_by');
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
        Schema::dropIfExists('disbursement_datas');
    }
}
