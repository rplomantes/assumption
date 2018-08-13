<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use PDF;

class SetUpController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function set_up_summary(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            return view('accounting.set_up_summary');
        }
        
    }
    
    function print_set_up_summary(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
     
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            
            $ledgers = \App\Ledger::selectRaw('sum(amount) as amount, subsidiary, accounting_code')->where('amount','>','0')->where('school_year',$school_year)->where('category_switch','<',4)->where('department', $department)->groupBy('subsidiary', 'accounting_code')->get();
            $tuitions = \App\Ledger::selectRaw('sum(amount) as amount, subsidiary, accounting_code')->where('amount','>','0')->where('school_year',$school_year)->where('category_switch',6)->where('department', $department)->groupBy('subsidiary', 'accounting_code')->get();
            
            $pdf = PDF::loadView('accounting.print_setupsummary', compact('ledgers','tuitions','department','school_year','period'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("set_up_summary.pdf");
        }
        
    }
}
