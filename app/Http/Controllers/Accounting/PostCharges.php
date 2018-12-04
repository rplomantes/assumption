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
            $dates = date_format($dateToday,'m') - 1;
            DB::beginTransaction();
            $indic = 0;
            foreach ($request->post as $idno) {
                $countLedger = $this->countLedger($idno, $dates);
                $ledger = new \App\Ledger;
                $ledger->idno = $idno;
                $status = \App\Status::where('idno', $idno)->first();
                $school_period = \App\CtrAcademicSchoolYear::where('academic_type', $status->academic_type)->first();
                if ($status->status > 0) {
                    $ledger->program_code = $status->academic_program;
                    $ledger->level = $status->level;
                    $ledger->school_year = $school_period->school_year;
                }
                $ledger->category = "Other Miscellaneous";
                $ledger->subsidiary = "Late Payment Charge";
                $ledger->receipt_details = "Late Payment Charge for the month of " . date('F', strtotime("0000-$request->date-$request->date"));
                $ledger->accounting_code = env('SURCHARGE_CODE');
                $ledger->accounting_name = env('SURCHARGE_NAME');
                $ledger->category_switch = "7";
                $ledger->amount = env('SURCHARGE_AMOUNT') * $countLedger;
                $ledger->save();
                $indic++;

                $posted = new \App\PostedCharges;
                $posted->idno = $idno;
                $posted->due_date = $request->date;
                $posted->date_posted = \Carbon\Carbon::now();
                $posted->amount = env('SURCHARGE_AMOUNT');
                $posted->is_reversed = 0;
                $posted->posted_by = Auth::user()->idno;
                $posted->save();
            }
            DB::commit();
            $levels = \App\CtrAcademicProgram::distinct()->where('academic_type', '!=', 'College')->orderBy('sort_by', 'asc')->get(['level', 'sort_by']);
            $plans = \App\CtrDueDateBed::distinct()->orderBy('plan', 'asc')->get(['plan']);

            \App\Http\Controllers\Admin\Logs::log("Post late payment charges.");
            return view('accounting.post_charges', compact('levels', 'plans', 'indic'));
        }
    }

    function countLedger($idno, $date) {
        $mainledgers = \App\Ledger::where('idno', $idno)->where('category_switch', '<=', '6')->get();
        $duedates = \App\LedgerDueDate::where('idno', $idno)->get();
        $mainpayment = 0;
        $result = 0;
        $due = 0;
        $count = 0;

//    $is_posted = \App\PostedCharges::where('idno',$idno)->where('due_date',$date)->where('is_reversed','0')->first();
        $is_posted = DB::select("SELECT * FROM posted_charges WHERE idno = '$idno' AND due_date = '$date' AND is_reversed = 0");

        foreach ($mainledgers as $payment) {
            $mainpayment = $mainpayment + $payment->payment + $payment->debit_memo;
        }

        foreach ($duedates as $duedate) {
            $due = $due + $duedate->amount;
            $monthdate = date_format(date_create($duedate->due_date), 'm');
            if ($monthdate == $date) {
                if ($mainpayment >= $due) {
                    $result = 2;
                } else {
                    if (count($is_posted) > 0) {
                        $result = 1;
                    } else {
                        $result = 0;
                    }
                }
                $count = $count + 1;
                break;
            } else {
                if ($mainpayment >= $due) {
                    $result = 2;
                } else {
                    if (count($is_posted) > 0) {
                        $result = 1;
                    } else {
                        $result = 0;
                        $count = $count + 1;
                    }
                }
            }
        }
        return $count;
    }

}
