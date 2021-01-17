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

    function view_report_card($idno,$display_type, $school_year, $period = null) {
        if (Auth::user()->accesslevel == env('REG_BE')) {

            $user = \App\User::where('idno', $idno)->first();
            if ($period == null) {
                $status = \App\BedLevel::where('idno', $idno)->where('school_year', $school_year)->first();
            } else {
                $status = \App\BedLevel::where('idno', $idno)->where('school_year', $school_year)->where('period',$period)->first();
            }
            
            
            if ($status->level == "Grade 11" or $status->level == "Grade 12") {
                $get_first_sem_final_ave = \App\ShsOldAveGrade::where('idno', $idno)->first();
                $adviser = \App\AcademicRole::where('level', $status->level)->where('school_year', $status->school_year)->where('period', $period)->where('strand',$status->strand)->where('role', 'advisory')->where('section', $status->section)->first();

                
                $get_subjects_heads = \App\GradeBasicEd::distinct()->where('idno', $idno)->where('school_year', $school_year)->where('period',$period)->orderBy('report_card_grouping', 'desc')->get(['report_card_grouping']);
//                $get_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('period',$period)->orderBy('report_card_grouping', 'desc')->orderBy('sort_to','asc')->get();

                if($period == "2nd Semester"){
                    if($school_year == 2019){
                    $pdf = PDF::loadView('reg_be.report_card.print_report_card_individually_shs_2019', compact('idno', 'school_year', 'absents', 'user', 'status', 'adviser','get_first_sem_final_ave','get_subjects','get_subjects_heads','period','school_year','display_type'));
                    }else{
                    $pdf = PDF::loadView('reg_be.report_card.print_report_card_individually_shs', compact('idno', 'school_year', 'absents', 'user', 'status', 'adviser','get_first_sem_final_ave','get_subjects','get_subjects_heads','period','school_year','display_type'));
                    }
                }else{
                $pdf = PDF::loadView('reg_be.report_card.print_report_card_individually_shs_1st_semester', compact('idno', 'school_year', 'absents', 'user', 'status', 'adviser','get_first_sem_final_ave','get_subjects','get_subjects_heads','period','school_year','display_type'));
                }
                
                $pdf->setPaper(array(0, 0, 720, 576));
                return $pdf->stream("report_card_$idno'_'$school_year'_'$period.pdf");
                
            } else {
                $adviser = \App\AcademicRole::where('level', $status->level)->where('school_year', $status->school_year)->where('role', 'advisory')->where('section', $status->section)->first();

                $get_regular_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 0)->where('is_alpha', 0)->where('is_display_card',1)->orderBy('sort_to', 'asc')->get();
                $get_regular_alpha_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 0)->where('is_alpha', 1)->where('is_display_card',1)->orderBy('sort_to', 'asc')->get();
                $get_group_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 1)->orderBy('sort_to', 'asc')->where('is_display_card',1)->get();
                $get_split_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 2)->where('subject_code', 'not like', "SA%")->where('is_display_card',1)->orderBy('sort_to', 'asc')->get();
                $get_sa_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 2)->where('subject_code', 'like', "SA%")->where('is_display_card',1)->orderBy('sort_to', 'asc')->get();
                //            $get_group_split_subjects = \App\GradeBasicEd::where('idno',$idno)->where('school_year',$school_year)->where('subject_type','>',2)->where(gr)->orderBy('sort_to','asc')->get();

                $get_grouping_subjects = \App\GradeBasicEd::SelectRaw('letter_grade_type,report_card_grouping as subject_name')->where('is_display_card',1)->where('idno', $idno)->where('school_year', $school_year)->where('report_card_grouping', "!=", "")->groupBy('report_card_grouping', 'letter_grade_type')->orderBy('subject_name','DESC')->get();
                $absents = \App\Absent::where('idno', $idno)->where('school_year', $school_year)->get();

                
                if($school_year == 2019){
                $pdf = PDF::loadView('reg_be.report_card.print_report_card_individually_2019', compact('get_regular_subjects', 'get_regular_alpha_subjects', 'get_group_subjects', 'get_split_subjects', 'get_group_split_subjects', 'idno', 'school_year', 'absents', 'user', 'status', 'adviser', 'get_grouping_subjects', 'get_sa_subjects','display_type'));
                }else{
                $pdf = PDF::loadView('reg_be.report_card.print_report_card_individually', compact('get_regular_subjects', 'get_regular_alpha_subjects', 'get_group_subjects', 'get_split_subjects', 'get_group_split_subjects', 'idno', 'school_year', 'absents', 'user', 'status', 'adviser', 'get_grouping_subjects', 'get_sa_subjects','display_type'));
                }
                
                $pdf->setPaper(array(0, 0, 720, 576));
                return $pdf->stream("report_card_$idno'_'$school_year.pdf");
            }
        }
    }

    function narrative_report($idno, $school_year) {
        if (Auth::user()->accesslevel == env('REG_BE')) {

            $user = \App\User::where('idno', $idno)->first();
            $status = \App\BedLevel::where('idno', $idno)->where('school_year', $school_year)->first();
            $adviser = \App\AcademicRole::where('level', $status->level)->where('school_year', $status->school_year)->where('role', 'advisory')->where('section', $status->section)->first();
            $narrative_report = \App\NarrativeGrade::where('idno', $idno)->where('school_year', $school_year)->where('status', 2)->orderBy('id', 'desc')->first();

            $pdf = PDF::loadView('reg_be.report_card.view_narrative_report', compact('idno', 'user', 'status', 'adviser', 'narrative_report', 'school_year'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("narrative_report-$idno.pdf");
        }
    }

    function indicator_report($idno, $school_year) {
        if (Auth::user()->accesslevel == env('REG_BE')) {

            $user = \App\User::where('idno', $idno)->first();
            $status = \App\BedLevel::where('idno', $idno)->where('school_year', $school_year)->first();
            $adviser = \App\AcademicRole::where('level', $status->level)->where('school_year', $status->school_year)->where('role', 'advisory')->where('section', $status->section)->first();
            $records = \App\PreschoolEcr::where('idno', $idno)->where('school_year', $school_year)->get();

            $pdf = PDF::loadView('reg_be.report_card.view_indicator_report', compact('idno', 'user', 'status', 'adviser', 'records', 'school_year'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("indicator_report-$idno.pdf");
        }
    }

}
