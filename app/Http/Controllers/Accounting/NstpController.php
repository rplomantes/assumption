<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use PDF;
use Excel;

class NstpController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            return view('accounting.nstp_reports');
        }
    }

    function get_list(Request $request) {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $students = \App\GradeCollege::
                    join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')
                    ->whereRaw('(college_levels.status > 2)')
                    ->where('college_levels.school_year', $request->school_year)
                    ->where('college_levels.period', $request->period)
                    ->where('grade_colleges.school_year', $request->school_year)
                    ->where('grade_colleges.period', $request->period)
                    ->where('grade_colleges.course_code', 'like', '%NSTP%')
                    ->join('users', 'users.idno', '=', 'grade_colleges.idno')
                    ->orderBy('users.lastname', 'asc')
                    ->get();
            
            if ($request->submit == "print_pdf") {
                $pdf = PDF::loadView('accounting.print_nstp_reports', compact('request', 'students'));
                $pdf->setPaper(array(0, 0, 612, 936));
                return $pdf->stream('nstp_reports.pdf');
            } else {
                ob_end_clean();
                Excel::create('NSTP-REPORT', function($excel) use ($request, $students) {
                    $excel->setTitle("NSTP Report");
                    $excel->sheet("NSTP Report", function ($sheet) use ($request, $students) {
                        $sheet->loadView('accounting.print_nstp_reports_excel', compact('request', 'students'));
                    });
                })->download('xlsx');
            }
        }
    }

}
