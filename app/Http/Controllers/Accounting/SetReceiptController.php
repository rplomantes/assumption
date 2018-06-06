<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class SetReceiptController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $users = \App\User::where('accesslevel', env('CASHIER'))->get();
            return view('accounting.set_or_number', compact('users'));
        }
        
    }
    
    function update_or(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $x=count($request->idno);
            $i=0;
            for($i=0; $i<$x; $i++){
              $update_or = \App\ReferenceId::where('idno', $request->idno[$i])->first();
              //$update_or->start_receipt_no = $request->start_or_number[$i];
              $update_or->receipt_no = $request->or_number[$i];
              $update_or->end_receipt_no = $request->end_or_number[$i];
              $update_or->save();
            }
            $this->log('OR Updated');
            Session::flash('message', "OR updated!");
            
            return redirect('accounting/set_or');
            
//            return $request;
        }
    }
    
    public static function log($action){
        $log = new \App\Log();
        $log->action = "$action";
        $log->idno = Auth::user()->idno;
        $log->datetime = date("Y-m-d H:i:s");
        $log->save();
    }
    
    function search_or(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
        return view('accounting.search_or');
        }
    }
}
