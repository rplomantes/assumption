<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use App\Http\Controllers\Cashier\MainPayment;
use PDF;

class EarlyEnrollment extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function process_cutoff($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $status = \App\Status::where('idno', $idno)->first();
            $enrollment = \App\CtrEnrollmentSchoolYear::where('academic_type', $status->academic_type)->first();
            $current_school_year = $status->school_year;
            $current_period = $status->period;
            $incoming_school_year = $enrollment->school_year;
            $incoming_period = $enrollment->period;

            DB::beginTransaction();
            $this->updateLedgers($idno, $status, $current_school_year, $current_period, $incoming_school_year, $incoming_period);
            $this->old_new($idno, $status, $current_school_year, $current_period, $incoming_school_year, $incoming_period);
            if ($incoming_period != "2nd Semester") {
                $this->update_transactions("Payment", $idno);
                $this->update_transactions("Debit", $idno);
                $this->update_transactions("Student_Deposit", $idno);
            }
            $this->updateStatus($status, $incoming_school_year, $incoming_period);
            \App\Http\Controllers\Admin\Logs::log("Process early enrollment for $idno");
            DB::commit();

            return redirect("/bedregistrar/assess/$idno");
        }
    }

    function updateLedgers($idno, $status, $current_school_year, $current_period, $incoming_school_year, $incoming_period) {

        if ($incoming_period == "2nd Semester") {
            $ledgers = \App\Ledger::where('idno', $idno)->where('level', $status->level)->where('category_switch', '<', 10)->where('period', '!=', $incoming_period)->where('school_year', $incoming_school_year)->get();
        } else {
            if ($status->academic_type == "BED") {
                $ledgers = \App\Ledger::where('idno', $idno)->where('category_switch', '<', 10)->where('school_year', '!=', $incoming_school_year)->get();
            } else {
                $ledgers = \App\Ledger::where('idno', $idno)->where('category_switch', '<', 10)->where('period', '!=', $incoming_period)->where('school_year', '!=', $incoming_school_year)->get();
            }
        }
        foreach ($ledgers as $ledger) {
            $ledger->category_switch = $ledger->category_switch + 10;
            $ledger->save();
        }
    }

    function old_new($idno, $status, $current_school_year, $current_period, $incoming_school_year, $incoming_period) {
        $status->is_new = 0;
        $status->save();
    }

    function update_transactions($type, $idno) {
        if ($type == "Payment") {
            $payments = \App\Payment::where('idno', $idno)->get();
            foreach ($payments as $payment) {
                $payment->is_current = 0;
                $payment->save();
            }
        } else if ($type == "Debit") {
            $payments = \App\DebitMemo::where('idno', $idno)->get();
            foreach ($payments as $payment) {
                $payment->is_current = 0;
                $payment->save();
            }
        } else if ($type == "Student_Deposit") {
            $payments = \App\AddToStudentDeposit::where('idno', $idno)->get();
            foreach ($payments as $payment) {
                $payment->is_current = 0;
                $payment->save();
            }
        }
    }

    function updateStatus($status, $school_year, $period) {
        $status->status = 0;

        if ($status->academic_type == "BED") {
            $status->school_year = $school_year;
        } else {
            $status->school_year = $school_year;
            $status->period = $period;
        }

        $status->save();
    }

}
