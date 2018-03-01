<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use App\Http\Controllers\Cashier\StudentLedger;
use App\Http\Controllers\Cashier\StudentReservation;

class MainPayment extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function main_payment($idno){
        if(Auth::user()->accesslevel==env("CASHIER")){
        $user = \App\User::where('idno',$idno)->first();
        $receipt_number=  StudentLedger::getreceipt();
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
       //Optional Fee Total
        $optional_fee_total =  \App\Ledger::where('idno',$idno)->where('category_switch',env("OPTIONAL_FEE"))
                ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                ->first();
       //Previous Balances
        $previous_total =  \App\Ledger::where('idno',$idno)->where('category_switch','>=','10')
                ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                ->first();
        //Other Fee
        $other_misc=  \App\Ledger::where('idno',$idno)->whereRaw('amount-discount-debit_memo-payment > 0 And (category_switch=7)')->get();
        
        if(count($other_misc)>0){
            foreach($other_misc as $om){
            $total_other=$total_other+$om->amount-$om->discount-$om->debit_memo-$om->payment;
            }        
        }
//      // Total Due Main
        $downpayment=  \App\LedgerDueDate::where('idno', $idno)->where('due_switch','0')->selectRaw('sum(amount) as amount')->first();
        $duetoday= \App\LedgerDueDate::where('idno', $idno)->where('due_date','<=',date('Y-m-d'))->where('due_switch','1')->selectRaw('sum(amount) as amount')->first();
        //Total Payment Main
        $payment = \App\Ledger::where('idno',$idno)->where('category_switch','<=','6')
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
        
        return view('cashier.main_payment',compact('user','other_fee_total','miscellaneous_fee_total','depository_fee_total','srf_total','tuition_fee_total','previous_total','other_misc','reservation','deposit','receipt_number','due_total','optional_fee_total'));
    
        }
    }
    
    function post_main_payment(Request $request){
        if(Auth::user()->accesslevel==env("CASHIER")){    
        DB::beginTransaction();
        $reference_id = uniqid();
        $this->checkStatus($request,$reference_id);
        StudentReservation::postPayment($request,$reference_id);
        $this->postAccounting($request, $reference_id);
        StudentReservation::postCashDebit($request, $reference_id);
        StudentLedger::updatereceipt();
        DB::commit();
        return redirect(url('/cashier',array('viewreceipt',$reference_id)));
        }
        //return $request;
    }
    
    function checkStatus($request,$reference_id){
         if($request->main_due > "0"){
            $status = \App\Status::where('idno',$request->idno)->first();
                if($status->status==env("ASSESSED")){
                    $this->addUnrealizedEntry($request,$reference_id);
                    $this->changeStatus($request->idno);
                    $this->addLevels($request->idno);
                    //$this->notifyStudent($request, $reference_id);
                }
         }
     }
     
     public static function addUnrealizedEntry($request,$reference_id){
         $totaltuition=  \App\Ledger::where('idno',$request->idno)->where('category_switch',env("TUITION_FEE"))
                 ->selectRaw("sum(amount) as amount")->first();
         $fiscal_year=  \App\CtrFiscalYear::first()->fiscal_year;
         $department=  \App\Status::where('idno',$request->idno)->first()->department;
        //add debit tuition fee ar
         $addacct = new \App\Accounting;
         $addacct->transaction_date = date('Y-m-d');
         $addacct->reference_id=$reference_id;
         $addacct->accounting_type = env("COMPUTER");
         $addacct->category=env("AR_TUITION_NAME");
         $addacct->subsidiary=$request->idno;;
         $addacct->receipt_details=env("AR_TUITION_NAME");
         $addacct->particular="Unrealized Tiution Fee For " . $request->idno;
         $addacct->accounting_code=env("AR_TUITION_CODE");
         $addacct->department = $department;
         $addacct->accounting_name=env("AR_TUITION_NAME");
         $addacct->fiscal_year=$fiscal_year;
         $addacct->debit=$totaltuition->amount; 
         $addacct->posted_by = Auth()->user()->idno;
         $addacct->save();
        
        //add credit unearned
         $addacct = new \App\Accounting;
         $addacct->transaction_date = date('Y-m-d');
         $addacct->reference_id=$reference_id;
         $addacct->accounting_type = env("COMPUTER");
         $addacct->category=env("UNEARNED_NAME");
         $addacct->subsidiary=$request->idno;
         $addacct->receipt_details=env("UNEARNED_NAME");
         $addacct->particular="Unrealized Tiution Fee For " . $request->idno;
         $addacct->accounting_code=env("UNEARNED_CODE");
         $addacct->accounting_name=env("UNEARNED_NAME");
         $addacct->department = $department;
         $addacct->fiscal_year=$fiscal_year;
         $addacct->credit=$totaltuition->amount;
         $addacct->posted_by = Auth()->user()->idno;
         $addacct->save();
        
         
     }
     public static function changeStatus($idno){
         $change = \App\Status::where('idno',$idno)->first();
         $change->status=env("ENROLLED");
         $change->date_enrolled=date('Y-m-d');
         $change->update();
     }
     
     function notifyStudent($request, $reference_id){
         
     }
     
    function postAccounting($request, $reference_id){
        
        if($request->main_due > 0 ){
           $totalpayment = $request->main_due;
           $ledgers = \App\Ledger::where('idno',$request->idno)->where("category_switch",'<=','6')->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get(); 
           $this->processAccounting($request, $reference_id,$totalpayment,$ledgers,env("CASH"));
        }
        
        if($request->previous_balance > 0){
           $totalpayment = $request->previous_balance;
           $ledgers = \App\Ledger::where('idno',$request->idno)->where("category_switch",'>=','10')->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get(); 
           $this->processAccounting($request, $reference_id,$totalpayment,$ledgers,env("CASH"));
        }
        
       if(count($request->other_misc)>0){
           foreach($request->other_misc as $key => $totalpayment){
               $ledgers =  \App\Ledger::where('id',$key)->get();
               $this->processAccounting($request, $reference_id,$totalpayment,$ledgers,env("CASH"));
           }
       }
        
    }
  
    function processDiscount($request,$reference_id,$discount,$discount_code,$accounting_type){
        $discount_ref = \App\CtrDiscount::where('discount_code',$discount_code)->first();
        $department=  \App\Status::where('idno',$request->idno)->first()->department;
        $addacct = new \App\Accounting;
                    $addacct->transaction_date = date('Y-m-d');
                    $addacct->reference_id=$reference_id;
                    $addacct->accounting_type = $accounting_type;
                    $addacct->category=$discount_ref->discount_description;
                    $addacct->subsidiary=$discount_ref->discount_description;
                    $addacct->receipt_details=$discount_ref->discount_description;
                    $addacct->particular=$discount_ref->discount_description;
                    $addacct->accounting_code=$discount_ref->accounting_code;
                    $addacct->department=$department;
                    $addacct->accounting_name=$discount_ref->sccounting_name;
                    $addacct->fiscal_year=$fiscal_year;
                    $addacct->debit=$discount;
                    $addacct->posted_by=Auth::user()->idno;
                    $addacct->save();        
        
    }

    public static function processAccounting($request, $reference_id,$totalpayment,$ledgers,$accounting_type){
        $fiscal_year=  \App\CtrFiscalYear::first()->fiscal_year;
            if(count($ledgers)>0){
                foreach($ledgers as $ledger){
                    if($totalpayment>0){
                        //process if there is discount
                        if($ledger->debit_memo==0 && $ledger->payment==0){
                            if($ledger->discount>0){
                            $this->processDiscount($request, $reference_id, $ledger->discount, $ledger->discount_code);
                            $addacct = new \App\Accounting;
                            $addacct->transaction_date = date('Y-m-d');
                            $addacct->reference_id=$reference_id;
                            $addacct->accounting_type = $accounting_type;
                            $addacct->category=$ledger->category;
                            $addacct->subsidiary=$ledger->subsidiary;
                            $addacct->receipt_details=$ledger->receipt_details;
                            $addacct->particular=$ledger->receipt_details;
                            $addacct->accounting_code=$ledger->accounting_code;
                            $addacct->accounting_name=$ledger->accounting_name;
                            $addacct->department=$ledger->department;
                            $addacct->fiscal_year=$fiscal_year;
                            $addacct->credit=$ledger->discount;
                            $addacct->posted_by=Auth::user()->idno;
                            $addacct->save();
                            
                            }
                            
                        } 
                    if($totalpayment >= $ledger->amount-$ledger->discount-$ledger->debit_memo-$ledger->payment){
                    $amount = $ledger->amount-$ledger->discount-$ledger->debit_memo-$ledger->payment;
                    if($accounting_type==env("DEBIT_MEMO")){
                    $ledger->debit_memo=$ledger->debit_memo+$amount; 
                    }else{
                    $ledger->payment=$ledger->payment+$amount;
                    }
                    $ledger->update();
                    
                    $addacct = new \App\Accounting;
                    $addacct->transaction_date = date('Y-m-d');
                    $addacct->reference_id=$reference_id;
                    $addacct->reference_number=$ledger->id;
                    $addacct->accounting_type = $accounting_type;
                    $addacct->category=$ledger->category;
                    $addacct->subsidiary=$ledger->subsidiary;
                    $addacct->receipt_details=$ledger->receipt_details;
                    $addacct->particular=$ledger->receipt_details;
                    $addacct->accounting_code=$ledger->accounting_code;
                    $addacct->accounting_name=$ledger->accounting_name;
                    $addacct->department=$ledger->department;
                    $addacct->fiscal_year=$fiscal_year;
                    $addacct->credit=$amount;
                    $addacct->posted_by=Auth::user()->idno;
                    $addacct->save();
                    $totalpayment=$totalpayment-$amount;
                    
                    } else {
                    if($totalpayment>0){
                    if($accounting_type==env("DEBIT_MEMO")){
                    $ledger->debit_memo=$ledger->debit_memo + $totalpayment;    
                    }else{    
                    $ledger->payment=$ledger->payment + $totalpayment;
                    }
                    $ledger->update();
                    $addacct = new \App\Accounting;
                    $addacct->transaction_date = date('Y-m-d');
                    $addacct->reference_id=$reference_id;
                    $addacct->reference_number=$ledger->id;
                    $addacct->accounting_type = $accounting_type;
                    $addacct->category=$ledger->category;
                    $addacct->subsidiary=$ledger->subsidiary;
                    $addacct->receipt_details=$ledger->receipt_details;
                    $addacct->particular=$ledger->receipt_details;
                    $addacct->accounting_code=$ledger->accounting_code;
                    $addacct->accounting_name=$ledger->accounting_name;
                    $addacct->fiscal_year=$fiscal_year;
                    $addacct->credit=$totalpayment;
                    $addacct->posted_by=Auth::user()->idno;
                    $addacct->save();
                    $totalpayment=0;
                     }    
                    }  
                    }
                }   
            }
    }
    function addLevels($idno){
        
    }
}
