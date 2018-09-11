<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade;
use App\Http\Controllers\Cashier\StudentLedger;
use App\Http\Controllers\Cashier\StudentReservation;
use DB;
use Mail;

class PreRegistration extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function pre_registration_payment() {
        if (Auth::user()->accesslevel == env("CASHIER")) {
            $receipt_number = StudentLedger::getreceipt();
            $particulars = \App\OtherPayment::get();
            $ending_receipt_number = StudentLedger::getending_receipt();
            
            $applicants = \Illuminate\Support\Facades\DB::connection('mysql2')->select('select * from pre_registrations where is_complete = 0');
            
            if ($receipt_number <= $ending_receipt_number) {
                return view("cashier.pre_registration.pre_registration_payment", compact('receipt_number', 'particulars', 'applicants'));
            } else {
                return "OR Used!";
            }
        }
    }
    
    function post_pre_registration_payment(Request $request){

        $db_ext = DB::connection('mysql2');
        $applicant_details = $db_ext->table('pre_registrations')->where('idno', $request->paid_by)->first();
        if(Auth::user()->accesslevel==env("CASHIER")){
            DB::beginTransaction();
                
            $reference_id = uniqid();
            
            //for bed and shs
            if($applicant_details->level!="1st Year" || $applicant_details->level!="2nd Year" || $applicant_details->level!="3rd Year" || $applicant_details->level!="4th Year" || $applicant_details->level!="5th Year"){
                if($applicant_details->level == "Grade 11" || $applicant_details->level == "Grade 12"){
                    $academic_type = "SHS";
                }else{
                    $academic_type = "BED";
                }
                $this->addUser($request,$reference_id, $applicant_details, $academic_type);
                $this->addBedProfile($request,$reference_id, $applicant_details, $academic_type);
                $this->addParent($request,$reference_id, $applicant_details, $academic_type);
                $this->addBEDStatus($request,$reference_id, $applicant_details, $academic_type);
                $this->addPromotions($request,$reference_id, $applicant_details, $academic_type);//not yet added in the old registration
            }else{
            //for college
                $academic_type = "College";
                $this->addUser($request,$reference_id, $applicant_details, $academic_type);
                //$this->addStudentInfo($request,$reference_id, $applicant_details, $academic_type);
                //$this->addHEDStatus($request,$reference_id, $applicant_details, $academic_type);
                //$this->addHEDAdmission($request,$reference_id, $applicant_details, $academic_type);
                //$this->addAdmission_heds($request,$reference_id, $applicant_details, $academic_type);
                //$this->addAdmission_hed_requirements($request,$reference_id, $applicant_details, $academic_type);
                //$this->addScholarship($request,$reference_id, $applicant_details, $academic_type);
            }
            
            $this->postPayment($request,$reference_id, $applicant_details);
            $this->postAccounting($request,$reference_id);
            StudentReservation::postCashDebit($request,$reference_id);
            StudentLedger::updatereceipt();
            
            $this->updatePreRegStatus($request);
            $this->sendPaymentEmail($request,$reference_id, $applicant_details);
            \App\Http\Controllers\Admin\Logs::log("Post Pre-Registration payment to - $request->paid_by.");
            DB::Commit();
            return redirect(url('/cashier',array('viewreceipt',$reference_id)));
        }
    }
    function updatePreRegStatus($request){
        $db_ext = DB::connection('mysql2');
        $applicant_details = $db_ext->table('pre_registrations')->where('idno', $request->paid_by)
                ->update([
                    'is_complete' => 1
                ]);
    }
    function sendPaymentEmail($request,$reference_id, $applicant_details){
        $data=array('name'=>$applicant_details->firstname." ".$applicant_details->lastname, 'email'=>$applicant_details->email);
        Mail::send('cashier.pre_registration.mail',compact('request','reference_id','applicant_details'), function($message) use($applicant_details) {
         $message->to($applicant_details->email, $applicant_details->firstname." ".$applicant_details->lastname)
                 ->subject('Assumption College Payment Confirmation');
         $message->from('support@assumption.edu.ph',"AC Treasurer's Office");
        });
    }
    
    function postAccounting($request,$reference_id){
        $fiscal_year= \App\CtrFiscalYear::first()->fiscal_year;
        $dept=\App\Status::where('idno',$request->paid_by)->first();
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
    function postPayment($request,$reference_id, $applicant_details){
        $remarks="";
        $adddpayment = new \App\Payment;
        $adddpayment->transaction_date = date('Y-m-d');
        $adddpayment->receipt_no=  StudentLedger::getreceipt();
        $adddpayment->reference_id=$reference_id;
        $adddpayment->idno=$request->paid_by;
        $adddpayment->paid_by= $applicant_details->lastname.", ".$applicant_details->firstname;
                
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
        
        if(isset($request->remark)){
            $remarks=$remarks . $request->remark;
        }
        
        $adddpayment->remarks=$remarks; 
        $adddpayment->posted_by=Auth::user()->idno;
        $adddpayment->save();
    }
    
    function addUser($request,$reference_id, $applicant_details, $academic_type){
        
        $add_new_user = new \App\User;
        $add_new_user->idno = $applicant_details->idno;
        $add_new_user->firstname = $applicant_details->firstname;
        $add_new_user->middlename = $applicant_details->middlename;
        $add_new_user->lastname = $applicant_details->lastname;
        $add_new_user->extensionname = $applicant_details->extensionname;
        $add_new_user->accesslevel = 0;
        $add_new_user->status = 1; //active or not
        $add_new_user->email = $applicant_details->email;
        $password = $applicant_details->idno;
        $add_new_user->password = bcrypt($password);
        $add_new_user->is_foreign = $applicant_details->is_foreign;
        $add_new_user->academic_type = $academic_type;
        $add_new_user->save();
        
    }
    function addBedProfile($request,$reference_id, $applicant_details, $academic_type){
        $addprofile = new \App\BedProfile;
        $addprofile->idno = $applicant_details->idno;
        $addprofile->date_of_birth = $applicant_details->date_of_birth;
        $addprofile->street = $applicant_details->street;
        $addprofile->barangay = $applicant_details->barangay;
        $addprofile->municipality = $applicant_details->municipality;
        $addprofile->province = $applicant_details->province;
        $addprofile->zip = $applicant_details->zip;
        $addprofile->tel_no = $applicant_details->tel_no;
        $addprofile->cell_no = $applicant_details->cell_no;
        $addprofile->save();
    }
    function addBEDStatus($request,$reference_id, $applicant_details, $academic_type){
        $addstatus = new \App\Status;
        $addstatus->idno = $applicant_details->idno;
        $addstatus->section = "";
        $addstatus->status = env("PRE_REGISTERED");
        $addstatus->academic_type = "BED";
        $addstatus->save();
    }
    function addPromotions($request,$reference_id, $applicant_details, $academic_type){
        $addpromotion = new \App\Promotion;
        $addpromotion->idno = $applicant_details->idno;
        $addpromotion->level = $applicant_details->level;
        $addpromotion->strand = $applicant_details->strand;
        $addpromotion->section = 1;
        $addpromotion->save();
    }
    function addParent($request,$reference_id, $applicant_details, $academic_type){
        $addpromotion = new \App\BedParentInfo;
        $addpromotion->idno = $applicant_details->idno;
        $addpromotion->save();
    }

}
