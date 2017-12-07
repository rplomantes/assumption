<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use App\Http\Controllers\Cashier\StudentLedger;

class OtherPayment extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function other_payment($idno){
        $user=  \App\User::where('idno',$idno)->first();
        $status= \App\User::where('idno',$idno)->first();
        $receipt_number=  StudentLedger::getreceipt();
        $particulars = \App\OtherPayment::get();
        return view('cashier.other_payment',compact('user','status','receipt_number','particulars'));
    }
}
