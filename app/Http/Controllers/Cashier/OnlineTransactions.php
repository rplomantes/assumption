<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class OnlineTransactions extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($date_from, $date_to) {
        if (Auth::user()->accesslevel == env("CASHIER") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            $transactions = \App\RequestPayment::whereBetween('request_date', array($date_from, $date_to))->get();
        }
        return view('accounting.online_transactions.index', compact('transactions', 'date_from', 'date_to'));
    }

    function search_transaction_history(Request $request) {
        
        $request_id = $request->request_id;
        
        return redirect(url('online_transaction_history',array($request_id)));
    }

    function transaction_history($request_id) {
        if (Auth::user()->accesslevel == env("CASHIER") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            $transaction_histories = \App\PaynamicsResponse::where('request_id',$request_id)->orderBy('timestamp','asc')->get();
            $transaction = \App\RequestPayment::where('request_id',$request_id)->first();
        }
        return view('accounting.online_transactions.transaction_history', compact('transaction','transaction_histories'));
    }
}
