<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use PDF;

class ReportCardController extends Controller {

    //

    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $students = \App\Status::where('academic_type', "BED")->where('status', env("ENROLLED"))->get();
            return view("reg_be.report_card.student_list", compact('students'));
        }
    }

    function view_report_card($idno,$school_year) {
        if (Auth::user()->accesslevel == env('REG_BE')) {
            
            $user = \App\User::where('idno',$idno)->first();
            $status = \App\BedLevel::where('idno', $idno)->where('school_year', $school_year)->first();
            $adviser = \App\AcademicRole::where('level', $status->level)->where('school_year',$status->school_year)->where('role', 'advisory')->first();
            $get_subjects = \App\GradeBasicEd::where('idno',$idno)->where('school_year',$school_year)->orderBy('sort_to','asc')->get();
            $absents = \App\Absent::where('idno', $idno)->where('school_year', $school_year)->get();
            
            $pdf = PDF::loadView('reg_be.report_card.print_report_card_individually', compact('get_subjects','idno','school_year','absents','user','status','adviser'));
            $pdf->setPaper('letter', 'landscape');
            return $pdf->stream("report_card_$idno'_'$school_year.pdf");
        }
    }

}
