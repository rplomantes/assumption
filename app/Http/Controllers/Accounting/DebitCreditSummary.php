<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DebitCreditSummary extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function debit_summary($date_from, $date_to, $posted_by) {
        if($posted_by == "all"){
        $debits = \App\DebitMemo::whereBetween('transaction_date', array($date_from, $date_to))->get();
        }else{
        $debits = \App\DebitMemo::whereBetween('transaction_date', array($date_from, $date_to))->where('posted_by', $posted_by)->get();
        }

        return view('accounting.debit_summary', compact('debits', 'date_from', 'date_to', 'posted_by'));
    }

    function print_debit_summary($date_from, $date_to, $posted_by) {
        if($posted_by == "all"){
        $debits = \App\DebitMemo::whereBetween('transaction_date', array($date_from, $date_to))->get();
        }else{
        $debits = \App\DebitMemo::whereBetween('transaction_date', array($date_from, $date_to))->where('posted_by', $posted_by)->get();
        }

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('accounting.print_debit_summary', compact('debits', 'date_from', 'date_to'));
        $pdf->setPaper('legal', 'landscape');
        
        return $pdf->stream();
    }

}
