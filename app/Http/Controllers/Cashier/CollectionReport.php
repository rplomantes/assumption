<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class CollectionReport extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function collection($date_from, $date_to){
        if(Auth::user()->accesslevel==env("CASHIER")){
            $payments = \App\Payment::whereBetween('transaction_date',array($date_from,$date_to))
                    ->where('posted_by',Auth::user()->idno)->get();
            return view('cashier.collection_report',compact('payments','date_from','date_to'));
        }
    }
    
    function list_of_checks($date_from, $date_to){
         if(Auth::user()->accesslevel==env("CASHIER")){
            $payments = \App\Payment::whereBetween('transaction_date',array($date_from,$date_to))
                    ->where('posted_by',Auth::user()->idno)->where('check_amount','>','0')->get();
            return view('cashier.list_of_checks',compact('payments','date_from','date_to'));
        }
        
    }
    function credit_cards($date_from, $date_to){
         if(Auth::user()->accesslevel==env("CASHIER")){
            $payments = \App\Payment::whereBetween('transaction_date',array($date_from,$date_to))
                    ->where('posted_by',Auth::user()->idno)->where('credit_card_amount','>','0')->get();
            return view('cashier.credit_cards',compact('payments','date_from','date_to'));
        }
        
    }
    function bank_deposits($date_from, $date_to){
         if(Auth::user()->accesslevel==env("CASHIER")){
            $payments = \App\Payment::whereBetween('transaction_date',array($date_from,$date_to))
                    ->where('posted_by',Auth::user()->idno)->where('deposit_amount','>','0')->get();
            return view('cashier.bank_deposits',compact('payments','date_from','date_to'));
        }
    }
    
    
}
