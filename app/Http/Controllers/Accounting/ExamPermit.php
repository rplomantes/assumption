<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use PDF;

class ExamPermit extends Controller {

//
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            return view('accounting.exam_permit');
        }
    }

    function print_now($school_year, $period, $exam_period,$idno) {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            $user = \App\User::where('idno',$idno)->first();
            $status = \App\Status::where('idno',$idno)->first();
            $grade_colleges = \App\GradeCollege::where('school_year', $school_year)->where('period', $period)->where('idno', $idno)->where('is_dropped', 0)->get();
            
            $pdf = PDF::loadView('accounting.print_exam_permit_hed', compact('school_year','period','idno','grade_colleges','user','status','exam_period'));
            $pdf->setPaper(array(0,0,306,396));
            return $pdf->stream('exam_permit-'.$idno.'.pdf');
        }
    }
    function print_all(Request $request) {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            
            $school_year = $request->school_year;
            $level = $request->level;
            $period = $request->period;
            $exam_period = $request->exam_period;

            $lists = \App\CollegeLevel::join('users', 'users.idno','=', 'college_levels.idno')->where('school_year', $school_year)->where('period', $period)->where('level', $level)->where('college_levels.status', env('ENROLLED'))->orderBy('users.lastname', 'asc')->get(array('users.lastname','users.firstname','users.middlename','users.extensionname','college_levels.level','college_levels.program_code','users.idno'));
            
            $pdf = PDF::loadView('accounting.printall_exam_permit_hed', compact('lists','school_year','period','level','exam_period'));
            $pdf->setPaper('letter','portrait');
            return $pdf->stream('exam_permit_all.pdf');
        }
    }

}
