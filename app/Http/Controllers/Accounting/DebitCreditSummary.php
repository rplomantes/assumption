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

    function debit_summary($date_from, $date_to) {
        $debits = \App\DebitMemo::whereBetween('transaction_date', array($date_from, $date_to))->get();

        return view('accounting.debit_summary', compact('debits', 'date_from', 'date_to'));
    }

    function print_debit_summary($date_from, $date_to) {
        
        $debits = \App\DebitMemo::whereBetween('transaction_date', array($date_from, $date_to))->get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('accounting.print_debit_summary', compact('debits', 'date_from', 'date_to'));
        $pdf->setPaper('legal', 'landscape');
        
        return $pdf->stream();
    }

}
