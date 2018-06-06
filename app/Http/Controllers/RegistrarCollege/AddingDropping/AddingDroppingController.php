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
        $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first();
        $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
        $adding_droppings = \App\AddingDropping::where('idno', $idno)->where('is_done', 0)->get();

        return view('reg_college.adding_dropping.view_grades', compact('school_year', 'idno', 'grades', 'adding_droppings', 'user'));
    }

    function remove($idno, $id) {

        $remove = \App\AddingDropping::where('id', $id)->first();
        $remove->delete();

        return redirect("/registrar_college/adding_dropping/$idno");
    }

    function process($idno) {
        $status = \App\Status::where('idno', $idno)->first();
        $user = \App\User::where('idno', $idno)->first();
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', "College")->first();
        DB::beginTransaction();
        $this->addSurcharge($idno, $school_year, $status, $user);
        $this->processAdding($idno, $school_year, $status, $user);
        $this->processDropping($idno, $school_year, $status, $user);
        $this->deleteLedgerduedate($idno, $school_year->school_year, $school_year->period);
        $this->computeLedgerDueDate($idno, $school_year->school_year, $school_year->period, $status->type_of_plan);
        DB::Commit();
        return redirect("registrar_college/assessment/$idno");
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

    function processAdding($idno, $school_year, $status, $user) {
        $tuitionfee = 0;
        $tfr = \App\CtrCollegeTuitionFee::where('program_code', $status->program_code)->where('period', $school_year->period)->where('level', $status->level)->first();
        $tuitionrate = $tfr->per_unit;
        $checktuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('category_switch', 6)->first();
        $adds = \App\AddingDropping::distinct()->where('idno', $idno)->where('is_done', 0)->where('action', 'ADD')->get();
        foreach ($adds as $grade) {
            if (count($checktuition) > 0) {
                $tuitionfee = ((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100));

                $checktuition->amount = $checktuition->amount + $tuitionfee;
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
                $new_grade->save();
                
                $addledger = new \App\Ledger;
                $addledger->idno = $idno;
                $addledger->department = \App\CtrAcademicProgram::where('program_code', $status->program_code)->first()->department;
                $addledger->program_code = $status->program_code;
                $addledger->level = $grade->level;
                $addledger->school_year = $school_year->school_year;
                $addledger->period = $school_year->period;
                $addledger->category = "SRF";
                $addledger->subsidiary = $grade->course_code;
                $addledger->receipt_details = "SRF";
                $addledger->accounting_code = env("SRF_CODE");
                $addledger->accounting_name = env("SRF_NAME");
                $addledger->category_switch = env("SRF_FEE");
                $addledger->amount = $grade->srf;
                $addledger->save();

                $grade->is_done = 1;
                $grade->save();
            }
        }
    }

    function processDropping($idno, $school_year, $status, $user) {
        $tuitionfee = 0;
        $tfr = \App\CtrCollegeTuitionFee::where('program_code', $status->program_code)->where('period', $school_year->period)->where('level', $status->level)->first();
        $tuitionrate = $tfr->per_unit;
        $checktuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('category_switch', 6)->first();
        $adds = \App\AddingDropping::distinct()->where('idno', $idno)->where('is_done', 0)->where('action', 'DROP')->get();
        foreach ($adds as $grade) {
            if (count($checktuition) > 0) {
                $tuitionfee = ((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100));

                $checktuition->amount = $checktuition->amount - $tuitionfee;
                $checktuition->save();


                $deletesrf = \App\Ledger::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('subsidiary', "$grade->course_code")->first();
                if (count($deletesrf) > 0) {
                    $deletesrf->amount = 0;
                    $deletesrf->save();
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

    function computeLedgerDueDate($idno, $school_year, $period, $plan) {
        $status = \App\Status::where('idno', $idno)->first();
        $due_dates = \App\CtrDueDate::where('academic_type', $status->academic_type)->where('plan', $plan)->where('level', $status->level)->get();
        $totalTuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 6)->sum('amount');
        $totalOtherFees = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '<', 6)->sum('amount');
        $totalTuitionDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 6)->sum('discount');
        $totalOtherFeesDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '<', 6)->sum('discount');
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

    function computeplan($downpaymentamount, $totalFees, $due_dates) {
        $planpayment = ($totalFees - $downpaymentamount) / count($due_dates);
        return $planpayment;
    }

    function getAccountingName($accounting_code) {
        $accounting_name = \App\ChartOfAccount::where('accounting_code', $accounting_code)->first()->accounting_name;
        return $accounting_name;
    }

}
