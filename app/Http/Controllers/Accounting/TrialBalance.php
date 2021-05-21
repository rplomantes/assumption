<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class TrialBalance extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    function trialBalance(){
        return view('accounting.trial_balance.trial_balance');
    }
    
    function printTrialBalance(Request $request) {
        $date_to = $request->date_to;
        $date_from = $request->date_from;
        $finalStartDate = "$date_from";
        $finalEndDate = "$date_to";
        
        $lists = \App\Accounting::join('chart_of_accounts','accountings.accounting_code','chart_of_accounts.accounting_code')
                ->selectRaw('accountings.accounting_code, chart_of_accounts.accounting_name, case when (sum(debit) - sum(credit)) > 0 then sum(debit) - sum(credit) end as debit,case when (sum(debit) - sum(credit)) < 0 then sum(debit) - sum(credit) end as credit')
                ->where('is_reverse',0)->where('accounting_type','>', 10)->whereBetween('transaction_date', [$finalStartDate, $finalEndDate])
                ->groupBy('accountings.accounting_code')->get();
            $pdf = PDF::loadView('accounting.trial_balance.print_trial_balance', compact('lists', 'date','finalStartDate','finalEndDate','sort'));
            $pdf->setPaper('letter','portrait');
            return $pdf->stream("trial_balance.pdf");
    
    }
}
