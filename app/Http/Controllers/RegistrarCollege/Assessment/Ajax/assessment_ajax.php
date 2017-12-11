<?php

namespace App\Http\Controllers\RegistrarCollege\Assessment\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class assessment_ajax extends Controller {

//
    function get_assessed_payment() {
        if (Request::ajax()) {

            $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
            $period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
            $idno = Input::get("idno");
            $plan = Input::get("plan");
            $level = Input::get("level");
            $type_of_account = Input::get("type_of_account");
            $program_code = Input::get("program_code");

            $currentledgers = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
            if (count($currentledgers) > 0) {
                foreach ($currentledgers as $currentledger) {
                    $currentledger->delete();
                }
            }

            $deleteledgerduedates = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
            if (count($deleteledgerduedates) > 0) {
                foreach ($deleteledgerduedates as $deleteledgerduedate) {
                    $deleteledgerduedate->delete();
                }
            }

            if ($type_of_account == "Regular") {
                $tfr = \App\CtrCollegeTuitionFee::where('program_code', $program_code)->where('level', $level)->first();
                $tuitionrate = $tfr->per_unit;
                $otherfee = $this->getOtherFee($idno, $school_year, $period, $level, $program_code);
                $tuitionfee = $this->getCollegeTuition($idno, $school_year, $period, $level, $program_code, $tuitionrate, $plan);

                $ledger_due_date = $this->computeLedgerDueDate($idno, $school_year, $period, $plan);

                $this->changeStatus($school_year, $period, $plan, $type_of_account, $idno);

                $totalFee = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->sum('amount');
                $tuition = \App\Ledger::groupBy(array('category'))->where('category', 'Tuition Fees Receivable')->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->selectRaw('category, sum(amount) as amount')->get();
                $misc = \App\Ledger::groupBy(array('category'))->where('category', 'Miscellaneous Fees')->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->selectRaw('category, sum(amount) as amount')->get();
                $other = \App\Ledger::groupBy(array('category'))->where('category', 'Other Fees')->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->selectRaw('category, sum(amount) as amount')->get();
                $depo = \App\Ledger::groupBy(array('category'))->where('category', 'Depository Fees')->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->selectRaw('category, sum(amount) as amount')->get();
                $srf = \App\Ledger::groupBy(array('category'))->where('category', 'Subject Related Fee')->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->selectRaw('category, sum(amount) as amount')->get();

                return view('reg_college.assessment.ajax.display_result', compact('idno', 'totalFee', 'tuition', 'misc', 'other', 'depo', 'srf'));
            }
        }
    }

    function getCollegeTuition($idno, $school_year, $period, $level, $program_code, $tuitionrate, $plan) {
        $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();

        if ($plan == "Cash") {
            $interest = 1;
        } else if ($plan == "Quarterly") {
            $interest = 1.02;
        } else if ($plan == "Monthly") {
            $interest = 1.03;
        }

        $tuitionfee = 0;
        foreach ($grades as $grade) {
            $tuitionfee = $tuitionfee + (((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100)) * $interest);
        }
        $addledger = new \App\ledger;
        $addledger->idno = $idno;
        $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
        $addledger->program_code = $program_code;
        $addledger->level = $level;
        $addledger->school_year = $school_year;
        $addledger->period = $period;
        $addledger->category = "Tuition Fees Receivable";
        $addledger->subsidiary = "Tuition Fees Receivable";
        $addledger->receipt_details = "Tuition Fees Receivable";
        $addledger->accounting_code = 120100;
        $addledger->category_switch = "5";
        $addledger->amount = $tuitionfee;
        $addledger->save();
    }

    function getOtherFee($idno, $school_year, $period, $level, $program_code) {
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
                $addledger->category_switch = $otherfee->category_switch;
                $addledger->amount = $otherfee->amount;
                $addledger->save();
            }
        }
    }

    function computeLedgerDueDate($idno, $school_year, $period, $plan) {
        $status = \App\Status::where('idno', $idno)->first();
        $due_dates = \App\CtrDueDate::where('academic_type', $status->academic_type)->where('plan', $plan)->where('level', $status->level)->get();
        $totalTuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 5)->sum('amount');
        $totalOtherFees = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '!=', 5)->sum('amount');
        $totalFees = $totalTuition + $totalOtherFees;
        $downpaymentamount = ($totalTuition / 2) + $totalOtherFees;

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

    function changeStatus($school_year, $period, $plan, $type_of_account, $idno) {
        $changestatus = \App\Status::where('idno', $idno)->first();
        $changestatus->type_of_account = $type_of_account;
        $changestatus->type_of_plan = $plan;
        $changestatus->school_year = $school_year;
        $changestatus->period = $period;
        $changestatus->save();
    }

}
