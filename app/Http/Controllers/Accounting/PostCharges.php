<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon;

class PostCharges extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $indic = 0;
            $levels = \App\CtrAcademicProgram::distinct()->where('academic_type', '!=', 'College')->orderBy('sort_by', 'asc')->get(['level', 'sort_by']);
            $plans = \App\CtrDueDateBed::distinct()->orderBy('plan', 'asc')->get(['plan']);
            return view('accounting.post_charges', compact('levels', 'plans', 'indic'));
        }
    }

    public function postCharges(Request $request) {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $dateToday = Carbon\Carbon::now();
            $dates = sprintf("%02d", date_format($dateToday, 'm') - 1);
            $dates2 = date_format($dateToday, "Y-$dates-31");
            if ($dates == 0) {
                $dates = 12;
                $dates2 = date_format($dateToday, "Y-$dates-31");
            }
            DB::beginTransaction();
            $indic = 0;
            foreach ($request->post as $idno) {

                $status = \App\Status::where('idno', $idno)->first();
                $school_period = \App\CtrAcademicSchoolYear::where('academic_type', $status->academic_type)->first();
                if ($status->academic_type == 'BED') {
                    $duedates = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_period->school_year)->get();
                } else {
                    $duedates = \App\LedgerDueDate::where('idno', $idno)->where('period', $school_period->period)->where('school_year', $school_period->school_year)->get();
                }

                $countLedger = $this->countLedger($idno, $dates, "payments");
                $countLedger2 = $this->countLedger($idno, $dates, "nopayments");
                $lastpay = $this->countLedger($idno, $dates, "lastpay");
                $noOfDues = $this->countLedger($idno, $dates, "duedates");
                if ($lastpay == "upon enrollment") {
                    if ($status->academic_type == 'BED') {
                        $lastpay = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_period->school_year)->where('due_switch', 0)->first()->due_date;
                    } else {
                        $lastpay = \App\LedgerDueDate::where('idno', $idno)->where('period', $school_period->period)->where('due_switch', 0)->where('school_year', $school_period->school_year)->first()->due_date;
                    }
                $numberOfMonths = 0;
                }else{
//                $numberOfMonths = abs((date('Y', strtotime($dates2)) - date('Y', strtotime($lastpay))) * 12 + (date('m', strtotime($dates2)) - date('m', strtotime($lastpay))));
                    $numberOfMonths = $noOfDues - $countLedger;
                }
if($numberOfMonths > 0){
                $ledger = new \App\Ledger;
                $ledger->idno = $idno;
                if ($status->status > 0) {
                    $ledger->program_code = $status->academic_program;
                    $ledger->level = $status->level;
                    $ledger->school_year = $school_period->school_year;
                    if ($status->academic_type == 'SHS') {
                        $ledger->period = $school_period->period;
                    }
                }
                $ledger->category = "Other Miscellaneous";
                $ledger->subsidiary = "Late Payment Charge";
                $ledger->receipt_details = "Late Payment Charge for the month of " . date('F', strtotime("0000-$request->date-$request->date"));
                $ledger->accounting_code = env('SURCHARGE_CODE');
                $ledger->accounting_name = env('SURCHARGE_NAME');
                $ledger->category_switch = "7";
                $ledger->amount = env('SURCHARGE_AMOUNT') * $numberOfMonths;
                $ledger->save();
                $indic++;

                $posted = new \App\PostedCharges;
                $posted->idno = $idno;
                $posted->due_date = $request->date;
                $posted->date_posted = \Carbon\Carbon::now();
                $posted->amount = env('SURCHARGE_AMOUNT') * $numberOfMonths;
                $posted->is_reversed = 0;
                $posted->posted_by = Auth::user()->idno;
                $posted->save();
}
            }
            DB::commit();
            $levels = \App\CtrAcademicProgram::distinct()->where('academic_type', '!=', 'College')->orderBy('sort_by', 'asc')->get(['level', 'sort_by']);
            $plans = \App\CtrDueDateBed::distinct()->orderBy('plan', 'asc')->get(['plan']);

            \App\Http\Controllers\Admin\Logs::log("Post late payment charges.");
            return view('accounting.post_charges', compact('levels', 'plans', 'indic'));
        }
    }

    function countLedger($idno, $date, $type) {
        $academic_type = \App\Status::where('idno', $idno)->first();
        $school_year = \App\CtrAcademicSchoolYear::where('academic_type', $academic_type->academic_type)->first();
        if ($academic_type->academic_type == 'BED') {
            $mainledgers = \App\Ledger::where('idno', $idno)->where('school_year', $school_year->school_year)->where('category_switch', '<=', 6)->get();
            $duedates = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_year->school_year)->get();
        } else {
            $mainledgers = \App\Ledger::where('idno', $idno)->where('period', $school_year->period)->where('school_year', $school_year->school_year)->where('category_switch', '<=', 6)->get();
            $duedates = \App\LedgerDueDate::where('idno', $idno)->where('period', $school_year->period)->where('school_year', $school_year->school_year)->get();
        }
        $mainpayment = 0;
        $result = 0;
        $due = 0;
        $count = 0;
        $remain = 0;

//    $is_posted = \App\PostedCharges::where('idno',$idno)->where('due_date',$date)->where('is_reversed','0')->first();
        $is_posted = DB::select("SELECT * FROM posted_charges WHERE idno = '$idno' AND due_date = '$date' AND is_reversed = 0");

        foreach ($mainledgers as $payment) {
            $mainpayment = $mainpayment + $payment->payment + $payment->debit_memo;
        }

        $dateToday = Carbon\Carbon::now();
        $dates1 = sprintf("%02d", date_format($dateToday, 'm') - 1);
        $dates2 = date_format($dateToday, "Y-$dates1-31");
        if ($dates1 == 0) {
            $dates1 = 12;
            $dates2 = date_format($dateToday, "Y-$dates1-31");
        }

        $totalpay = $mainpayment;
        foreach ($duedates as $duedate) {

            $due = $due + $duedate->amount;
            $monthdate = date_format(date_create($duedate->due_date), 'm');
            if ($duedate->due_switch == 0) {
                if ($totalpay >= $duedate->amount) {
                    $totalpay = $totalpay - $duedate->amount;
                    $lastpay = "";
                    $count = $count +1;
                } else {
                    $totalpay = 0;
                    $count = $count;
                }
                $lastpay = "upon enrollment";
                $remain = $remain;
            } else {
                if ($duedate->due_date <= $dates2) {
                    if ($totalpay >= $duedate->amount) {
                        $totalpay = $totalpay - $duedate->amount;
                        $lastpay = $duedate->due_date;
                        $count = $count + 1;
                    } else {
                        $totalpay = 0;
                        $lastpay = $duedate->due_date;
                        $count = $count;
                        break;
                    }
                } else {
                    $remain = $remain + 1;
                }
            }
        }
        if ($type == "payments") {
            return $count;
        } else if ($type == "nopayments") {
            return $remain;
        } else if ($type == "duedates"){
            return count($duedates);
        } else {
            return $lastpay;
        }
    }

}
