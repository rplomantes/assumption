<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalEntryDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_entry_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transaction_date');
            $table->string('reference_id');
            $table->string('voucher_no');
            $table->string('category');
            $table->string('subsidiary')->nullable();
            $table->string('description');
            $table->string('accounting_code');
            $table->integer('entry_type');
            $table->integer('fiscal_year');
            $table->string('receipt_type')->default('JV');
            $table->decimal('debit', 10, 2)->default(0);
            $table->decimal('credit', 10, 2)->default(0);
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
        Schema::dropIfExists('journal_entry_datas');
    }
}
