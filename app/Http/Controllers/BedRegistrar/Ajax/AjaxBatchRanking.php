<?php

namespace App\Http\Controllers\BedRegistrar\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use PDF;

class AjaxBatchRanking extends Controller {

    //
    function get_students() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $level = Input::get("level");
            if ($level == "Grade 11" or $level == "Grade 12") {
                $period = "2nd Semester";
                $list = \App\BedLevel::where('level', $level)->where('school_year', $school_year)->where('period', $period)->get();
            } else {
                $period = "";
                $list = \App\BedLevel::where('level', $level)->where('school_year', $school_year)->get();
            }
            $lists = collect();
            foreach ($list as $lists2) {
                $lists->push($this->getLists($lists2->idno, $school_year, $period, $level, $lists2));
            }
            $lists3 = $lists->sortByDesc('gpa');
            return view('reg_be.ajax.get_batch_students', compact('lists3', 'school_year'));
        }
    }

//    function get_students2($school_year, $level) {
//
//        if ($level == "Grade 11" or $level == "Grade 12") {
//            $period = "2nd Semester";
//            $list = \App\BedLevel::where('level', $level)->where('school_year', $school_year)->where('period', $period)->get();
//        } else {
//            $list = \App\BedLevel::where('level', $level)->where('school_year', $school_year)->get();
//        }
//
//        $lists = collect();
//        foreach ($list as $lists2) {
//            $lists->push($this->getLists($lists2->idno));
//        }
//        $lists3 = $lists->sortBy('gpa');
//
//        $pdf = PDF::loadView('reg_college.graduates.print_batch_students', compact('lists3', 'date_of_grad'));
//        $pdf->setPaper('letter', 'portrait');
//        return $pdf->stream("batch_ranking.pdf");
//    }

    function getLists($idno, $school_year, $period, $level, $lists2) {
        $user = \App\User::where('idno', $idno)->first();
        $array2 = array();
        $array2['idno'] = $idno;
        $array2['lastname'] = $user->lastname;
        $array2['firstname'] = $user->firstname;
        $array2['middlename'] = $user->middlename;
        $array2['section'] = $lists2->section;
        $array2['strand'] = $lists2->strand;
        if ($level == "Grade 11" or $level == "Grade 12") {
            $array2['gpa'] = $this->get_gpa_shs($idno, $school_year, $period);
        } else {
            $array2['gpa'] = $this->get_gpa_bed($idno, $school_year, $period);
        }

        return $array2;
    }

    function get_gpa_bed($idno, $school_year, $period) {

        $get_regular_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 0)->where('is_alpha', 0)->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();
        $get_regular_alpha_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 0)->where('is_alpha', 1)->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();
        $get_group_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 1)->orderBy('sort_to', 'asc')->where('is_display_card', 1)->get();
        $get_split_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 2)->where('subject_code', 'not like', "SA%")->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();
        $get_sa_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 2)->where('subject_code', 'like', "SA%")->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();

        $get_grouping_subjects = \App\GradeBasicEd::SelectRaw('letter_grade_type,report_card_grouping as subject_name')->where('is_display_card', 1)->where('idno', $idno)->where('school_year', $school_year)->where('report_card_grouping', "!=", "")->groupBy('report_card_grouping', 'letter_grade_type')->get();

        $total_units = 0;
        $total_final_grade = 0;
        if (count($get_regular_subjects) > 0) {
            foreach ($get_regular_subjects as $subject) {
                $total_units += $subject->units;
                if ($subject->units > 0) {
                    $total_final_grade += $subject->final_grade;
                }
            }
        }

        if (count($get_group_subjects) > 0) {
            foreach ($get_group_subjects as $subject) {
                $total_units += $subject->units;
                if ($subject->units > 0) {
                    $total_final_grade += $subject->final_grade;
                }
            }
        }

        if (count($get_split_subjects) > 0) {
            foreach ($get_split_subjects as $subject) {
                $total_units += $subject->units;
                if ($subject->units > 0) {
                    $total_final_grade += $subject->final_grade;
                }
            }
        }

        if (count($get_grouping_subjects) > 0) {
            $grade1 = 0;
            $grade2 = 0;
            $grade3 = 0;
            foreach ($get_grouping_subjects as $subject) {
                $total_units += $this->getUnits($subject, $idno, $school_year);
                $grade1 = $this->getGrades($subject, $idno, $school_year, '1');
                $grade2 = $this->getGrades($subject, $idno, $school_year, '2');
                $grade3 = $this->getGrades($subject, $idno, $school_year, '3');
                $grade = ($grade1 + $grade2 + $grade3) / 3;
                if ($total_units > 0) {
                    $total_final_grade += $grade;
                }
            }
        }



        if (count($get_sa_subjects) > 0) {
            foreach ($get_sa_subjects as $subject) {
                $total_units += $subject->units;
                if ($subject->units > 0) {
                    $total_final_grade += $subject->final_grade;
                }
            }
        }


        if (count($get_regular_alpha_subjects) > 0) {
            foreach ($get_regular_alpha_subjects as $subject) {
                $total_units += $subject->units;
                if ($subject->units > 0) {
                    $total_final_grade += $subject->final_grade;
                }
            }
        }
        return round($total_final_grade / $total_units, 3);
    }

    function get_gpa_shs($idno, $school_year, $period) {
        $get_first_sem_final_ave = \App\ShsOldAveGrade::where('idno', $idno)->first();
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
        return round(($get_first_sem_final_ave->final_grade + round($total_final_grade / $total_units, 3)) / 2, 3);
    }

    function getGrades($subject, $idno, $school_year, $period) {
        $getsubjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('report_card_grouping', $subject->subject_name)->get();
        $final_grade = 0;
        foreach ($getsubjects as $get) {
            switch ($period) {
                case "1":
                    if ($get->subject_code != "COMP1" && $get->subject_code != "COMP2" && $get->subject_code != "COMP3" && $get->subject_code != "COMP4" && $get->subject_code != "COMP5" && $get->subject_code != "COMP6" && $get->subject_code != "COMP7" && $get->subject_code != "COMP8") {
                        $final_grade += $get->first_grading * ($get->units);
                        if ($get->group_code == "EPP5" || $get->group_code == "EPP4" || $get->group_code == "EPP6" || $get->group_code == "TLE7" || $get->group_code == "TLE8" || $get->group_code == "TLE9" || $get->group_code == "TLE10") {
                            $final_grade = $final_grade + ($get->first_grading * (1 - $get->units));
                            return $final_grade;
                        }
                    } else {
                        $final_grade += 100 * ($get->units);
                    }
                    break;
                case "2":
                    if ($get->subject_code != "COMP1" && $get->subject_code != "COMP2" && $get->subject_code != "COMP3" && $get->subject_code != "COMP4" && $get->subject_code != "COMP5" && $get->subject_code != "COMP6" && $get->subject_code != "COMP7" && $get->subject_code != "COMP8") {
                        $final_grade += $get->second_grading * ($get->units);
                        if ($get->group_code == "EPP5" || $get->group_code == "EPP4" || $get->group_code == "EPP6" || $get->group_code == "TLE7" || $get->group_code == "TLE8" || $get->group_code == "TLE9" || $get->group_code == "TLE10") {
                            $final_grade = $final_grade + ($get->second_grading * (1 - $get->units));
                            return $final_grade;
                        }
                    } else {
                        $final_grade += 100 * ($get->units);
                    }
                    break;
                case "3":
                    $final_grade += $get->third_grading * ($get->units);
                    break;
                case "4":
                    $final_grade += $get->fourth_grading * ($get->units);
                    break;
            }
        }
        return $final_grade;
    }

    function getUnits($subject, $idno, $school_year) {
        $getsubjects = \App\GradeBasicEd::selectRaw('sum(units) as units')->where('idno', $idno)->where('school_year', $school_year)->where('report_card_grouping', $subject->subject_name)->first();

        return $getsubjects->units;
    }

//get final rating for grouping
    function getFinalRating($grade, $letter_grade_type) {
        $round = round($grade);
        $round2 = round($grade, 2);
        $final_letter_grade = \App\CtrTransmuLetter::where('grade', $round)->where('letter_grade_type', $letter_grade_type)->first();
        $letter = $final_letter_grade['letter_grade'];
        return "$letter($round2)";
    }

//get letter grade transmutation
    function getLetterGrade($grade, $letter_grade_type) {
        $round = round($grade);
        $final_letter_grade = \App\CtrTransmuLetter::where('grade', $round)->where('letter_grade_type', $letter_grade_type)->first();
        $letter = $final_letter_grade['letter_grade'];
        return "$letter";
    }

}
