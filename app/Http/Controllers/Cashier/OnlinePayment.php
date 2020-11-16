<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;

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

    function issue_or_number() {
        if (\Illuminate\Support\Facades\Request::ajax()) {
            $payment_id = \Illuminate\Support\Facades\Input::get('payment_id');
            $date_from = \Illuminate\Support\Facades\Input::get('date_from');
            $date_to = \Illuminate\Support\Facades\Input::get('date_to');
            return view('cashier.ajax.issue_or_number', compact('payment_id', 'date_from', 'date_to'));
        }
    }

    function issue_or_number_now(Request $request) {
        if (Auth::user()->accesslevel == env("CASHIER")) {

            $validate = $request->validate([
                'or_number' => 'required|size:10',
            ]);
            if ($validate) {
                $check_or_number = \App\Payment::where('receipt_no', $request->or_number)->get();
                if (count($check_or_number) > 0) {

                    Session::flash('danger', 'Oops!, OR number already encoded.');
                } else {
                    $update_payment = \App\Payment::where('id', $request->payment_id)->first();
                    $update_payment->receipt_no = $request->or_number;
                    $update_payment->save();
                    Session::flash('message', 'OR number issued successfully!');
                }
            }
            return redirect(url('cashier', array('online_payment', $request->date_from, $request->date_to)));
        }
    }

    function paypal_transactions() {
        if (Auth::user()->accesslevel == env("CASHIER") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            return view('cashier.paypaltransactions');
        }
    }

    function getpaypaltransactions() {
        if (\Illuminate\Support\Facades\Request::ajax()) {
            $invoice_id = \Illuminate\Support\Facades\Input::get('invoice_id');
            $getpayment = \App\RequestPayment::where('response_id',$invoice_id)->first();
            
            return view('cashier.ajax.paypaltransaction',compact('invoice_id','getpayment'));
        }
    }

}
