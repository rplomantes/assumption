<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 
        
        Schema::table('ledgers', function(Blueprint $table) {
            $table->decimal('credit_memo',10,2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('ledgers', function(Blueprint $table) {
            $table->dropColumn('credit_memo');
        });
    }
}
