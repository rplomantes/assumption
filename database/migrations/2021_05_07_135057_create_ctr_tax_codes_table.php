<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCtrTaxCodesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('ctr_tax_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tax_code');
            $table->decimal('tax_rate', 10, 2);
            $table->integer('is_half')->default(0);
            $table->timestamps();

            Schema::table('ctr_suppliers', function(Blueprint $table) {
                $table->dropColumn('due_date');
                $table->string('tax_code');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('ctr_tax_codes');

        Schema::table('ctr_suppliers', function(Blueprint $table) {
            $table->dropColumn('tax_code');
            $table->string('due_date');
        });
    }

}
