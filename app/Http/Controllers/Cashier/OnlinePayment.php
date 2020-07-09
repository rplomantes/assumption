<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class OnlinePayment extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($date_from, $date_to) {
        if (Auth::user()->accesslevel == env("CASHIER")) {
            $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))
                            ->where('posted_by', "Paynamics")->where('credit_card_amount', '>', '0')->get();
        }
        return view('cashier.online_payment', compact('payments', 'date_from', 'date_to'));
    }
    
    function issue_or_number(){
        if(\Illuminate\Support\Facades\Request::ajax()){
            $payment_id = \Illuminate\Support\Facades\Input::get('payment_id');
            $date_from = \Illuminate\Support\Facades\Input::get('date_from');
            $date_to = \Illuminate\Support\Facades\Input::get('date_to');
            return view('cashier.ajax.issue_or_number',compact('payment_id','date_from','date_to'));
            
        }
        
    }
    
    function issue_or_number_now(Request $request){
        if (Auth::user()->accesslevel == env("CASHIER")) {
            $update_payment = \App\Payment::where('id',$request->payment_id)->first();
            $update_payment->receipt_no = $request->or_number;
            $update_payment->save();
            
            return redirect(url('cashier', array('online_payment',$request->date_from,$request->date_to)));
        }
    }

}
