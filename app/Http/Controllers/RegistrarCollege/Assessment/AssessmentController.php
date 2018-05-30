<?php

namespace App\Http\Controllers\RegistrarCollege\Assessment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use App\Http\Controllers\Cashier\MainPayment;
use DB;

class AssessmentController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }
    
    function index($idno){
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            
                $status = \App\Status::where('idno', $idno)->first();
            if ($status->status == 0) {
//                return view('dean', array('advising',$idno), compact('status', 'idno', 'school_year', 'period'));
                return redirect("/dean/advising/$idno");
            } else{ 
                return view('reg_college.assessment.select_school_year', compact('idno'));
            }
        }
        
    }

    function index2($idno, $school_year,$period) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {

            $checkcollegelevels = \App\CollegeLevel::where('idno',$idno)->where('school_year',$school_year)->where('period',$period)->first();
            if(count($checkcollegelevels)>0){
                $status = \App\CollegeLevel::where('idno',$idno)->where('school_year',$school_year)->where('period',$period)->first();
            }else {
                $status = \App\Status::where('idno', $idno)->first();
            }
            if ($status->status == env('ADVISING')) {
                //return view('reg_college.assessment.select_school_year', compact('idno'));
                return view('reg_college.assessment.view_assessment', compact('idno', 'school_year', 'period'));
            } else if ($status->status == env('ASSESSED')) {
                return view('reg_college.assessment.assessed', compact('status', 'idno', 'school_year', 'period'));
            } else if ($status->status >= env('ENROLLED')) {
                return view('reg_college.assessment.enrolled', compact('status', 'idno', 'school_year', 'period'));
            } else {
                return view('reg_college.assessment.enrolled', compact('status', 'idno', 'school_year', 'period'));
            }
        }
    }

    function set_up_year(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            
            $school_year = $request->school_year;
            $period = $request->period;
            $idno = $request->idno;
            
            return redirect("/registrar_college/assessment2/$idno/$school_year/$period");
            //return view('reg_college.assessment.view_assessment', compact('idno', 'school_year', 'period'));
        }
    }

    function checkcourse_offering_id($idno) {
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', "College")->first();
        $check = \App\GradeCollege::where('idno', "$idno")->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('course_offering_id', NULL)->get();

        if (count($check) > 0) {
            return 0;
        } else {
            return 1;
        }
    }
    
    function readvise($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            
            $status = \App\Status::where('idno', $idno)->first();
            $status->status=0;
            $status->save();
            
            return redirect("/registrar_college/assessment/$idno");
            //return view('reg_college.assessment.view_assessment', compact('idno', 'school_year', 'period'));
        }
    }

    function save_assessment(Request $request) {
        DB::beginTransaction();
        $this->processAssessment($request);
        DB::Commit();
        return redirect(url('/registrar_college', array('assessment', $request->idno)));
    }

    function processAssessment($request) {
        $discounttf = 0;
        $discountof = 0;
        $discounttype = 0;
        $school_year=$request->school_year;
        $period=$request->period;
//        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
//        $period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
        $idno = $request->idno;
        $plan = $request->plan;
        $discount_code = $request->discount;
        $level = $request->level;
        $type_of_account = $request->type_of_account;
        $program_code = $request->program_code;
        $is_audit = $request->is_audit;

        ///delete current records if reasssess///
        $this->deletecurrentledgers($idno, $school_year, $period);
        $this->deleteledgerduedate($idno, $school_year, $period);

        //get discount////
        if (!is_null($discount_code)) {
            $discounttype = \App\CtrDiscount::where('discount_code', $discount_code)->first()->discount_type;
            if ($discounttype == 0) {
                $discounttf = $this->getdiscountrate('tf', $discount_code);
                $discountof = $this->getdiscountrate('of', $discount_code);
            } else if ($discounttype == 1) {
                $discounttf = $this->getdiscount('tf', $discount_code);
            }
        }

        //get tuition fee rate///
        $tfr = \App\CtrCollegeTuitionFee::where('program_code', $program_code)->where('period', $period)->where('level', $level)->first();
        $tuitionrate = $tfr->per_unit;

        //poppulate other fee with discount////
        $course_assessed = \App\GradeCollege::where('idno',$idno)->where('school_year', $school_year)->where('period', $period)->get();
        if(count($course_assessed)>1){
        $this->getOtherFee($idno, $school_year, $period, $level, $program_code, $discountof, $discount_code);
        } else {
            $check_practicum = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)
                    ->where(function($q) {
                        $q->where('course_name', 'like', '%practicum%')
                          ->orWhere('course_code', 'like', '%prac%');
                    })
                    ->get();
            if(count($check_practicum)==1){
                $this->getPracticumOtherFee($idno, $school_year, $period, $level, $program_code, $discountof, $discount_code);
            }else{
        $this->getOtherFee($idno, $school_year, $period, $level, $program_code, $discountof, $discount_code);
            }
        }
        //populate tuition fee with discount///
        $this->getCollegeTuition($idno, $school_year, $period, $level, $program_code, $tuitionrate, $plan, $discounttf, $discountof, $discount_code, $discounttype);
        //populate srf//
        $this->getSRF($idno, $program_code, $school_year, $period, $level);
        //populate due dates//
        $this->computeLedgerDueDate($idno, $school_year, $period, $plan);
        //change status///
        $this->changeStatus($is_audit, $school_year, $period, $plan, $type_of_account, $idno, $discount_code);
        //check reservation//
        $this->checkReservations($request, $idno, $school_year, $period);
        /*
          $totalFee = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->sum('amount');
          $totalDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->sum('discount');
          $tuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 5)->sum('amount');
          $tuitionDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 5)->sum('discount');
          $misc = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 1)->sum('amount');
          $miscDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 1)->sum('discount');
          $other = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 2)->sum('amount');
          $otherDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 2)->sum('discount');
          $depo = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 3)->sum('amount');
          $depoDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 3)->sum('discount');
          $srf = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 4)->sum('amount');
          $srfDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 4)->sum('discount');
         */
    }

    function reassess($idno) {
        $updatestatus = \App\Status::where('idno', $idno)->first();
        $updatestatus->status = 1;
        $updatestatus->save();

        return redirect("/registrar_college/assessment/$idno");
    }

    function print_registration_form($idno,$school_year,$period) {

        $user = \App\User::where('idno', $idno)->first();
        $status = \App\Status::where('idno', $idno)->first();
        $student_info = \App\StudentInfo::where('idno', $idno)->first();
        //$y = \App\CtrAcademicSchoolYear::where('academic_type', $status->academic_type)->first();
        $y_year = $school_year;
        $y_period = $period;
        
        //$school_year = \App\CtrAcademicSchoolYear::where('academic_type', $status->academic_type)->first();
        $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
        $ledger_due_dates = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('due_switch', 1)->get();
        $downpayment = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('due_switch', 0)->first();

        $pdf = PDF::loadView('reg_college.assessment.registration_form', compact('student_info', 'grades', 'user', 'status', 'school_year','period', 'ledger_due_dates', 'downpayment', 'y_year', 'y_period'));
        $pdf->setPaper(array(0, 0, 612.00, 936.0));
        return $pdf->stream("registration_form_$status->registration_no.pdf");

        //return "Printing of Registration form will be here.";
    }

    function deletecurrentledgers($idno, $school_year, $period) {
        $currentledgers = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '<=', '6')->get();
        if (count($currentledgers) > 0) {
            foreach ($currentledgers as $currentledger) {
                $currentledger->delete();
            }
        }
    }

    function deleteledgerduedate($idno, $school_year, $period) {
        $deleteledgerduedates = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
        if (count($deleteledgerduedates) > 0) {
            foreach ($deleteledgerduedates as $deleteledgerduedate) {
                $deleteledgerduedate->delete();
            }
        }
    }

    function getdiscountrate($type, $discount_code) {
        if ($type == 'tf') {
            return \App\CtrDiscount::where('discount_code', $discount_code)->first()->tuition_fee;
        } elseif ($type == 'of') {
            return \App\CtrDiscount::where('discount_code', $discount_code)->first()->other_fee;
        }
    }

    function getdiscount($type, $discount_code) {
        if ($type == 'tf') {
            return \App\CtrDiscount::where('discount_code', $discount_code)->first()->amount;
        }
    }
    
    function getPracticumOtherFee($idno, $school_year, $period, $level, $program_code, $discountof, $discount_code){
        $otherfees = \App\CtrCollegePracticumFee::get();
        if (count($otherfees) > 0) {
            foreach ($otherfees as $otherfee) {
                $addledger = new \App\ledger;
                $addledger->idno = $idno;
                $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                $addledger->program_code = $program_code;
                $addledger->level = $level;
                $addledger->school_year = $school_year;
                $addledger->period = $period;
                $addledger->category = $otherfee->category;
                $addledger->subsidiary = $otherfee->subsidiary;
                $addledger->receipt_details = $otherfee->receipt_details;
                $addledger->accounting_code = $otherfee->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($otherfee->accounting_code);
                $addledger->category_switch = $otherfee->category_switch;
                $addledger->amount = $otherfee->amount;
                $addledger->discount = $otherfee->amount * ($discountof / 100);
                $addledger->discount_code = $discount_code;
                $addledger->save();
            }
        }
    }

    function getOtherFee($idno, $school_year, $period, $level, $program_code, $discountof, $discount_code) {
        $otherfees = \App\CtrCollegeOtherFee::where('program_code', $program_code)->where('level', $level)->where('period', $period)->get();
        if (count($otherfees) > 0) {
            foreach ($otherfees as $otherfee) {
                $addledger = new \App\ledger;
                $addledger->idno = $idno;
                $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                $addledger->program_code = $program_code;
                $addledger->level = $level;
                $addledger->school_year = $school_year;
                $addledger->period = $period;
                $addledger->category = $otherfee->category;
                $addledger->subsidiary = $otherfee->subsidiary;
                $addledger->receipt_details = $otherfee->receipt_details;
                $addledger->accounting_code = $otherfee->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($otherfee->accounting_code);
                $addledger->category_switch = $otherfee->category_switch;
                $addledger->amount = $otherfee->amount;
                $addledger->discount = $otherfee->amount * ($discountof / 100);
                $addledger->discount_code = $discount_code;
                $addledger->save();
            }
        }
    }

    function getCollegeTuition($idno, $school_year, $period, $level, $program_code, $tuitionrate, $plan, $discounttf, $discountof, $discount_code, $discounttype) {
        $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();

        $interest = $this->getInterest($plan);
        $tuitionfee = 0;
        $tobediscount = 0;
        foreach ($grades as $grade) {
            if ($discounttype == 0) {
                $tobediscount = $tobediscount + ((((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100)) * ($discounttf / 100)) * $interest);
            } else if ($discounttype == 1) {
                $tobediscount = $tobediscount + $discounttf;
            }

            $tuitionfee = $tuitionfee + (((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100)) * $interest);
        }
        $addledger = new \App\ledger;
        $addledger->idno = $idno;
        $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
        $addledger->program_code = $program_code;
        $addledger->level = $level;
        $addledger->school_year = $school_year;
        $addledger->period = $period;
        $addledger->category = "Tuition Fee";
        $addledger->subsidiary = "Tuition Fee";
        $addledger->receipt_details = "Tuition Fee";
        $addledger->accounting_code = env("AR_TUITION_CODE");
        $addledger->accounting_name = env("AR_TUITION_NAME");
        $addledger->category_switch = env("TUITION_FEE");
        $addledger->amount = $this->roundOff($tuitionfee);
        $addledger->discount = $tobediscount;
        $addledger->discount_code = $discount_code;
        $addledger->save();
    }

    function getSRF($idno, $program_code, $school_year, $period, $level) {
        $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('srf', '>', '0')->get();
        if (count($grades) > 0) {
            foreach ($grades as $grade) {
                $addledger = new \App\ledger;
                $addledger->idno = $idno;
                $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                $addledger->program_code = $program_code;
                $addledger->level = $level;
                $addledger->school_year = $school_year;
                $addledger->period = $period;
                $addledger->category = "SRF";
                $addledger->subsidiary = $grade->course_code;
                $addledger->receipt_details = "SRF";
                $addledger->accounting_code = env("SRF_CODE");
                $addledger->accounting_name = env("SRF_NAME");
                $addledger->category_switch = env("SRF_FEE");
                $addledger->amount = $grade->srf;
                $addledger->save();
            }
        }
    }

    function computeLedgerDueDate($idno, $school_year, $period, $plan) {
        $status = \App\Status::where('idno', $idno)->first();
        $due_dates = \App\CtrDueDate::where('academic_type', $status->academic_type)->where('plan', $plan)->where('level', $status->level)->get();
        $totalTuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 6)->sum('amount');
        $totalOtherFees = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '!=', 6)->sum('amount');
        $totalTuitionDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 6)->sum('discount');
        $totalOtherFeesDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '!=', 6)->sum('discount');
        $totalFees = ($totalTuition + $totalOtherFees) - ($totalTuitionDiscount + $totalOtherFeesDiscount);
        $downpaymentamount = (($totalTuition - $totalTuitionDiscount) / 2) + ($totalOtherFees - $totalOtherFeesDiscount);
        if ($plan == 'Cash') {
            $addledgerduedates = new \App\LedgerDueDate;
            $addledgerduedates->idno = $idno;
            $addledgerduedates->school_year = $school_year;
            $addledgerduedates->period = $period;
            $addledgerduedates->due_switch = 0;
            $addledgerduedates->due_date = date('Y-m-d');
            $addledgerduedates->amount = $totalFees;
            $addledgerduedates->save();
        } else {
            $addledgerduedates = new \App\LedgerDueDate;
            $addledgerduedates->idno = $idno;
            $addledgerduedates->school_year = $school_year;
            $addledgerduedates->period = $period;
            $addledgerduedates->due_switch = 0;
            $addledgerduedates->due_date = date('Y-m-d');
            $addledgerduedates->amount = $downpaymentamount;
            $addledgerduedates->save();
            foreach ($due_dates as $paln) {
                $addledgerduedates = new \App\LedgerDueDate;
                $addledgerduedates->idno = $idno;
                $addledgerduedates->school_year = $school_year;
                $addledgerduedates->period = $period;
                $addledgerduedates->due_switch = 1;
                $addledgerduedates->due_date = $paln->due_date;
                $addledgerduedates->amount = $this->computeplan($downpaymentamount, $totalFees, $due_dates);
                $addledgerduedates->save();
            }
        }
    }

    function getAccountingName($accounting_code) {
        $accounting_name = \App\ChartOfAccount::where('accounting_code', $accounting_code)->first()->accounting_name;
        return $accounting_name;
    }

    function getInterest($plan) {
//        if ($plan == "Cash") {
//            $interest = 1;
//        } else if ($plan == "Quarterly") {
//            $interest = 1.02;
//        } else if ($plan == "Monthly") {
//            $interest = 1.03;
//        }

        if ($plan == "Cash") {
            $interest = 1;
        } else if ($plan == "Plan B") {
            $interest = 1.01;
        } else if ($plan == "Plan C") {
            $interest = 1.02;
        } else if ($plan == "Plan D") {
            $interest = 1.03;
        }
        return $interest;
    }

    function computeplan($downpaymentamount, $totalFees, $due_dates) {
        $planpayment = ($totalFees - $downpaymentamount) / count($due_dates);
        return $planpayment;
    }

    function changeStatus($is_audit, $school_year, $period, $plan, $type_of_account, $idno, $discount_code) {
        $changestatus = \App\Status::where('idno', $idno)->first();
        $changestatus->date_registered = date('Y-m-d');
        $changestatus->status = env('ASSESSED');
        $changestatus->school_year = $school_year;
        $changestatus->period = $period;
        $changestatus->type_of_account = $type_of_account;
        $changestatus->type_of_plan = $plan;
        $changestatus->school_year = $school_year;
        $changestatus->period = $period;
        $changestatus->type_of_discount = $discount_code;
        $changestatus->is_audit = $is_audit;
        $changestatus->save();
    }

    function checkReservations($request, $idno, $school_year, $period) {
        $checkreservations = \App\Reservation::where('idno', $idno)->where('is_consumed', 0)->where('is_reverse', 0)->selectRaw('sum(amount) as amount')->first();
        if ($checkreservations->amount > 0) {
            $totalpayment = $checkreservations->amount;
            $reference_id = uniqid();
            $ledgers = \App\Ledger::where('idno', $idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('category_switch', '<=', '6')->get();
            $changestatus = \App\Status::where('idno', $idno)->first();
            $changestatus->status = env("ENROLLED");
            $changestatus->update();
            MainPayment::addUnrealizedEntry($request, $reference_id);
            MainPayment::processAccounting($request, $reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
            $this->postDebit($idno, $reference_id, $totalpayment);
            $changereservation = \App\Reservation::where('idno', $idno)->get();
            if (count($changereservation) > 0) {
                foreach ($changereservation as $change) {
                    $change->is_consumed = '1';
                    $change->update();
                }
            }
        }
    }

    function postDebit($idno, $reference_id) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $reservations = \App\Reservation::where('idno', $idno)->where('is_consumed', 0)->where('is_reverse', 0)->get();
        $department = \App\Status::where('idno', $idno)->first()->department;
        $totalReserved = 0;
        if (count($reservations) > 0) {
            foreach ($reservations as $ledger) {
                $addacct = new \App\Accounting;
                $addacct->transaction_date = date('Y-m-d');
                $addacct->reference_id = $reference_id;
                //$addacct->reference_number=$ledger->id;
                $addacct->accounting_type = env("DEBIT_MEMO");
                $addacct->subsidiary = $ledger->idno;
                $dept = \App\Status::where('idno', $idno)->first();
                if (count($dept) > 0) {
                    $department = $dept->department;
                } else {
                    $department = "None";
                }
                $addacct->department = $department;
                if ($ledger->reservation_type == 1) {
                    $category = "Reservation";
                    $accounting_code = env("RESERVATION_CODE");
                    $accounting_name = env("RESERVATION_NAME");
                } else if ($ledger->reservation_type == 2) {
                    $category = "Student Deposit";
                    $accounting_code = env("STUDENT_DEPOSIT_CODE");
                    $accounting_name = env("STUDENT_DEPOSIT_NAME");
                }
                $addacct->category = $category;
                $addacct->receipt_details = $category;
                $addacct->particular = $category;
                $addacct->accounting_code = $accounting_code;
                $addacct->accounting_name = $accounting_name;
                $addacct->department = $department;
                $addacct->fiscal_year = $fiscal_year;
                $addacct->debit = $ledger->amount;
                $addacct->posted_by = Auth::user()->idno;
                $addacct->save();
                $ledger->is_consumed = 1;
                $totalReserved = $totalReserved + $ledger->amount;
            }
            $this->postDebitMemo($idno, $reference_id, $totalReserved);
        }
    }

    function postDebitMemo($idno, $reference_id, $totalReserved) {
        $debit_memo = new \App\DebitMemo;
        $debit_memo->idno = $idno;
        $debit_memo->transaction_date = date("Y-m-d");
        $debit_memo->reference_id = $reference_id;
        $debit_memo->dm_no = $this->getDMNumber();
        $debit_memo->explanation = "Reversal of Reservation/Student Deposit";
        $debit_memo->amount = $totalReserved;
        $debit_memo->posted_by = Auth::user()->idno;
        $debit_memo->save();
    }

    function getDMNumber() {
        $id = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->id;
        $number = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->dm_no;
        $receipt = "";
        for ($i = strlen($number); $i <= 6; $i++) {
            $receipt = $receipt . "0";
        }

        $update = \App\ReferenceId::where('idno', Auth::user()->idno)->first();
        $update->dm_no = $update->dm_no + 1;
        $update->update();

        return $id . $receipt . $number;
    }
    
    function roundOff($amount) {
        return round($amount);
    }
}
