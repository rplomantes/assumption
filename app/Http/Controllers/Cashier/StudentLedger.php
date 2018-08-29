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
     if(Auth::user()->accesslevel==env("CASHIER")||Auth::user()->accesslevel==env("ACCTNG_STAFF")||Auth::user()->accesslevel==env("")){
      $due_others=0.00;
      $due_previous=0.00;
      $totalmainpayment = 0.00;
      $totalmaindue =0;
      $user = \App\User::where('idno',$idno)->first();
      
      $ledger_main = \App\Ledger::groupBy(array('category','category_switch'))->where('idno',$idno)->where('category_switch','<=','6')
              ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();
      
      $ledger = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno',$idno)
        ->where(function($query){
            $query->where('category_switch', 4)
                    ->orWhere('category_switch', 5);
            })->groupBy('category','category_switch')->orderBy('category_switch')->get();
      
      $ledger_main_tuition = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno',$idno)->where('category_switch', 6)->groupBy('category','category_switch')->orderBy('category_switch')->get();
$ledger_main_misc = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno',$idno)->where('category_switch', 1)->groupBy('category','category_switch')->orderBy('category_switch')->get();
$ledger_main_other = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno',$idno)->where('category_switch', 2)->groupBy('category','category_switch')->orderBy('category_switch')->get();
$ledger_main_depo = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno',$idno)->where('category_switch', 3)->groupBy('category','category_switch')->orderBy('category_switch')->get();
      
      
      if(count($ledger_main)>0){
         foreach($ledger_main as $payment){
             $totalmainpayment = $totalmainpayment + $payment->debit_memo + $payment->payment;
         } 
      }
      
      $downpayment=  \App\LedgerDueDate::where('idno', $idno)->where('due_switch','0')->selectRaw('sum(amount) as amount')->first();
      $duetoday= \App\LedgerDueDate::where('idno', $idno)->where('due_date','<=',date('Y-m-d'))->where('due_switch','1')->selectRaw('sum(amount) as amount')->first();
      
      $ledger_others = \App\Ledger::where('idno',$idno)->where('category_switch',env("OTHER_MISC"))->get();
      //$ledger_optional = \App\Ledger::where('idno',$idno)->where('category_switch',env("OPTIONAL_FEE"))->get();
      
      if(count($ledger_others)>0){
          foreach($ledger_others as $ledger_other){
              $due_others=$due_others + $ledger_other->amount - $ledger_other->discount - $ledger_other->debit_memo -$ledger_other->payment;
          }
      }
      
      $previous=  \App\Ledger::groupBy(array('category','category_switch'))->where('idno',$idno)->where('category_switch','>','9')
              ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();
      
      if(count($previous)>0){
          foreach($previous as $prev){
              $due_previous = $due_previous + $prev->amount - $prev->discount -$prev->debit_memo - $prev->payment;
          }
      }
      $totalmaindue=$downpayment->amount + $duetoday->amount - $totalmainpayment;
      if($totalmaindue<0){
          $totalmaindue=0;
      }
      $totaldue=  $totalmaindue+$due_others + $due_previous;
      $status = \App\Status::where('idno',$idno)->first();
      if($status->academic_type=="BED"){
      $levels = \App\Status::where('idno',$idno)->where('school_year',$status->school_year)->where('period',$status->period)->first();   
      } else if($status->academic_type=="SHS") {
      $levels = \App\Status::where('idno',$idno)->where('school_year',$status->school_year)->where('period',$status->period)->first();   
      } else if($status->academic_type=="College"){
      $levels=  \App\Status::where('idno',$idno)->where('school_year',$status->school_year)->where('period',$status->period)->first();    
      }
      
      $payments =  \App\Payment::where('idno',$idno)->where('is_current','1')->orderBy('transaction_date')->get();
      
      $debit_memos =  \App\DebitMemo::where('idno',$idno)->where('is_current','1')->orderBy('transaction_date')->get();
      
      $due_dates = \App\LedgerDueDate::where('idno',$idno)->orderBy('due_switch')->orderBy('due_date')->get();
      return view("cashier.ledger",compact('levels','user','ledger_main','ledger','ledger_main_tuition','ledger_main_misc','ledger_main_other','ledger_main_depo','ledger_others','ledger_optional','previous','status','payments',"debit_memos",'due_dates','totalmainpayment','totaldue'));
      //return $levels;
      }       
    }
   
    function viewreceipt($reference_id){
        $payment= \App\Payment::where('reference_id',$reference_id)->first();
        $status=  \App\Status::where('idno',$payment->idno)->first();
        $accountings = \App\Accounting::where('reference_id',$reference_id)->where('accounting_type','1')->get();
        $receipt_details = DB::Select("Select receipt_details, sum(credit) as credit from accountings where reference_id = "
                . "'$reference_id' and credit > '0' and accounting_type = '1' group by receipt_details, reference_id");
        $receipt_less = DB::Select("Select receipt_details, sum(debit) as debit from accountings where reference_id = "
                . "'$reference_id' and receipt_details != 'Cash' and debit > '0' and accounting_type='1'  group by receipt_details, reference_id");
        return view('cashier.viewreceipt',compact('payment','status','accountings','receipt_details','receipt_less'));
    }
    
    public static function getreceipt(){
        if(Auth::user()->accesslevel==env("CASHIER")){
            $id = \App\ReferenceId::where('idno',Auth::user()->idno)->first()->id;
            $number =  \App\ReferenceId::where('idno',Auth::user()->idno)->first()->receipt_no;
            $receipt="";
            for($i=strlen($number);$i<=9;$i++){
                $receipt=$receipt."0";
            }
            return $receipt.$number;
        }
    }
    public static function getending_receipt(){
        if(Auth::user()->accesslevel==env("CASHIER")){
            $id = \App\ReferenceId::where('idno',Auth::user()->idno)->first()->id;
            $number =  \App\ReferenceId::where('idno',Auth::user()->idno)->first()->end_receipt_no;
            $receipt="";
            for($i=strlen($number);$i<=9;$i++){
                $receipt=$receipt."0";
            }
            return $receipt.$number;
        }
    }
    public static function updatereceipt(){
        if(Auth::user()->accesslevel==env("CASHIER")){
           $update=\App\ReferenceId::where('idno',Auth::user()->idno)->first();
           $update->receipt_no = $update->receipt_no+1;
           $update->update();
        }
    }
    function reverserestore($reference_id){
        if(Auth::user()->accesslevel==env("CASHIER")||Auth::user()->accesslevel == env('ACCTNG_STAFF')||Auth::user()->accesslevel == env('ACCTNG_HEAD')){
            DB::beginTransaction();
            //$this->checkifreservation($reference_id);
            $this->reverserestore_ledger($reference_id, env("CASH"));
            $this->reverserestore_entries(\App\Payment::where('reference_id',$reference_id)->get(), $reference_id);
            $this->reverserestore_entries(\App\Accounting::where('reference_id',$reference_id)->get(), $reference_id);
            $this->reverserestore_entries(\App\Reservation::where('reference_id',$reference_id)->get(), $reference_id);
            \App\Http\Controllers\Admin\Logs::log("Reverse/Restore receipt with reference no: $reference_id.");
            DB::commit();
            return redirect(url('/cashier',array('viewreceipt',$reference_id)));
        }
    }
    
    function reverserestore_ledger($reference_id,$entry_type) {
        $accountings = \App\Accounting::where('reference_id',$reference_id)->where('credit','>','0')->where('accounting_type',$entry_type)->get();
           if(count($accountings)>0){
               foreach($accountings as $accounting){
                   $ledger=  \App\Ledger::find($accounting->reference_number);
                   if (count($ledger)>0){
                   if($accounting->is_reverse==0){
                   $ledger->payment = $ledger->payment - $accounting->credit;
                   }else{
                   $ledger->payment = $ledger->payment + $accounting->credit;          
                   }
                   $ledger->update(); 
                   }
               }
               }
           }
         
     function reverserestore_entries($obj,$reference_id){
         if(count($obj)>0){
             foreach ($obj as $ob){
                 if($ob->is_reverse=="0"){
                     $ob->is_reverse="1";
                 }else{
                     $ob->is_reverse="0";
                 }
                 $ob->update();
             }
         }
             
     }
     
    
    
}
