<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class UpdateTransactionDate extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updatetransactiondate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        //
        $gettransactions = \App\Accounting::distinct()->where('transaction_date', null)->get(['reference_id', 'transaction_date']);
        foreach ($gettransactions as $transaction) {
            $getdate = \App\Accounting::where('reference_id', $transaction->reference_id)->where('transaction_date', '!=', null)->first();
            $gets = \App\Accounting::where('reference_id', $transaction->reference_id)->where('transaction_date', null)->get();
            if ($getdate) {
                foreach ($gets as $get) {
                    $get->transaction_date = $getdate->transaction_date;
                    $get->update();
                }
            }
                    $this->info($transaction->reference_id);
        }
    }

}
