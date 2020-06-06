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
            $adviser = \App\AcademicRole::where('level', $status->level)->where('school_year',$status->school_year)->where('role', 'advisory')->where('section',$status->section)->first();
            
            $get_regular_subjects = \App\GradeBasicEd::where('idno',$idno)->where('school_year',$school_year)->where('subject_type',0)->where('is_alpha',0)->orderBy('sort_to','asc')->get();
            $get_regular_alpha_subjects = \App\GradeBasicEd::where('idno',$idno)->where('school_year',$school_year)->where('subject_type',0)->where('is_alpha',1)->orderBy('sort_to','asc')->get();
            $get_group_subjects = \App\GradeBasicEd::where('idno',$idno)->where('school_year',$school_year)->where('subject_type',1)->orderBy('sort_to','asc')->get();
            $get_split_subjects = \App\GradeBasicEd::where('idno',$idno)->where('school_year',$school_year)->where('subject_type',2)->where('subject_code','not like',"SA%")->orderBy('sort_to','asc')->get();
            $get_sa_subjects = \App\GradeBasicEd::where('idno',$idno)->where('school_year',$school_year)->where('subject_type',2)->where('subject_code','like',"SA%")->orderBy('sort_to','asc')->get();
//            $get_group_split_subjects = \App\GradeBasicEd::where('idno',$idno)->where('school_year',$school_year)->where('subject_type','>',2)->where(gr)->orderBy('sort_to','asc')->get();
            
            $get_grouping_subjects = \App\GradeBasicEd::SelectRaw('letter_grade_type,report_card_grouping as subject_name')->where('idno',$idno)->where('school_year',$school_year)->where('report_card_grouping',"!=","")->groupBy('report_card_grouping','letter_grade_type')->get();
            $absents = \App\Absent::where('idno', $idno)->where('school_year', $school_year)->get();
            
            $pdf = PDF::loadView('reg_be.report_card.print_report_card_individually', compact('get_regular_subjects','get_regular_alpha_subjects','get_group_subjects','get_split_subjects','get_group_split_subjects','idno','school_year','absents','user','status','adviser','get_grouping_subjects','get_sa_subjects'));
            $pdf->setPaper('letter', 'landscape');
            return $pdf->stream("report_card_$idno'_'$school_year.pdf");
        }
    }
    
    function narrative_report($idno,$school_year) {
      if (Auth::user()->accesslevel == env('REG_BE')) {
          
            $user = \App\User::where('idno',$idno)->first();
            $status = \App\BedLevel::where('idno', $idno)->where('school_year', $school_year)->first();
            $adviser = \App\AcademicRole::where('level', $status->level)->where('school_year',$status->school_year)->where('role', 'advisory')->where('section',$status->section)->first();
            $narrative_report = \App\NarrativeGrade::where('idno',$idno)->where('school_year',$school_year)->where('status',1)->orderBy('id','desc')->first();
            
            $pdf = PDF::loadView('reg_be.report_card.view_narrative_report', compact('idno','user','status','adviser','narrative_report','school_year'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("reg_be.report_card.view_narrative_report-$idno");
        }
    }


}
