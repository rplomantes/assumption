<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class BookOfAccount extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function cash_receipt($date_start, $date_end) {
        $reference_ids = \App\Payment::where('transaction_date', '>=', $date_start)->where('transaction_date', '<=', $date_end)->get();
        if (count($reference_ids) > 0) {
            foreach ($reference_ids as $reference_id) {
                $cash_receipt[] = new \App\Accounting\CashReceipts($reference_id->reference_id);
            }
        return view('accounting.test', compact('cash_receipt', 'date_start', 'date_end'));
        }else{
        }
    }

}
