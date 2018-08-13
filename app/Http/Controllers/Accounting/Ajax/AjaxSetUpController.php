<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Request;

class AjaxSetUpController extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function getsetupsummary(){
        if(Request::ajax()){
            $department = Input::get('department');
            $school_year = Input::get('school_year');
            $period = Input::get('period');
            
            $ledgers = \App\Ledger::selectRaw('sum(amount) as amount, subsidiary, accounting_code')->where('amount','>','0')->where('school_year',$school_year)->where('category_switch','<',4)->where('department', $department)->groupBy('subsidiary', 'accounting_code')->get();
            $tuitions = \App\Ledger::selectRaw('sum(amount) as amount, subsidiary, accounting_code')->where('amount','>','0')->where('school_year',$school_year)->where('category_switch',6)->where('department', $department)->groupBy('subsidiary', 'accounting_code')->get();

            return view('accounting.ajax.getsetupsummary',compact('ledgers', 'tuitions','department','school_year','period'));
        }
    }
}
