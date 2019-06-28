<?php

namespace App\Http\Controllers\ScholarshipCollege;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use App\Http\Controllers\Cashier\MainPayment;
use DB;

class ViewScholarship extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env("SCHOLARSHIP_HED")) {
            $scholar = \App\CollegeScholarship::where('idno', $idno)->first();
            return view('scholarship_hed.view_scholarship.view', compact('scholar', 'idno'));
        }
    }

    function update_now(Request $request) {
        if (Auth::user()->accesslevel == env("SCHOLARSHIP_HED")) {
            $scholar = \App\CollegeScholarship::where('idno', $request->idno)->first();
            if ($request->discount_code == "") {
                $scholar->discount_code = "";
                $scholar->discount_description = "";
                $scholar->accounting_code = "";
                $scholar->tuition_fee = 0;
                $scholar->other_fee = 0;
                $scholar->misc_fee = 0;
                $scholar->depository_fee = 0;
            } else {
                $scholar->discount_code = "$request->discount_code";
                $scholar->discount_description = \App\CtrDiscount::where('discount_code', $request->discount_code)->first()->discount_description;
                $scholar->accounting_code = \App\CtrDiscount::where('discount_code', $request->discount_code)->first()->accounting_code;
                $scholar->tuition_fee = $request->tf;
                $scholar->other_fee = $request->of;
                $scholar->misc_fee = $request->of;
                $scholar->depository_fee = $request->of;
            }
            $scholar->save();
            $this->updateAdmissionHED($request);
            \App\Http\Controllers\Accounting\SetReceiptController::log("Update scholarship of" . $request->idno);
            return redirect(url('/scholarship_college/view_scholar/' . $request->idno));
        }
    }

    function updateAdmissionHED($request) {
        $scholar = \App\AdmissionHed::where('idno', $request->idno)->first();
        if ($request->discount_code == "") {
            $scholar->admission_status = "";
            $scholar->assumption_scholar = "";
        } else {
            $scholar->admission_status = "Scholar";
            $scholar->assumption_scholar = \App\CtrDiscount::where('discount_code', $request->discount_code)->first()->discount_description;
        }
        $scholar->save();
    }

    function view_schedule($idno) {
        if (Auth::user()->accesslevel == env("SCHOLARSHIP_HED")) {
            $status = \App\Status::where('idno', $idno)->first();
            $school_year = $status->school_year;
            $period = $status->period;
            if ($status->status >= env('ENROLLED')) {
                return view('scholarship_hed.view_schedule', compact('status', 'idno', 'school_year', 'period'));
            } else {
                return redirect(url('/'));
            }
        }
    }

}
