<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use App\Http\Controllers\Cashier\StudentLedger;

class StudentReservation extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
     function reservation($idno){
        if(Auth::user()->accesslevel==env("CASHIER")){
        $user = \App\User::where('idno',$idno)->first();
        $reservations = \App\Reservation::where('idno',$idno)->where('reservation_type','1')->orderBy('transaction_date')->get();
        $deposits = \App\Reservation::where('idno',$idno)->where('reservation_type','2')->orderBy('transaction_date')->get();
        $receipt_no = StudentLedger::getreceipt();
        return view('cashier.reservation',compact('user','reservations','deposits','receipt_no'));
        } 
    }
    
     function postreservation(Request $request){
        DB::beginTransaction();
        $reference_id = uniqid();
        $this->postPayment($request,$reference_id);
        $this->postDeposit($request, $reference_id);
        $this->postAccounting($request, $reference_id);
        StudentLedger::updatereceipt();
        DB::commit();
        
        return redirect(url('/cashier',array('viewreceipt',$reference_id)));
    }
    
    function postAccounting($request, $reference_id){
        $fiscal_year= \App\CtrFiscalYear::first()->fiscal_year;
        if($request->reservation != ""){
        $addaccounting = new \App\Accounting;
        $addaccounting->transaction_date=date('Y-m-d');
        $addaccounting->reference_id=$reference_id;
        $addaccounting->accounting_type=1;
        $addaccounting->category="Reservation";
        $addaccounting->subsidiary=$request->idno;
        $addaccounting->receipt_details="Reservation";
        $addaccounting->particular="Reservation";
        $addaccounting->accounting_code="210400";
        $addaccounting->accounting_name="Student Reservation";
        $addaccounting->fiscal_year=$fiscal_year;
        $addaccounting->credit=$request->reservation;
        $addaccounting->posted_by=Auth::user()->idno;
        $addaccounting->save();
        }
        
        if($request->deposit != ""){
        $addaccounting = new \App\Accounting;
        $addaccounting->transaction_date=date('Y-m-d');
        $addaccounting->reference_id=$reference_id;
        $addaccounting->accounting_type=1;
        $addaccounting->category="Student Deposit";
        $addaccounting->subsidiary=$request->idno;
        $addaccounting->receipt_details="Student Deposit";
        $addaccounting->particular="Student Deposit";
        $addaccounting->accounting_code="210103";
        $addaccounting->accounting_name="OCL-Student Deposit";
        $addaccounting->fiscal_year=$fiscal_year;
        $addaccounting->credit=$request->deposit;
        $addaccounting->posted_by=Auth::user()->idno;
        $addaccounting->save();
        }
        
        $addaccounting = new \App\Accounting;
        $totalamount=0;
        if($request->cash_receive != ""){
            $totalamount=$totalamount+$request->cash_receive-$request->change;
        }
        if($request->check_amount != ""){
            $totalamount=$totalamount+$request->check_amount;
        }
        if($request->credit_card_amount != ""){
            $totalamount=$totalamount+$request->credit_card_amount;
        }
        if($request->deposit_amount != ""){
            $totalamount=$totalamount+$request->deposit_amount;
        }
        $addaccounting->transaction_date=date('Y-m-d');
        $addaccounting->reference_id=$reference_id;
        $addaccounting->accounting_type=1;
        $addaccounting->category="Cash";
        $addaccounting->subsidiary="None";
        $addaccounting->receipt_details="Cash";
        $addaccounting->particular="Cash";
        $addaccounting->accounting_code="110011";
        $addaccounting->accounting_name="BPI ACCT";
        $addaccounting->fiscal_year=$fiscal_year;
        $addaccounting->debit=$totalamount;
        $addaccounting->posted_by=Auth::user()->idno;
        $addaccounting->save();
    }
    
    function postDeposit($request,$reference_id){
        if($request->reservation != ""){
        $addreservation = new \App\Reservation;
        $addreservation->idno=$request->idno;
        $addreservation->reference_id=$reference_id;
        $addreservation->transaction_date=date('Y-m-d');
        $addreservation->amount=$request->reservation;
        $addreservation->reservation_type=1;
        $addreservation->posted_by=Auth::user()->idno;
        $addreservation->save();
        }
        if($request->deposit != ""){
        $addreservation = new \App\Reservation;
        $addreservation->idno=$request->idno;
        $addreservation->reference_id=$reference_id;
        $addreservation->transaction_date=date('Y-m-d');
        $addreservation->amount=$request->deposit;
        $addreservation->reservation_type=2;
        $addreservation->posted_by=Auth::user()->idno;
        $addreservation->save();
        }
    }
    function postPayment($request,$reference_id){
        $remarks="";
        $paidby = \App\User::where('idno',$request->idno)->first();
        $adddpayment = new \App\Payment;
        $adddpayment->transaction_date = date('Y-m-d');
        $adddpayment->receipt_no=  StudentLedger::getreceipt();
        $adddpayment->reference_id=$reference_id;
        $adddpayment->idno=$request->idno;
        $adddpayment->paid_by=$paidby->lastname .", " . $paidby->firstname;
        if($request->check_amount != ""){
            $adddpayment->check_number = $request->check_number;
            $adddpayment->bank_name = $request->bank;
            $adddpayment->check_amount = $request->check_amount;
        }
        if($request->cash_receive != ""){
            $adddpayment->amount_received = $request->cash_receive;
            $adddpayment->cash_amount = $request->cash_receive - $request->change;
        }
        if($request->credit_card_amount != ""){
            $adddpayment->credit_card_bank=$request->credit_card_bank;
            $adddpayment->credit_card_type=$request->credit_card_type;
            $adddpayment->credit_card_number=$request->card_number;
            $adddpayment->approval_number=$request->approval_number;
            $adddpayment->credit_card_amount=$request->credit_card_amount;
        }
        if($request->deposit_amount != ""){
            $adddpayment->deposit_reference=$request->deposit_reference;
            $adddpayment->deposit_amount=$request->deposit_amount;
        }
        if($request->reservation > 0){
            $remarks = $remarks." Reservation";
        }
        if($request->deposit > 0 ){
            $remarks= $remarks . " Student Deposit";
        }
        $adddpayment->remarks=$remarks; 
        $adddpayment->posted_by=Auth::user()->idno;
        $adddpayment->save();
    }
    
}
