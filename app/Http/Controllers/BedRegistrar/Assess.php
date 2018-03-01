<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class Assess extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function assess($idno){
        if(Auth::user()->accesslevel==env("REG_BE")){
            $user=  \App\User::where('idno',$idno)->first();
            if($user->academic_type=="BED"){
                $school_year = \App\CtrAcademicSchoolYear::where('academic_type','BED')->first()->school_year;
                $status = \App\Status::where('idno',$idno)->first();
                $ledgers = \App\Ledger::where('idno',$idno)->where('category_switch','<=','6')->get();
                $level = \App\BedLevel::where('idno',$idno)->where('school_year',$school_year)->first();
                if(count($status)>0){
                    if($status->status < env("ASSESSED")){
                    return view('reg_be.assess',compact('user','status','ledgers','level'));    
                    } else {
                    return view('reg_be.assessed_enrolled',  compact('idno'));
                    }
                } 
                
                }
                    
            }
        }
    
    
    function post_assess(Request $request){
        $validation = $this->validate($request,[
                'plan' => 'required',
            ]);
        if($validation){
        if(Auth::user()->accesslevel == env("REG_BE")){
            $user = \App\User::where("idno",$request->idno)->first();
                if($user->academic_type == "BED"){
                    $status = \App\Status::where('idno',$request->idno)->first();
                    if($status->status == 0 ){
                        $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type',"BED")->first();
                        DB::beginTransaction();
                        $this->addGrades($request, $schoolyear->school_year);
                        $this->addLedger($request, $schoolyear->school_year); 
                        $this->addOptionalFee($request);
                        $this->addSRF($request,$schoolyear->school_year);
                        $this->addDueDates($request,$schoolyear->school_year);
                        $this->checkReservations($request, $schoolyear->school_year);
                        $this->modifyStatus($request);
                        //$this->addBooks($request,$schoolyear);
                        DB::commit();
                        return redirect(url('/bedregistrar',array('assess',$request->idno)));
                        //return view(url('begregistrar',array('viewregistration',$request->idno)));
                    }
                    else if($status->status >= env('ASSESSED')){
                        return view(url('begregistrar',array('viewregistration',$request->idno)));
                    }
                else{
                    view('unauthorized');
                }    
            }  
            
        }}
    }
    function changeStatus($id){
        
    }
    function addGrades($request, $schoolyear){
        if($request->level=="Grade 11" || $request->level=="Grade 12"){
        $subjects = \App\BedCurriculum::where('level',$request->level)->where('strand',$request->strand)->get();
        } else {
        $subjects = \App\BedCurriculum::where('level',$request->level)->get();    
        }
       if(count($subjects)>0){
            foreach($subjects as $subject){
                $addsubject = new \App\GradeBasicEd;
                $addsubject->idno = $request->idno;
                $addsubject->school_year = $schoolyear;
                $addsubject->strand = $subject->strand;
                $addsubject->level = $request->level;
                $addsubject->subject_code = $subject->subject_code;
                $addsubject->subject_name = $subject->subject_name;
                $addsubject->group_name = $subject->group_name;
                $addsubject->units = $subject->units;
                $addsubject->display_subject_code = $subject->display_subject_code;
                $addsubject->weighted = $subject->weighted;
                $addsubject->encoded_by = Auth::user()->idno;
                $addsubject->save();
            }
                    
        }
        
    }
    
    function addLedger($request,$schoolyear){
        $discount_code=0;
        $discount_description="";
        $discount_tuition=0;
        $discount_other=0;
        $discount_depository=0;
        $discount_misc=0;
        $discount_srf=0;
        $discount = \App\CtrDiscount::where('discount_code',$request->discount)->first();
        if(count($discount)>0){
        $discount_code=$discount->discount_code;
        $discount_description=$discount->discount_description;
        $discount_tuition=$discount->tuition_fee;
        $discount_other=$discount->other_fee;
        $discount_depository=$discount->depository_fee;
        $discount_misc=$discount->misc_fee;
        }
        $department = \App\CtrAcademicProgram::where('level',$request->level)->first();
        $fees = \App\CtrBedFee::where('level',$request->level)->get();
        if(count($fees)>0){
            foreach($fees as $fee){
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
                $addledger->department = $department->department;
                $addledger->level = $request->level;
                if($request->level=="Grade 11" || $request->level=="Grade 12"){
                $addledger->strand = $request->strand;    
                }
                $addledger->school_year=$schoolyear;
                $addledger->category=$fee->category;
                $addledger->subsidiary=$fee->subsidiary;
                $addledger->receipt_details = $fee->receipt_details;
                $addledger->accounting_code=$fee->accounting_code;
                $addledger->accounting_name =$this->getAccountingName($fee->accounting_code);
                $addledger->category_switch=$fee->category_switch;
                $amount1 = $fee->amount;
                $amount = $fee->amount;
                $discount_amount=0;
                switch ($fee->category_switch){
                    case env("MISC_FEE"):
                        $amount = $fee->amount - ($fee->amount * $discount_misc/100);
                        $discount_amount = $fee->amount * $discount_misc/100;
                        break;
                    case env("OTHER_FEE"):
                        $amount=$fee->amount -($fee->amount * $discount_other/100);
                        $discount_amount = $fee->amount * $discount_other/100;
                        break;
                    case env("DEPOSITORY_FEE"):
                        $amount=$fee->amount -($fee->amount * $discount_depository/100);
                        $discount_amount = $fee->amount * $discount_depository/100;
                        break;
                    case env("TUITION_FEE"):
                        $addpercent = $this->addPercentage($request->plan);
                        $amount = ($fee->amount + ($fee->amount * $addpercent/100)) - ($fee->amount + ($fee->amount * $addpercent/100)) * $discount_tuition/100;
                        $discount_amount = ($fee->amount + ($fee->amount * $addpercent/100)) * $discount_tuition/100;
                }
                
                $addledger->amount = $amount1;
                $addledger->discount_code = $discount_code;
                $addledger->discount = $discount_amount;
                $addledger->save();
            }
        }
        
        
    }
    
    function enrollment_statistics($school_year){
        $kinder = \App\BedLevel::selectRaw("level,section,count(*)as count")
                ->whereRaw("school_year=$school_year AND level='Kinder' AND status='3'")->groupBy('level','section');
        
        $statistics = \App\BedLevel::selectRaw("sort_by, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND sort_by <= '10' AND sort_by >= '1' AND status='3'")->groupBy('sort_by','section')
                ->orderBy('sort_by')->get();
        
        $abm =\App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'ABM' AND status='3'")->groupBy('sort_by','strand','section','strand')
                ->get();
        
        $humms=\App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'HUMMS' AND status='3'")->groupBy('sort_by','strand','section','strand')
                ->get();
        
        $stem =\App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'STEM' AND status='3'")->groupBy('sort_by','strand','section','strand')
                ->get();
      
        return view('reg_be.enrollment_statistics',compact('statistics','abm','humms','stem'));
    }
    
    function addSRF($request,$schoolyear){
        if($request->level == "Grade 11" || $request->level == "Grade 12"){
             $department = \App\CtrAcademicProgram::where('level',$request->level)->first();
            $srf = \App\CtrBedSrf::where('level',$request->level)->where('strand',$request->strand)->first();
            if(count($srf)>0){
                $add = new \App\Ledger;
                $add->idno = $request->idno;
                $add->department = $department->department;
                $add->strand = $request->strand;
                $add->level = $request->level;
                $add->school_year = $schoolyear;
                $add->category = $srf->category;
                $add->subsidiary = $srf->subsidiary;
                $add->receipt_details = $srf->receipt_details;
                $add->accounting_code = $srf->accounting_code;
                $add->category_switch = $srf->category_switch;
                $add->accounting_name = $this->getAccountingName($srf->accounting_code);
                $add->amount=$srf->amount;
                $add->save();
            }
        }
        
    }
    
    function addOptionalFee($request){
    if(count($request->qty_books)>0){    
        $this->processOptional($request->qty_books,$request,'books');
    }
    if(count($request->qty_materials)>0){
        $this->processOptional($request->qty_materials,$request,'materials');
    }
    if(count($request->qty_other_materials)>0){
        $this->processOptional($request->qty_other_materials,$request,'other_materials');
    }
    if(count($request->qty_pe_uniforms)>0){
        $this->processOptional($request->qty_pe_uniforms,$request,'pe_uniform');
    }
    }
    
    function addPercentage($plan){
        switch ($plan){
            case "Annual":
                return 0;
                break;
            case "Semestral":
                return 1;
                break;
            case "Quarterly":
                return 2;
                break;
            case "Monthly":
                return 3;
                break;
        } 
    }
    
    function getAccountingName($accounting_code){
       $accounting_name =  \App\ChartOfAccount::where('accounting_code',$accounting_code)->first();
       if(count($accounting_name)>0){
           return $accounting_name->accounting_name;
       } else {
           return "Not Found in Chart of Account";
       }
    }
    
    function processOptional($optional,$request,$material){
        $department = \App\CtrAcademicProgram::where('level',$request->level)->first();
        $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type','BED')->first();
        foreach($optional as $key=>$value){
        if($value > 0){    
        $item = \App\CtrOptionalFee::find($key);
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
                $addledger->department = $department->department;
                $addledger->level = $request->level;
                $addledger->school_year=$schoolyear->school_year;
                $addledger->category=$item->category;
                $addledger->subsidiary=$item->subsidiary;
                $addledger->receipt_details = $item->receipt_details;
                $addledger->accounting_code=$item->accounting_code;
                $addledger->accounting_name =$this->getAccountingName($item->accounting_code);
                $addledger->category_switch=$item->category_switch;
                $addledger->amount = $item->amount * $value;
                $addledger->qty = $value;
                $addledger->save();
        
            }      
        }  
    }
    
  function addDueDates($request,$schoolyear){
      if($request->plan=="Annual"){
          $total = \App\Ledger::selectRaw('idno, sum(amount)-sum(discount) as total')->where('idno',$request->idno)
                  ->where('category_switch','<=','6')->groupBy('idno')->first();
          $addduedate = new \App\LedgerDueDate;
          $addduedate->idno = $request->idno;
          $addduedate->school_year = $schoolyear;
          $addduedate->due_date = Date('Y-m-d');
          $addduedate->due_switch = 0;
          $addduedate->amount = $total->total;
          $addduedate->save();
      } else { 
          $duedates = \App\CtrDueDateBed::where('plan',$request->plan)->get();
          $count = count($duedates)+1;
          $duetuition = \App\Ledger::selectRaw('idno, sum(amount)-sum(discount) as total')->where('idno',$request->idno)
                  ->where('category_switch','6')->groupBy('idno')->first();
          $dueamount = $duetuition->total/$count;
          
          $dueothers = \App\Ledger::selectRaw('idno, sum(amount)-sum(discount) as total')->where('idno',$request->idno)
                  ->where('category_switch','<=','5')->groupBy('idno')->first();
          $addduedate = new \App\LedgerDueDate;
          $addduedate->idno = $request->idno;
          $addduedate->school_year = $schoolyear;
          $addduedate->due_date = Date('Y-m-d');
          $addduedate->due_switch = 0;
          $addduedate->amount = $dueothers->total + $dueamount;
          $addduedate->save();
          foreach($duedates as $duedate){
          $addduedate = new \App\LedgerDueDate;
          $addduedate->idno = $request->idno;
          $addduedate->school_year = $schoolyear;
          $addduedate->due_date = $duedate->due_date;
          $addduedate->due_switch = 1;
          $addduedate->amount = $dueamount;
          $addduedate->save(); 
          }
      }
      
  }
  
  function modifyStatus($request){
      $department = \App\CtrAcademicProgram::where('level',$request->level)->first();
      $status = \App\Status::where('idno',$request->idno)->first();
      $status->status = env("ASSESSED");
      $status->level = $request->level;
      if($request->level=="Grade 11" || $request->level=="Grade 12"){
         $status->strand = $request->strand; 
      }
      $status->section = $request->section;
      $status->department = $department->department;
      $status->date_registered = date('Y-m-d');
      $status->update();
  }
  
  function reassess($idno){
      if(Auth::user()->accesslevel == env("REG_BE")){
          $status = \App\Status::where('idno',$idno)->first();
          $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type',"BED")->first();
          if($status->status==env("ASSESSED")){
              DB::beginTransaction();
              $this->removeLedger($idno,$schoolyear->school_year);
              $this->removeLedgerDueDate($idno,$schoolyear->school_year);
              $this->removeGrades($idno,$schoolyear->school_year);
              $this->returnStatus($idno,$schoolyear->school_year);
              DB::commit();
          }
                 
      }
      
      return redirect(url('/bedregistrar',array('assess',$idno)));
  }
  
  function removeLedger($idno,$schoolyear){
    \App\Ledger::where('idno',$idno)->where('category_switch','<=','6')->where('school_year',$schoolyear)->delete();
  }
  function removeLedgerDueDate($idno,$schoolyear){
      \App\LedgerDueDate::where('idno',$idno)->where('school_year',$schoolyear)->delete();
  }
  function removeGrades($idno,$schoolyear){
      \App\GradeBasicEd::where('idno',$idno)->where('school_year',$schoolyear)->delete();
  }
  function returnStatus($idno,$schoolyear){
      $status =  \App\Status::where('idno',$idno)->first();
      $assignlevel=$status->level;
      switch ($status->level){
          case "Kinder":
              $assignlevel="Pre-Kinder";
              break;
          case "Grade 1":
              $assignlevel="Kinder";
              break;
          case "Grade 2":
              $assignlevel="Grade 1";
              break;
          case "Grade 3":
              $assignlevel="Grade 2";
              break;
          case "Grade 4":
              $assignlevel="Grade 3";
              break;
          case "Grade 5":
              $assignlevel="Grade 4";
              break;
          case "Grade 6":
              $assignlevel="Grade 5";
              break;
          case "Grade 7":
              $assignlevel="Grade 6";
              break;
          case "Grade 8":
              $assignlevel="Grade 7";
              break;
          case "Grade 9":
              $assignlevel="Grade 8";
              break;
          case "Grade 10":
              $assignlevel="Grade 9";
              break;
          case "Grade 11":
              $assignlevel="Grade 10";
              break;
          case "Grade 12":
              $assignlevel="Grade 11";
              break;
      }
      $status->level = $assignlevel;
      $status->status = 0;
      $status->update();
  }
  function checkReservations($request,$school_year) {
        $checkreservations = \App\Reservation::where('idno', $idno)->where('is_consumed', 0)->where('is_reverse', 0)->selectRaw('sum(amount) as amount')->first();
        if ($checkreservations->amount > 0) {
            $totalpayment = $checkreservations->amount;
            $reference_id = uniqid();
            $ledgers = \App\Ledger::where('idno',$request->idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('category_switch','<=','6')->get();
            $changestatus=  \App\Status::where('idno',$idno)->first();
            $changestatus->status=env("ENROLLED");
            $changestatus->update();
            MainPayment::addUnrealizedEntry($request, $reference_id);
            MainPayment::processAccounting($request, $reference_id, $totalpayment, $ledgers,env("DEBIT_MEMO"));
            $this->postDebit($idno, $reference_id, $totalpayment);
            $changereservation = \App\Reservation::where('idno',$idno)->get();
            if(count($changereservation)>0){
                foreach($changereservation as $change){
                $change->is_consumed = '1';
                $change->update();
                }
            }
        }
    }
  
}
