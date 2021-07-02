<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use App\Http\Controllers\Cashier\MainPayment;
use DB;

class BenefitScholar extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("SCHOLARSHIP_BED")) {
            return view('accounting.benefit_scholar.index');
        }
    }

    function bed_index() {
        if (Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("SCHOLARSHIP_BED")) {
            return view('accounting.benefit_scholar.bed_index');
        }
    }

    function scholarship_report() {
        if (Auth::user()->accesslevel == env("SCHOLARSHIP_BED") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            $scholarships = \App\CtrDiscount::where('academic_type', "!=","College")->where('is_display', 1)->where('discount_type', 2)->get();
            return view('scholarship_bed.report.list_of_scholars', compact('scholarships'));
        }
    }

    function print_scholarship_report($scholarship, $school_year) {
        if (Auth::user()->accesslevel == env("SCHOLARSHIP_BED") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            $scholars = \App\BedLevel::where('bed_levels.status', env("ENROLLED"))
                            ->join('users', 'users.idno', 'bed_levels.idno')
                            ->join('bed_scholarships', 'bed_scholarships.idno', 'bed_levels.idno')
                            ->where('school_year', $school_year)
                            ->where('discount_code', "$scholarship")
                            ->orderBy('users.lastname', 'asc')->get();

            $pdf = PDF::loadView('scholarship_bed.report.print_list_of_scholars', compact('school_year', 'scholars','scholarship'));
            $pdf->setPaper(array(0, 0, 792.0, 612.00));
            return $pdf->stream("list_of_scholars.pdf");
        }
    }
}
