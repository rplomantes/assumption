<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class GeneralLedger extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    function generalLedger(){
       $accounting_entry = \App\ChartOfAccount::all();
       return view('accounting.general_ledger.general_ledger',compact('accounting_entry'));
    }
    
    function generateLedger($accounting_code,$finalStartDate,$finalEndDate){
        $entries = \App\Accounting::where('accounting_code',$accounting_code)
                ->where('is_reverse',0)->whereBetween('transaction_date', [$finalStartDate, $finalEndDate])
                ->orderBy('transaction_date')->get();
        $account = \App\ChartOfAccount::where('accounting_code',$accounting_code)->first();
        return view('accounting.general_ledger.generate_ledger',compact('account','entries','finalStartDate','finalEndDate'));
    }
    
    function printGenerateLedger($accounting_code,$finalStartDate,$finalEndDate){
        $entries = \App\Accounting::where('accounting_code',$accounting_code)
                ->where('is_reverse',0)->whereBetween('transaction_date', [$finalStartDate, $finalEndDate])
                ->orderBy('transaction_date')->get();
        $account = \App\ChartOfAccount::where('accounting_code',$accounting_code)->first();
        
        $pdf = PDF::loadView('accounting.general_ledger.print_general_ledger', compact('entries', 'account', 'finalStartDate', 'finalEndDate'));
        $pdf->setPaper('letter', 'landscape');
        return $pdf->stream("general_ledger.pdf");
    }
}
