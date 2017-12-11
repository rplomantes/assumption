<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use App\Http\Controllers\Cashier\StudentLedger;
use App\Http\Controllers\Cashier\StudentReservation;

class OtherPayment extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function other_payment($idno){
        if(Auth::user()->accesslevel==env("CASHIER")){
        $user=  \App\User::where('idno',$idno)->first();
        $status= \App\User::where('idno',$idno)->first();
        $receipt_number=  StudentLedger::getreceipt();
        $particulars = \App\OtherPayment::get();
        return view('cashier.other_payment',compact('user','status','receipt_number','particulars'));
    }}
    function post_other_payment(Request $request){
        if(Auth::user()->accesslevel==env("CASHIER")){    
        DB::beginTransaction();
        $reference_id = uniqid();
        StudentReservation::postPayment($request,$reference_id);
        $this->postAccounting($request, $reference_id);
        StudentReservation::postCashDebit($request, $reference_id);
        StudentLedger::updatereceipt();
        DB::commit();
        return redirect(url('/cashier',array('viewreceipt',$reference_id)));
        }
        //return $request;
    }
    
    function postAccounting($request,$reference_id){
        $fiscal_year= \App\CtrFiscalYear::first()->fiscal_year;
        if(count($request->particular)>0){
            for($i=0;$i<count($request->particular);$i++){
                $addaccounting = new \App\Accounting;
                $addaccounting->transaction_date=date('Y-m-d');
                $addaccounting->reference_id=$reference_id;
                $addaccounting->accounting_type=1;
                $addaccounting->category="Other Payment";
                $addaccounting->subsidiary=$request->particular[$i];
                $addaccounting->receipt_details=$request->particular[$i];
                $addaccounting->particular=$request->particular[$i];
                $addaccounting->accounting_code=$this->getParticularAccounting($request->particular[$i])->accounting_code;
                $addaccounting->accounting_name=$this->getParticularAccounting($request->particular[$i])->accounting_name;
                $addaccounting->fiscal_year=$fiscal_year;
                $addaccounting->credit=$request->other_amount[$i];
                $addaccounting->posted_by=Auth::user()->idno;
                $addaccounting->save();
            }
        }
        
    }
    
    function getParticularAccounting($subsidiary){
        return \App\OtherPayment::where('subsidiary',$subsidiary)->first();
    }
}
