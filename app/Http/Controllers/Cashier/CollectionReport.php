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
    
}
