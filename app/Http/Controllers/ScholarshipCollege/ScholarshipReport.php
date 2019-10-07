<?php

namespace App\Http\Controllers\ScholarshipCollege;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use App\Http\Controllers\Cashier\MainPayment;
use DB;

class ScholarshipReport extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function list_of_scholars($school_year,$period) {
        if (Auth::user()->accesslevel == env("SCHOLARSHIP_HED")) {
            $scholars = \App\CollegeLevel::where('college_levels.status', env("ENROLLED"))
                    ->join('users','users.idno', 'college_levels.idno')
                    ->join('college_scholarships','college_scholarships.idno', 'college_levels.idno')
                    ->where('school_year', $school_year)
                    ->where('period', $period)
                    ->where('discount_code','!=', "")
                    ->orderBy('users.lastname', 'asc')->get();
            
            return view('scholarship_hed.report.list_of_scholars', compact('scholars', 'school_year', 'period'));
        }
    }

    function print_list_of_scholars($school_year,$period) {
        if (Auth::user()->accesslevel == env("SCHOLARSHIP_HED")) {
            $scholars = \App\CollegeLevel::where('college_levels.status', env("ENROLLED"))
                    ->join('users','users.idno', 'college_levels.idno')
                    ->join('college_scholarships','college_scholarships.idno', 'college_levels.idno')
                    ->where('school_year', $school_year)
                    ->where('period', $period)
                    ->where('discount_code','!=', "")
                    ->orderBy('users.lastname', 'asc')->get();
            
            $pdf = PDF::loadView('scholarship_hed.report.print_list_of_scholars', compact('school_year', 'period', 'scholars'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("list_of_scholars.pdf");
        }
    }
}
