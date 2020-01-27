<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Cashier\MainPayment;
use App\Http\Controllers\Accounting\OverDebitMemo;
use Illuminate\Support\Facades\Input;

class Overpayment_1 extends Controller
{
    public function __construct() {
        $this->middleware("auth");
    }
    
    public static function process_overpayment($idno){
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD")) {
            $amount_to_fix = 0.00;
            $reference_id = uniqid();
            
            $ledgers = \App\Ledger::where("idno", $idno)->whereRaw("payment > 0")->whereRaw("amount - debit_memo - discount - payment < 0 ")->get();
            $ledgers = \App\Ledger::where('idno', $idno)->whereRaw("discount + debit_memo + payment > amount")->get();
            if(!$ledgers->isEmpty()){
                DB::beginTransaction();
                $amount_to_remove = 0;
                
                foreach($ledgers as $ledger){
                    
                    //Total amount to be moved
                    $amount_to_fix = round($amount_to_fix + $ledger->amount - ($ledger->debit_memo + $ledger->discount + $ledger->payment),2); 
                    
                    
                    $amount_to_remove = $amount_to_remove;
                    self::fixLedger($ledger->id, $amount_to_fix);
                }
                
                $amount_to_fix = abs($amount_to_fix);
                $amount_to_remove = abs($amount_to_remove);
                self::checkBalances($idno, $amount_to_fix,$reference_id);
                self::addOverpaymentMemo($idno, $amount_to_fix);
                self::updateOM();
                
                \App\Http\Controllers\Admin\Logs::log("Apply Overpayment for $idno.Ref No. $reference_id.");
                DB::commit();
                return redirect(url("/cashier/viewledger/2019/$idno"));
            }
        }
    }
    
    public static function checkBalances($idno, $amount_to_fix,$reference_id){
        $ledgers = \App\Ledger::where("idno", $idno)
                 ->whereRaw("amount - (payment + debit_memo + discount) > 0 ")
                 ->orderBy("category_switch","DESC")
                 ->get();
        $ba = ""; 
        if(!$ledgers->isEmpty()){
            $totalamount = $amount_to_fix;
            foreach($ledgers as $ledger){
                $balance = abs($ledger->amount - ($ledger->debit_memo + $ledger->discount + $ledger->payment));
                if($totalamount >= $balance){
                    $totalamount = $totalamount - $balance;
                }else{
                    if($totalamount > 0){
                        $totalamount = 0;
                    }
                }
            }
            self::processAccounting($idno, $reference_id, $amount_to_fix, $ledgers, env("CASH"));
            if($totalamount > 0){
                self::addStudentDeposit($idno, $totalamount,$reference_id);
            } 
        }else{
            self::addStudentDeposit($idno, $amount_to_fix,$reference_id);
        }
    }
    
    public static function addOverpaymentMemo($idno, $amount_to_fix){
        
        $adddm = new \App\OverpaymentMemo;
        $adddm->idno = $idno;
        $adddm->transaction_date = date('Y-m-d');
        $adddm->op_no = self::getReceipt();
        $adddm->amount = $amount_to_fix;
        $adddm->posted_by = Auth::user()->idno;
        $adddm->school_year = \App\Status::where('idno', $idno)->first()->school_year;
        $per = \App\Status::where('idno', $idno)->first()->period;
        if($per == null){
            $adddm->period = "";
        }else{
            $adddm->period = $per;
        }
        $adddm->save();
    }
    
    public static function updateOM(){
         $om = \App\ReferenceId::where('idno',"accounting")->first();
         $om->op_no = $om->op_no + 1;
         $om->update();
     }
    
    public static function getReceipt(){
            $id = \App\ReferenceId::where('idno',"accounting")->first()->id;
            $number =  \App\ReferenceId::where('idno',"accounting")->first()->op_no;
            $receipt="";
            for($i=strlen($number);$i<=6;$i++){
                $receipt=$receipt."0";
            }
            return $id.$receipt.$number;
        
    }
    
    public static function fixLedger($ledger_id, $amount_to_fix){
        $ledger = \App\Ledger::find($ledger_id);
        $less = round($ledger->amount - ($ledger->payment + $ledger->discount + $ledger->debit_memo),2);
        
        $ledger->payment = $ledger->payment - abs($less);
        $ledger->update();
        return abs($less);
    }
    
    public static function processAccounting($idno, $reference_id, $totalpayment, $ledgers, $accounting_type) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $date = date('Y-m-d');
        
        if (!$ledgers->isEmpty()) {
            foreach ($ledgers as $ledger) {
                //IF ($totalpayment > 0)
                if ($totalpayment > 0) {                    
                    if ($totalpayment >= ($ledger->amount - ( $ledger->discount + $ledger->debit_memo + $ledger->payment))) {
                        
                        $amount = round($ledger->amount - $ledger->discount - $ledger->debit_memo - $ledger->payment,2);
                        
                        if ($accounting_type == env("DEBIT_MEMO")) {
                            $ledger->debit_memo = $ledger->debit_memo + $amount;
                        } else {
                            $ledger->payment = $ledger->payment + $amount;
                        }
                        $ledger->update();
                        $totalpayment = $totalpayment - $amount;
                        
                    } else {
                        
                        if ($totalpayment > 0) {
                            if ($accounting_type == env("DEBIT_MEMO")) {
                                $ledger->debit_memo = $ledger->debit_memo + $totalpayment;
                            } else {
                                $ledger->payment = $ledger->payment + $totalpayment;
                            }
                            $ledger->update();
                            $totalpayment = 0;
                            
                        }
                    }
                }
            }
        }
    }
    
    public static function addStudentDeposit($idno, $amount_to_fix,$reference_id){
        if($amount_to_fix > 0){
            $addreservation = new \App\Reservation;
            $addreservation->idno=$idno;
            $addreservation->reference_id="";
            $addreservation->transaction_date=date('Y-m-d');
            $addreservation->amount=$amount_to_fix;
            $addreservation->reservation_type=2;
            $addreservation->posted_by=Auth::user()->idno;
            $addreservation->save();
        }
    }
}
