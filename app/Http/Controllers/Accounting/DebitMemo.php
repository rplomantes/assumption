<?php

namespace App\Http\Controllers\Accounting;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class DebitMemo extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
 function index($idno){
     if(Auth::user()->accesslevel==env("ACCTNG_STAFF")){
        $user = \App\User::where('idno',$idno)->first();
        $receipt_number=  $this->getReceipt();
        $total_other=0.00;
        
        //Other Fee Total
        $other_fee_total =  \App\Ledger::where('idno',$idno)->where('category_switch',env("OTHER_FEE"))
                ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                ->first();
        //Miscellaneous Fee Total
        $miscellaneous_fee_total =  \App\Ledger::where('idno',$idno)->where('category_switch',env("MISC_FEE"))
                ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                ->first();
        ///Depository Fee Total
        $depository_fee_total =  \App\Ledger::where('idno',$idno)->where('category_switch',env("DEPOSITORY_FEE"))
                ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                ->first();
        //Subject Related Fee Total
        $srf_total =  \App\Ledger::where('idno',$idno)->where('category_switch',env("SRF_FEE"))
                ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                ->first();
         
        //Tuion Fee Total
        $tuition_fee_total =  \App\Ledger::where('idno',$idno)->where('category_switch',env("TUITION_FEE"))
                ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                ->first();
       //Previous Balances
        $previous_total =  \App\Ledger::where('idno',$idno)->where('category_switch','>=','10')
                ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                ->first();
        //Other Fee
        $other_misc=  \App\Ledger::where('idno',$idno)->whereRaw('amount-discount-debit_memo-payment > 0 And category_switch=7')->get();
        
        if(count($other_misc)>0){
            foreach($other_misc as $om){
            $total_other=$total_other+$om->amount-$om->discount-$om->debit_memo-$om->payment;
            }        
        }
//      // Total Due Main
        $downpayment=  \App\LedgerDueDate::where('idno', $idno)->where('due_switch','0')->selectRaw('sum(amount) as amount')->first();
        $duetoday= \App\LedgerDueDate::where('idno', $idno)->where('due_date','<=',date('Y-m-d'))->where('due_switch','1')->selectRaw('sum(amount) as amount')->first();
        //Total Payment Main
        $payment = \App\Ledger::where('idno',$idno)->where('category_switch','<=','5')
                ->selectRaw('sum(debit_memo)+sum(payment)+sum(discount) as payment')->first();
        //
        if($downpayment->amount + $duetoday->amount -$payment->payment > 0){
        $due_total = $downpayment->amount + $duetoday->amount -$payment->payment;
        } else {
        $due_total=0;    
        }
        //reservation
        $reservation=  \App\Reservation::where('idno',$idno)->where('reservation_type','1')
                ->where('is_consumed','0')->selectRaw('sum(amount) as amount')->first();
        //Srudent Deposit
        $deposit =  \App\Reservation::where('idno',$idno)->where('reservation_type','2')
                ->where('is_consumed','0')->selectRaw('sum(amount) as amount')->first();
        
        return view('accounting.debit_memo',compact('user','other_fee_total','miscellaneous_fee_total','depository_fee_total','srf_total','tuition_fee_total','previous_total','other_misc','reservation','deposit','receipt_number','due_total'));
    
        }
 }   
    function getReceipt(){
        if(Auth::user()->accesslevel==env("ACCTNG_STAFF")){
            $id = \App\ReferenceId::where('idno',Auth::user()->idno)->first()->id;
            $number =  \App\ReferenceId::where('idno',Auth::user()->idno)->first()->dm_no;
            $receipt="";
            for($i=strlen($number);$i<=6;$i++){
                $receipt=$receipt."0";
            }
            return $id.$receipt.$number;
        }
        
    }
}
