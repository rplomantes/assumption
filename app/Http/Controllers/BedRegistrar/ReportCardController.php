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
            $pdf->setPaper(array(0, 0, 612, 1008));
            return $pdf->stream("indicator_report-$idno.pdf");
        }
    }

    function view_report_card($idno, $display_type, $school_year, $period = null) {
        if (Auth::user()->accesslevel == env('REG_BE')) {

            $user = \App\User::where('idno', $idno)->first();
            if ($period == null) {
                $status = \App\BedLevel::where('idno', $idno)->where('school_year', $school_year)->first();
            } else {
                $status = \App\BedLevel::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->first();
            }

            if ($status->level == "Grade 11" or $status->level == "Grade 12") {
                return $this->processSHS($idno, $status, $school_year, $period, $display_type, $user);
            } else {
                return $this->processBED($idno, $status, $school_year, $period, $display_type, $user);
            }
        }
    }

    function processBED($idno, $status, $school_year, $period, $display_type, $user) {

        $adviser = \App\AcademicRole::where('level', $status->level)->where('school_year', $status->school_year)->where('role', 'advisory')->where('section', $status->section)->first();

        $get_regular_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 0)->where('is_alpha', 0)->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();
        $get_regular_alpha_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 0)->whereRaw('is_alpha between 1 and 2 ')->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();
        $get_group_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 1)->orderBy('sort_to', 'asc')->where('is_display_card', 1)->get();
        $get_split_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 2)->where('subject_code', 'not like', "SA%")->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();
        $group_split_subjects = \App\GradeBasicEd::distinct()->where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 2)->where('subject_code', 'not like', "SA%")->where('is_display_card', 1)->get(['group_code']);
        $get_sa_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 2)->where('subject_code', 'like', "SA%")->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();

        $get_grouping_subjects = \App\GradeBasicEd::SelectRaw('letter_grade_type,report_card_grouping as subject_name')->where('is_display_card', 1)->where('idno', $idno)->where('school_year', $school_year)->where('report_card_grouping', "!=", "")->groupBy('report_card_grouping', 'letter_grade_type')->orderBy('subject_name', 'DESC')->get();
        $absents = \App\Absent::where('idno', $idno)->where('school_year', $school_year)->get();

        if ($school_year == 2019) {
            $pdf = PDF::loadView('reg_be.report_card.print_report_card_individually_2019', compact('get_regular_subjects', 'get_regular_alpha_subjects', 'get_group_subjects', 'get_split_subjects', 'get_group_split_subjects', 'idno', 'school_year', 'absents', 'user', 'status', 'adviser', 'get_grouping_subjects', 'get_sa_subjects', 'display_type'));
        } else {
            $pdf = PDF::loadView('reg_be.report_card.print_report_card_individually', compact('get_regular_subjects', 'get_regular_alpha_subjects', 'get_group_subjects', 'get_split_subjects', 'get_group_split_subjects', 'idno', 'school_year', 'absents', 'user', 'status', 'adviser', 'get_grouping_subjects', 'get_sa_subjects', 'display_type', 'group_split_subjects'));
        }

        $pdf->setPaper(array(0, 0, 720, 576));
        return $pdf->stream("report_card_$idno'_'$school_year.pdf");
    }

    function processSHS($idno, $status, $school_year, $period, $display_type, $user) {
        $adviser = \App\AcademicRole::where('level', $status->level)->where('school_year', $status->school_year)->where('period', $period)->where('strand', $status->strand)->where('role', 'advisory')->where('section', $status->section)->first();

        $get_subjects_heads = \App\GradeBasicEd::distinct()->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->orderBy('report_card_grouping', 'desc')->get(['report_card_grouping']);

        if ($period == "2nd Semester") {
            if ($school_year == 2019) {
                $get_first_sem_final_ave = \App\ShsOldAveGrade::where('idno', $idno)->first();
                $pdf = PDF::loadView('reg_be.report_card.print_report_card_individually_shs_2019', compact('idno', 'school_year', 'absents', 'user', 'status', 'adviser', 'get_first_sem_final_ave', 'get_subjects', 'get_subjects_heads', 'period', 'school_year', 'display_type'));
            } else {
                $get_first_sem_final_ave = $this->getSHS1stAve($idno, $school_year);
                $get_second_sem_final_ave = $this->getSHS2ndAve($idno, $school_year);
                $pdf = PDF::loadView('reg_be.report_card.print_report_card_individually_shs', compact('idno', 'school_year', 'absents', 'user', 'status', 'adviser', 'get_first_sem_final_ave', 'get_second_sem_final_ave', 'get_subjects', 'get_subjects_heads', 'period', 'school_year', 'display_type'));
            }
        } else {
            if($school_year==2019){
                return "No record found...";
            }
            $get_first_sem_final_ave = $this->getSHS1stAve($idno, $school_year);
            $pdf = PDF::loadView('reg_be.report_card.print_report_card_individually_shs_1st_semester', compact('idno', 'school_year', 'absents', 'user', 'status', 'adviser', 'get_subjects', 'get_subjects_heads', 'period', 'school_year', 'display_type','get_first_sem_final_ave'));
        }

        $pdf->setPaper(array(0, 0, 720, 576));
        return $pdf->stream("report_card_$idno'_'$school_year'_'$period.pdf");
    }

    public static function getSHS1stAve($idno, $school_year) {

        $period = "1st Semester";

        $get_subjects_heads = \App\GradeBasicEd::distinct()->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->orderBy('report_card_grouping', 'desc')->get(['report_card_grouping']);

        $total_units = 0;
        $total_final_grade = 0;
        if (count($get_subjects_heads) > 0) {
            foreach ($get_subjects_heads as $subject_heads) {
                $get_subjects = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('subject_name', 'not like', "%Student Activit%")->where('subject_code', 'not like', "%PEH%")->where('is_alpha', 0)->where('is_display_card', 1)->orderBy('report_card_grouping', 'desc')->orderBy('sort_to', 'asc')->get();
                $get_pe_1st = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', "1st Semester")->where('subject_code', 'like', "%PEH%")->where('is_alpha', 0)->orderBy('report_card_grouping', 'desc')->where('is_display_card', 1)->orderBy('sort_to', 'asc')->first();
                $get_sa = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('subject_name', 'like', "%Student Activit%")->orderBy('report_card_grouping', 'desc')->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();
                $get_conduct = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('is_alpha', 1)->orderBy('report_card_grouping', 'desc')->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();

                if (count($get_subjects) > 0) {
                    foreach ($get_subjects as $subject) {
                        $total_units += $subject->units;
                        if ($subject->units > 0) {
                            $total_final_grade += $subject->first_grading + $subject->second_grading;
                        }
                    }
                }

                if (count($get_pe_1st) > 0) {
                    $pe_average = ($get_pe_1st->first_grading + $get_pe_1st->second_grading) / 2;
                    $total_units += $subject->units;
                }
                if ($subject->units > 0) {
                    $total_final_grade += $pe_average;
                }


                if (count($get_sa) > 0) {
                    foreach ($get_sa as $subject) {
                        $total_units += $subject->units;
                        if ($subject->units > 0) {
                            $total_final_grade += $subject->first_grading + $subject->second_grading;
                        }
                    }
                }

                if (count($get_conduct) > 0) {
                    foreach ($get_conduct as $subject) {
                        $total_units += $subject->units;

                        if ($subject->units > 0) {
                            $total_final_grade += $subject->first_grading + $subject->second_grading;
                        }
                    }
                }
            }
        }
        $get_first_sem_final_ave = new \App\ShsOldAveGrade;
        if ($total_units == 0) {
            $get_first_sem_final_ave->final_grade = 0;
            $get_first_sem_final_ave->final_letter_grade = "not yet done";
        } else {
            $get_first_sem_final_ave->final_grade = round($total_final_grade / $total_units, 3);
            $get_first_sem_final_ave->final_letter_grade = "not yet done";
        }
        return $get_first_sem_final_ave;
    }

    public static function getSHS2ndAve($idno, $school_year) {

        $period = "2nd Semester";

        $get_subjects_heads = \App\GradeBasicEd::distinct()->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->orderBy('report_card_grouping', 'desc')->get(['report_card_grouping']);

        $total_units = 0;
        $total_final_grade = 0;
        if (count($get_subjects_heads) > 0) {
            foreach ($get_subjects_heads as $subject_heads) {
                $get_subjects = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('subject_name', 'not like', "%Student Activit%")->where('subject_code', 'not like', "%PEH%")->where('is_alpha', 0)->where('is_display_card', 1)->orderBy('report_card_grouping', 'desc')->orderBy('sort_to', 'asc')->get();
                $get_pe_2nd = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', "2nd Semester")->where('subject_code', 'like', "%PEH%")->where('is_alpha', 0)->orderBy('report_card_grouping', 'desc')->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();
                $get_pe_1st = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', "1st Semester")->where('subject_code', 'like', "%PEH%")->where('is_alpha', 0)->orderBy('report_card_grouping', 'desc')->where('is_display_card', 1)->orderBy('sort_to', 'asc')->first();
                $get_sa = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('subject_name', 'like', "%Student Activit%")->orderBy('report_card_grouping', 'desc')->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();
                $get_conduct = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('is_alpha', 1)->orderBy('report_card_grouping', 'desc')->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();

                if (count($get_subjects) > 0) {
                    foreach ($get_subjects as $subject) {
                        $total_units += $subject->units;
                        if ($subject->units > 0) {
                            $total_final_grade += $subject->final_grade;
                        }
                    }
                }

                if (count($get_pe_2nd) > 0) {
                    foreach ($get_pe_2nd as $subject) {
                        if (count($get_pe_1st) > 0) {
                            $pe_average = ($subject->third_grading + $get_pe_1st->first_grading + $get_pe_1st->second_grading) / 3;
                        } else {
                            $pe_average = ($subject->first_grading + $subject->second_grading + $subject->third_grading) / 3;
                        }
                        $total_units += $subject->units;
                        if ($subject->units > 0) {
                            $total_final_grade += $pe_average;
                        }
                    }
                }


                if (count($get_sa) > 0) {
                    foreach ($get_sa as $subject) {
                        $total_units += $subject->units;
                        if ($subject->units > 0) {
                            $total_final_grade += $subject->final_grade;
                        }
                    }
                }

                if (count($get_conduct) > 0) {
                    foreach ($get_conduct as $subject) {
                        $total_units += $subject->units;

                        if ($subject->units > 0) {
                            $total_final_grade += $subject->final_grade;
                        }
                    }
                }
            }
        }
        $get_second_sem_final_ave = new \App\ShsOldAveGrade;
        if ($total_units == 0) {
            $get_second_sem_final_ave->final_grade = 0;
            $get_second_sem_final_ave->final_letter_grade = "not yet done";
        } else {
            $get_second_sem_final_ave->final_grade = round($total_final_grade / $total_units, 3);
            $get_second_sem_final_ave->final_letter_grade = "not yet done";
        }
        return $get_second_sem_final_ave;
    }

}
