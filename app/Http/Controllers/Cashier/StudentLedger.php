<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class StudentLedger extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function view($idno){
     if(Auth::user()->accesslevel==40){
      $user = \App\User::where('idno',$idno)->first();
      $totalmainpayment = 0;
      $ledger_main = \App\Ledger::groupBy(array('category','category_switch'))->where('idno',$idno)->where('category_switch','<=','6')
              ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->get();
     // if(count($ledger_main)>0){
     //    foreach($ledger_main as $payment){
     //        $totalmainpayment = $totalmainpayment + $payment->debit_memo + $payment->payment;
     //    } 
     // }
      $ledger_others = \App\Ledger::where('idno',$idno)->where('category_switch','7')->get();
      
      $previous=  \App\Ledger::where('idno',$idno)->where('category_switch','>',9)->
              whereRaw("amount-payment-debit_memo-discount > 0")->get();
      
      $status = \App\Status::where('idno',$idno)->first();
      
      $payments =  \App\Payment::where('idno',$idno)->where('is_current','1')->orderBy('transaction_date')->get();
      
      $debit_memos =  \App\DebitMemo::where('idno',$idno)->where('is_current','1')->orderBy('transaction_date')->get();
      
      $due_dates = \App\LedgerDueDate::where('idno',$idno)->orderBy('due_switch')->orderBy('due_date')->get();
      return view("cashier.ledger",compact('user','ledger_main','ledger_others','previous','status','payments',"debit_memos",'due_dates','totalmainpayment'));
     }       
    }
   
    function viewreceipt($reference_id){
        $payment= \App\Payment::where('reference_id',$reference_id)->first();
        $status=  \App\Status::where('idno',$payment->idno)->first();
        $accountings = \App\Accounting::where('reference_id',$reference_id)->get();
        $receipt_details = DB::Select("Select receipt_details, sum(credit) as credit from accountings where reference_id = "
                . "'$reference_id' and credit > '0' group by receipt_details, reference_id");
        $receipt_less = DB::Select("Select receipt_details, sum(debit) as debit from accountings where reference_id = "
                . "'$reference_id' and receipt_details != 'Cash' and debit > '0'  group by receipt_details, reference_id");
        return view('cashier.viewreceipt',compact('payment','status','accountings','receipt_details','receipt_less'));
    }
    
    public static function getreceipt(){
        if(Auth::user()->accesslevel==env("CASHIER")){
            $id = \App\ReferenceId::where('idno',Auth::user()->idno)->first()->id;
            $number =  \App\ReferenceId::where('idno',Auth::user()->idno)->first()->receipt_no;
            $receipt="";
            for($i=strlen($number);$i<=6;$i++){
                $receipt=$receipt."0";
            }
            return $id.$receipt.$number;
        }
    }
    public static function updatereceipt(){
        if(Auth::user()->accesslevel==env("CASHIER")){
           $update=\App\ReferenceId::where('idno',Auth::user()->idno)->first();
           $update->receipt_no = $update->receipt_no+1;
           $update->update();
        }
    }
}
