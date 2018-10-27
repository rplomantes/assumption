<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use Session;

class EditLedger extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($id) {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            $ledger = \App\Ledger::where('id', $id)->first();
            $status = \App\Status::where('idno', $ledger->idno)->first();
            return view('accounting.editLedger.view', compact('ledger', 'status', 'id'));
        }
    }

    function remove_ledger($id) {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {


            $idno = \App\Ledger::where('id', $id)->first()->idno;
            $stat = \App\Status::where('idno', $idno)->first();

            if ($stat->academic_type == "College") {
                DB::beginTransaction();

                $ledger = \App\Ledger::where('id', $id)->first();
                $idno = $ledger->idno;
                $ledger->delete();

                $this->college_change_due_date($stat);
                \App\Http\Controllers\Admin\Logs::log("Delete Ledger of $stat->idno with ledger id : $id ");
                DB::Commit();
            } else {
                DB::beginTransaction();
                $this->change_due_date($request);
                DB::Commit();
            }
            return redirect("/cashier/viewledger/$idno");
        }
    }

    function update($request) {
        $ledger = \App\Ledger::where('id', $request->id)->first();
        $ledger->amount = $request->amount;
        $ledger->save();
    }

    function update_ledger(Request $request) {
        $checkpasscode = \App\AccountingPasscode::where('passcode', $request->passcode)->where('is_used', 0)->first();
        if (count($checkpasscode) != 0) {
            $date_today = date('Y-m-d H:i:s');
            $date_ends = strtotime($checkpasscode->datetime_generated);
            $date_end = strtotime("+3 minutes", $date_ends);

            if ($checkpasscode->datetime_generated <= $date_today && $date_today <= date("Y-m-d H:i:s", $date_end)) {

                if ($request->submit == "Update Ledger") {
                    if ($request->academic_type == "College") {
                        DB::beginTransaction();
                        $this->update($request);
                        $this->updateDiscount_college($request);
                        $this->college_change_due_date($request);
                        $this->updatePasscode($checkpasscode);
                        \App\Http\Controllers\Admin\Logs::log("Update Ledger of $request->idno with ledger id : $request->id change amount to $request->amount");
                        DB::Commit();
                    } else {
                        DB::beginTransaction();
                        $this->update($request);
                        $this->change_due_date($request);
                        DB::Commit();
                    }
                } else {
                    $this->remove_ledger($request->id);
                }
            } else {
                Session::flash('warning', 'Passcode Timeout!');
                return redirect("/accounting/edit_ledger/$request->id");
            }
            return redirect("/cashier/viewledger/$request->idno");
        } else {
            Session::flash('warning', 'Incorrect Passcode!');
            return redirect("/accounting/edit_ledger/$request->id");
        }
    }

    function updateDiscount_college($request) {
        $tuition = 0;

        $stat = \App\Status::where('idno', $request->idno)->first();
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
        $period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
        $tfr = \App\CtrCollegeTuitionFee::where('program_code', $stat->program_code)->where('period', $period)->where('level', $stat->level)->first();
        $tuitionrate = $tfr->per_unit;
        $tobediscount = 0;

        $grades = \App\GradeCollege::where('idno', $request->idno)->where('school_year', $school_year)->where('period', $period)->get();

        $discounttype = \App\CollegeScholarship::where('idno', $request->idno)->first();

        if ($discounttype->discount_code != NULL) {
            if ($discounttype->discount_type == 0) {
                $discounttf = $this->getdiscountrate('tf', $discounttype->discount_code, $request->idno);

                //remove this after updating
                $discountof = $this->getdiscountrate('of', $discounttype->discount_code, $request->idno);
                $otherfees = \App\CtrCollegeOtherFee::where('program_code', $stat->program_code)->where('level', $stat->level)->where('period', $period)->get();
                foreach ($otherfees as $of) {
                    $getofledger = \App\Ledger::where('school_year', $school_year)->where('period', $period)->where('idno', $request->idno)->where('subsidiary', $of->subsidiary)->first();
                    $getofledger->discount = $getofledger->amount * ($discountof / 100);
                    $getofledger->discount_code = $discounttype->discount_code;
                    $getofledger->save();
                }
                //up to here
            } else if ($discounttype->discount_type == 1) {
                $discounttf = $this->getdiscount('tf', $discounttype->discount_code, $request->idno);
            }
        }


        foreach ($grades as $grade) {
            if ($discounttype->discount_code != NULL) {
                if ($discounttype->discount_type == 0) {
                    $tobediscount = $tobediscount + ((((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100)) * ($discounttf / 100)));
                } else if ($discounttype->discount_type == 1) {
                    $tobediscount = $tobediscount + $discounttf;
                }
            }

            $tuition = $tuition + (((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100)));
        }
    }

    function college_change_due_date($request) {
        $stat = \App\Status::where('idno', $request->idno)->first();
        $schoolyear = $stat->school_year;
        $period = $stat->period;

        $deltedue = \App\LedgerDueDate::where('idno', $request->idno)->where('school_year', $schoolyear)->where('period', $period)->delete();
        $this->computeLedgerDueDates($request->idno, $schoolyear, $period, $stat->type_of_plan);
    }

    function get_percentage_now($plan) {
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

    function computeLedgerDueDates($idno, $school_year, $period, $plan) {
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
        if ($plan == 'Plan A') {
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
                $totalFees_percentage = (($totalTuition * ($paln->percentage / 100)) + $totalOtherFees) - (($totalTuitionDiscount * ($paln->percentage / 100)) + $totalOtherFeesDiscount);
                $tf_percentage = (($totalTuition * ($paln->percentage / 100)) - (($totalTuitionDiscount * ($paln->percentage / 100))));

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
            $this->college_update_due_dates($idno, $total_decimal, $downpaymentamount);
        }
    }

    function computeplan($downpaymentamount, $totalFees, $due_dates, $tf) {
        $planpayment = $tf;
//        $planpayment = ($totalFees - $downpaymentamount) / count($due_dates);
        return $planpayment;
    }

    function college_update_due_dates($idno, $total_decimal, $downpaymentamount) {
        $update = \App\LedgerDueDate::where('idno', $idno)->where('due_switch', 0)->where('due_date', Date('Y-m-d'))->first();
        $update->amount = $downpaymentamount + $total_decimal;
        $update->save();
    }

    public static function log($action) {
        $log = new \App\Log();
        $log->action = "$action";
        $log->idno = Auth::user()->idno;
        $log->datetime = date("Y-m-d H:i:s");
        $log->save();
    }

    function getdiscountrate($type, $discount_code, $idno) {
        if ($type == 'tf') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->tuition_fee;
        } elseif ($type == 'of') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->other_fee;
        }
    }

    function getdiscount($type, $discount_code, $idno) {
        if ($type == 'tf') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->amount;
        }
    }

    function updatePasscode($checkpasscode) {
        $checkpasscode->is_used = 1;
        $checkpasscode->used_by = Auth::user()->idno;
        $checkpasscode->datetime_used = date('Y-m-d H:i:s');
        $checkpasscode->save();
    }

}
