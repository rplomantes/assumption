<?php

namespace App\Http\Controllers\RegistrarCollege\AddingDropping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Input;
use DB;

class AddingDroppingController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        $user = \App\User::where('idno',$idno)->first();
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
        $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
        $adding_droppings = \App\AddingDropping::where('idno', $idno)->where('is_done', 0)->get();

        return view('reg_college.adding_dropping.view_grades', compact('school_year', 'idno', 'grades', 'adding_droppings', 'user'));
    }

    function remove($idno, $id) {

        $remove = \App\AddingDropping::where('id', $id)->first();
        $remove->delete();

        return redirect("/registrar_college/adding_dropping/$idno");
    }

    function process($fee,$idno) {
        $status = \App\Status::where('idno', $idno)->first();
        $user = \App\User::where('idno', $idno)->first();
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', "College")->first();
        DB::beginTransaction();
        $is_practicum_only = $this->checkPracticumOnly($idno, $school_year->school_year, $school_year->period);
        if ($fee == "w"){
        $this->addSurcharge($idno, $school_year, $status, $user);
        }
        $this->processAdding($idno, $school_year, $status, $user, $is_practicum_only);
        $this->processDropping($idno, $school_year, $status, $user);
        $this->deleteLedgerduedate($idno, $school_year->school_year, $school_year->period);
        $this->computeLedgerDueDate($idno, $school_year->school_year, $school_year->period, $status->type_of_plan);
            \App\Http\Controllers\Admin\Logs::log("Process adding/dropping of $idno for S.Y. $school_year->school_year, $school_year->period");
        DB::Commit();
        return redirect("registrar_college/advising/assigning_of_schedules/$idno");
    }
    
    function checkPracticumOnly($idno, $school_year, $period){
        $course_assessed = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
        if (count($course_assessed) > 1) {
            return "0";
        } else {
            $check_practicum = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)
                    ->where(function($q) {
                        $q->where('course_name', 'like', '%practicum%')
                        ->orWhere('course_code', 'like', '%prac%');
                    })
                    ->get();
            if (count($check_practicum) == 1) {
                return "1";
            } else {
                return "0";
            }
        }
    }

    function addSurcharge($idno, $school_year, $status, $user) {
        $countSurcharges = \App\AddingDropping::distinct()->where('idno', $idno)->where('is_done', 0)->get(['action']);
        if (count($countSurcharges) > 0) {
            foreach ($countSurcharges as $countSurcharge) {
                $addledger = new \App\Ledger;
                $addledger->idno = $idno;
                $addledger->department = \App\CtrAcademicProgram::where('program_code', $status->program_code)->first()->department;
                $addledger->program_code = $status->program_code;
                $addledger->level = $status->level;
                $addledger->school_year = $school_year->school_year;
                $addledger->period = $school_year->period;
                $addledger->category = "Other Miscellaneous";
                $addledger->subsidiary = "Adding/Dropping";
                $addledger->receipt_details = "Adding/Dropping";
                $addledger->accounting_code = 6801;
                $addledger->accounting_name = $this->getAccountingName($addledger->accounting_code);
                $addledger->category_switch = 7;
                $addledger->amount = 30;
                $addledger->save();
            }
        }
    }

    function getdiscountrate($type, $discount_code, $idno) {
        if ($type == 'tf') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->tuition_fee;
        } elseif ($type == 'of') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->other_fee;
        }
    }
    
    function processAdding($idno, $school_year, $status, $user, $is_practicum_only) {
        $tuitionfee = 0;
        $tobediscount = 0;
        $discounttf = 0;
        $tfr = \App\CtrCollegeTuitionFee::where('program_code', $status->program_code)->where('period', $school_year->period)->where('level', $status->level)->first();
        $tuitionrate = $tfr->per_unit;
        $checktuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('category_switch', 6)->first();
        $adds = \App\AddingDropping::distinct()->where('idno', $idno)->where('is_done', 0)->where('action', 'ADD')->get();
        foreach ($adds as $grade) {
            if (count($checktuition) > 0) {
                
                if (!is_null($checktuition->discount_code)) {
                    $discounttype = \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $checktuition->discount_code)->first()->discount_type;
                    if ($discounttype == 0) {
                        $discounttf = $this->getdiscountrate('tf', $checktuition->discount_code, $idno);
                    } else if ($discounttype == 1) {
                        $discounttf = $this->getdiscount('tf', $checktuition->discount_code, $idno);
                    }
                }
                
                $tuitionfee   = ((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100));
                $tobediscount = (((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100)) * ($discounttf / 100));

                $checktuition->amount = $checktuition->amount + $tuitionfee;
                $checktuition->discount = $checktuition->discount + $tobediscount;
                $checktuition->save();

                $new_grade = new \App\GradeCollege();
                $new_grade->idno = $idno;
                $new_grade->course_code = $grade->course_code;
                $new_grade->course_name = $grade->course_name;
                $new_grade->level = $grade->level;
                $new_grade->lec = $grade->lec;
                $new_grade->lab = $grade->lab;
                $new_grade->hours = $grade->hours;
                $new_grade->percent_tuition = $grade->percent_tuition;
                $new_grade->school_year = $school_year->school_year;
                $new_grade->period = $school_year->period;
                $new_grade->srf = $grade->srf;
                $new_grade->lab_fee = $grade->lab_fee;
                $new_grade->save();
                
                if($grade->srf>0){
                $this->getSRF($idno, $status->program_code, $school_year->school_year, $school_year->period, $grade->level, $grade->id);
                }
                if($grade->lab_fee>0){
                $this->getLABFEE($idno, $status->program_code, $school_year->school_year, $school_year->period, $grade->level, $grade->id);
                }
                
                if($is_practicum_only == 1){
                $this->getOtherFee($idno, $school_year->school_year, $school_year->period, $grade->level, $status->program_code);
                }

                $grade->is_done = 1;
                $grade->save();
            }
        }
    }
    
    function getOtherFee($idno, $school_year, $period, $level, $program_code) {
        $otherfees = \App\CtrCollegeOtherFee::where('program_code', $program_code)->where('level', $level)->where('period', $period)->where('subsidiary','!=','Registration')->get();
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
                $addledger->save();
            }
        }
    }
    
    function getSRF($idno, $program_code, $school_year,$period,$level,$id) {
        $grades = \App\AddingDropping::distinct()->where('id', $id)->where('srf', '>', '0')->get();
        if (count($grades) > 0) {
            foreach ($grades as $grade) {
                $addledger = new \App\Ledger;
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
    
    function getLABFEE($idno, $program_code, $school_year,$period,$level,$id) {
        $grades = \App\AddingDropping::distinct()->where('id', $id)->where('lab_fee', '>', '0')->get();
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
                $addledger->subsidiary = "Lab Fee-".$grade->course_code;
                $addledger->receipt_details = "SRF";
                $addledger->accounting_code = env("LAB_FEE_CODE");
                $addledger->accounting_name = env("LAB_FEE_NAME");
                $addledger->category_switch = env("SRF_FEE");
                $addledger->amount = $grade->lab_fee;
                $addledger->save();
            }
        }
    }

    function processDropping($idno, $school_year, $status, $user) {
        $tuitionfee = 0;
        $tobediscount = 0;
        $discounttf = 0;
        $tfr = \App\CtrCollegeTuitionFee::where('program_code', $status->program_code)->where('period', $school_year->period)->where('level', $status->level)->first();
        $tuitionrate = $tfr->per_unit;
        $checktuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('category_switch', 6)->first();
        $adds = \App\AddingDropping::distinct()->where('idno', $idno)->where('is_done', 0)->where('action', 'DROP')->get();
        foreach ($adds as $grade) {
            if (count($checktuition) > 0) {
                if (!is_null($checktuition->discount_code)) {
                    $discounttype = \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $checktuition->discount_code)->first()->discount_type;
                    if ($discounttype == 0) {
                        $discounttf = $this->getdiscountrate('tf', $checktuition->discount_code, $idno);
                    } else if ($discounttype == 1) {
                        $discounttf = $this->getdiscount('tf', $checktuition->discount_code, $idno);
                    }
                }
                
                $tuitionfee   = ((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100));
                $tobediscount = (((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100)) * ($discounttf / 100));

                $checktuition->amount = $checktuition->amount - $tuitionfee;
                $checktuition->discount = $checktuition->discount - $tobediscount;
                $checktuition->save();


                $deletesrf = \App\Ledger::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('subsidiary', "$grade->course_code")->first();
                if (count($deletesrf) > 0) {
                    $deletesrf->amount = 0;
                    $deletesrf->save();
                }
                $deletelab = \App\Ledger::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('subsidiary', 'Lab Fee-'.$grade->course_code)->first();
                if (count($deletelab) > 0) {
                    $deletelab->amount = 0;
                    $deletelab->save();
                }
                $drop_grade = \App\GradeCollege::where('id', $grade->course_id)->first();
                $drop_grade->delete();

                $grade->is_done = 1;
                $grade->save();
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
    
    function get_percentage_now($plan){
        if ($plan == "Plan A") {
            $interest = 1;
        } else if ($plan == "Plan B") {
            $interest = .5;
        } else if ($plan == "Plan C") {
            $interest = .35;
        } else if ($plan == "Plan D") {
            $interest = .2;
        }
        return $interest;
    }

    function computeLedgerDueDate($idno, $school_year, $period, $plan) {
        $total_decimal = 0;
        $status = \App\Status::where('idno', $idno)->first();
        $due_dates = \App\CtrDueDate::where('academic_type', $status->academic_type)->where('plan', $plan)->where('level', $status->level)->get();
        $percentage_now = $this->get_percentage_now($plan);
        
        $totalTuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 6)->sum('amount');
        $totalOtherFees = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '<', 6)->sum('amount');
        $totalTuitionDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 6)->sum('discount');
        $totalOtherFeesDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '<', 6)->sum('discount');
        $totalFees = ($totalTuition + $totalOtherFees) - ($totalTuitionDiscount + $totalOtherFeesDiscount);
        $downpaymentamount = (($totalTuition - $totalTuitionDiscount) * $percentage_now) + ($totalOtherFees - $totalOtherFeesDiscount);
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
                $totalFees_percentage = (($totalTuition*($paln->percentage/100)) + $totalOtherFees) - (($totalTuitionDiscount*($paln->percentage/100)) + $totalOtherFeesDiscount);
                $tf_percentage = (($totalTuition*($paln->percentage/100)) - (($totalTuitionDiscount*($paln->percentage/100)) + $totalOtherFeesDiscount));
                $addledgerduedates = new \App\LedgerDueDate;
                $addledgerduedates->idno = $idno;
                $addledgerduedates->school_year = $school_year;
                $addledgerduedates->period = $period;
                $addledgerduedates->due_switch = 1;
                $addledgerduedates->due_date = $paln->due_date;
                $plan_amount = floor($this->computeplan($downpaymentamount, $totalFees_percentage, $due_dates, $tf_percentage));
                $addledgerduedates->amount = $plan_amount;
                $addledgerduedates->save();
                $total_decimal = $total_decimal + ($this->computeplan($downpaymentamount, $totalFees_percentage, $due_dates, $tf_percentage) - $plan_amount);
            }
            $this->update_due_dates($idno, $total_decimal, $downpaymentamount);
        }
    }

    function update_due_dates($idno, $total_decimal, $downpaymentamount) {
        $update = \App\LedgerDueDate::where('idno', $idno)->where('due_switch', 0)->where('due_date', Date('Y-m-d'))->first();
        $update->amount = $downpaymentamount + $total_decimal;
        $update->save();
    }

    function computeplan($downpaymentamount, $totalFees, $due_dates, $tf) {
        $planpayment = $tf;
//        $planpayment = ($totalFees - $downpaymentamount) / count($due_dates);
        return $planpayment;
    }

    function getAccountingName($accounting_code) {
        $accounting_name = \App\ChartOfAccount::where('accounting_code', $accounting_code)->first()->accounting_name;
        return $accounting_name;
    }

}
