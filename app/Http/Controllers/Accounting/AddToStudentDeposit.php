<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class AddToStudentDeposit extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function add_to_student_deposit($idno) {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD")) {
            $user = \App\User::where('idno', $idno)->first();
            $receipt_no = $this->getreceipt();
            $ending_receipt_number = \App\Http\Controllers\Cashier\StudentLedger::getending_receipt();
            $total_other = 0.00;
                return view('accounting.add_to_student_deposit', compact('user', 'receipt_no'));
        }
    }
    
    function post_add_to_student_deposit(Request $request) {
       // return $request;
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD")) {
            DB::beginTransaction();
            $reference_id = uniqid();
            $this->postAccounting($request, $reference_id);
            $this->postDebit($request, $reference_id);
            $this->postStudentDeposit($request, $reference_id);
            $this->postSD($request, $reference_id);
            \App\Http\Controllers\Admin\Logs::log("Place Add to Student Deposit to - $request->idno with reference id:$reference_id.");
            DB::commit();
            $this->updateSD($request);
            return redirect(url('/accounting',array('view_add_to_student_deposit',$reference_id)));
        }
    }
    function updateSD($request){
         $dm=  \App\ReferenceId::where('idno',Auth::user()->idno)->first();
         $dm->sd_no = $dm->sd_no + 1;
         $dm->update();
     }
    
    function postSD($request, $reference_id){
        $adddm = new \App\AddToStudentDeposit;
        $adddm->idno=$request->idno;
        $adddm->transaction_date=date('Y-m-d');
        $adddm->reference_id=$reference_id;
        $adddm->sd_no=$this->getReceipt();
        $adddm->explanation=$request->remark;
        $adddm->amount=$request->deposit;
        $adddm->posted_by=Auth::user()->idno;
        $adddm->school_year = \App\Status::where('idno', $request->idno)->first()->school_year;
        $adddm->period = \App\Status::where('idno', $request->idno)->first()->period;
        $adddm->save();
    }
    
    function getReceipt(){
        if(Auth::user()->accesslevel==env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD")){
            $id = \App\ReferenceId::where('idno',Auth::user()->idno)->first()->id;
            $number =  \App\ReferenceId::where('idno',Auth::user()->idno)->first()->sd_no;
            $receipt="";
            for($i=strlen($number);$i<=6;$i++){
                $receipt=$receipt."0";
            }
            return $id.$receipt.$number;
        }
        
    }
    
    function postAccounting($request,$reference_id){
        $fiscal_year= \App\CtrFiscalYear::first()->fiscal_year;
        $dept=  \App\CtrAcademicProgram::where('level',$request->level)->first();
        if(count($dept)>0){
        $department = $dept->department;
        } else {
        $department="None";    
        }
        if($request->deposit != ""){
        $addaccounting = new \App\Accounting;
        $addaccounting->transaction_date=date('Y-m-d');
        $addaccounting->reference_id=$reference_id;
        $addaccounting->accounting_type= env("STUDENT_DEPOSIT");
        $addaccounting->category="Student Deposit";
        $addaccounting->subsidiary=$request->idno;
        $addaccounting->receipt_details="Student Deposit";
        $addaccounting->particular="Student Deposit";
        $addaccounting->department=$department;
        $addaccounting->accounting_code=env("STUDENT_DEPOSIT_CODE");
        $addaccounting->accounting_name=env("STUDENT_DEPOSIT_NAME");
        $addaccounting->fiscal_year=$fiscal_year;
        $addaccounting->credit=$request->deposit;
        $addaccounting->posted_by=Auth::user()->idno;
        $addaccounting->save();
        }
    }
    function postDebit($request, $reference_id){
        $accounting = $request->accounting;
        $debit_amount = $request->debit_amount;
        $debit_particular = $request->debit_particular;
        $department=  \App\Status::where('idno',$request->idno)->first()->department;
        $fiscal_year=  \App\CtrFiscalYear::first()->fiscal_year;
        for($i=0;$i<count($accounting);$i++){
            $addacct= new \App\Accounting;
            $addacct->transaction_date = date('Y-m-d');
            $addacct->reference_id=$reference_id;
            $addacct->accounting_type = env("STUDENT_DEPOSIT");
            $addacct->category=$this->getAccountingName($accounting[$i]);
            $addacct->subsidiary=$debit_particular[$i];
            $addacct->receipt_details=$this->getAccountingName($accounting[$i]);
            $addacct->particular=$request->remark;
            $addacct->accounting_code=$accounting[$i];
            $addacct->department=$department;
            $addacct->accounting_name=$this->getAccountingName($accounting[$i]);
            $addacct->fiscal_year=$fiscal_year;
            $addacct->debit=$debit_amount[$i];
            $addacct->posted_by=Auth::user()->idno;
            $addacct->save();
        }
    }
    function postStudentDeposit($request,$reference_id){
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
    function getAccountingName($accounting_code){
         $acctname = \App\ChartOfAccount::where('accounting_code',$accounting_code)->first();
         return $acctname->accounting_name;
     }
     
     function view_add_to_student_deposit($reference_id){
         $student_deposit = \App\AddToStudentDeposit::where('reference_id',$reference_id)->first();
         $accountings =  \App\Accounting::selectRaw("accounting_code, accounting_name, subsidiary,sum(debit) as debit, sum(credit) as credit")
                 ->where('reference_id',$reference_id)->groupBy('accounting_name','accounting_code','subsidiary')->where('accounting_type', '6')->get();
         $user = \App\User::where('idno',$student_deposit->idno)->first();
         $status=  \App\Status::where('idno',$student_deposit->idno)->first();
         return view('accounting.view_student_deposit',compact('student_deposit','accountings','user','status'));
     }

}
