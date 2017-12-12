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
      $due_others=0.00;
      $due_previous=0.00;
      $totalmainpayment = 0.00;
      $user = \App\User::where('idno',$idno)->first();
      
      $ledger_main = \App\Ledger::groupBy(array('category','category_switch'))->where('idno',$idno)->where('category_switch','<=','6')
              ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();
      
      if(count($ledger_main)>0){
         foreach($ledger_main as $payment){
             $totalmainpayment = $totalmainpayment + $payment->debit_memo + $payment->payment +$payment->discount;
         } 
      }
      
      $downpayment=  \App\LedgerDueDate::where('idno',$idno)->where('due_switch','0')->selectRaw('sum(amount) as amount')->first();
      $duetoday= \App\LedgerDueDate::where('idno',$idno)->where('due_date','<=',date('Y-m-d'))->where('due_switch','1')->selectRaw('sum(amount) as amount')->first();
      
      $ledger_others = \App\Ledger::where('idno',$idno)->where('category_switch','7')->get();
      if(count($ledger_others)>0){
          foreach($ledger_others as $ledger_other){
              $due_others=$due_others + $ledger_other->amount - $ledger_other->discount - $ledger_other->debit_memo -$ledger_other->payment;
          }
      }
      
      $previous=  \App\Ledger::where('idno',$idno)->where('category_switch','>','9')->
              whereRaw("amount-payment-debit_memo-discount > 0")->get();
      
      if(count($previous)>0){
          foreach($previous as $prev){
              $due_previous = $due_previous + $prev->amount - $prev->discount -$prev->debit_memo - $prev->payment;
          }
      }
      
      $totaldue=$downpayment->amount + $duetoday->amount - $totalmainpayment +$due_others + $due_previous;
      $status = \App\Status::where('idno',$idno)->first();
      
      $payments =  \App\Payment::where('idno',$idno)->where('is_current','1')->orderBy('transaction_date')->get();
      
      $debit_memos =  \App\DebitMemo::where('idno',$idno)->where('is_current','1')->orderBy('transaction_date')->get();
      
      $due_dates = \App\LedgerDueDate::where('idno',$idno)->orderBy('due_switch')->orderBy('due_date')->get();
      return view("cashier.ledger",compact('user','ledger_main','ledger_others','previous','status','payments',"debit_memos",'due_dates','totalmainpayment','totaldue'));
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
