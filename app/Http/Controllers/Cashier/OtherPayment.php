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
        $ending_receipt_number=  StudentLedger::getending_receipt();
        $total_other=0.00;
        if($receipt_number<=$ending_receipt_number){
        return view('cashier.other_payment',compact('user','status','receipt_number','particulars'));
        }else{
                return view('cashier.ORUsed');
        }
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
        $dept=\App\Status::where('idno',$request->idno)->first();
        if(count($dept)>0){
        $department = $dept->department;
        } else {
        $department="None";    
        }
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
                $addaccounting->department = $department;
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
    function non_student_payment(){
        if(Auth::user()->accesslevel==env("CASHIER")){
            $receipt_number=  StudentLedger::getreceipt();
            $particulars = \App\OtherPayment::get();
        $ending_receipt_number=  StudentLedger::getending_receipt();
        $total_other=0.00;
        if($receipt_number<=$ending_receipt_number){
            return view("cashier.non_student_payment",compact('receipt_number','particulars'));
        }else{
            return "OR Used!";
        }
        }
    }
    function post_non_student_payment(Request $request){
        if(Auth::user()->accesslevel==env("CASHIER")){
            DB::beginTransaction();
            $reference_id = uniqid();
            $this->postPayment($request,$reference_id);
            $this->postAccounting($request, $reference_id);
            StudentReservation::postCashDebit($request, $reference_id);
            StudentLedger::updatereceipt();
            DB::Commit();
            return redirect(url('/cashier',array('viewreceipt',$reference_id)));
        }
    }
    function postPayment($request,$reference_id){
        $remarks="";
        $paidby = \App\User::where('idno',$request->idno)->first();
        $adddpayment = new \App\Payment;
        $adddpayment->transaction_date = date('Y-m-d');
        $adddpayment->receipt_no=  StudentLedger::getreceipt();
        $adddpayment->reference_id=$reference_id;
        $adddpayment->idno="999999";
        $adddpayment->paid_by=$request->paid_by;
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
        if(isset($request->reservation)){
        if($request->reservation > 0){
            $remarks = $remarks."Reservation - ";
        }}
        if(isset($request->deposit)){
        if($request->deposit > 0 ){
            $remarks= $remarks . "Student Deposit - ";
        }}
        
        if(isset($request->remark)){
            $remarks=$remarks . $request->remark;
        }
        
        $adddpayment->remarks=$remarks; 
        $adddpayment->posted_by=Auth::user()->idno;
        $adddpayment->save();
    }
}
