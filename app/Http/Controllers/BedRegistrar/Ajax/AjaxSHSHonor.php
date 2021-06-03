<?php

namespace App\Http\Controllers\BedRegistrar\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Excel;

class AjaxSHSHonor extends Controller {

    //
    function get_students() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $level = Input::get("level");

            $lists3 = $this->getStudentCompute($school_year, $level);

            return view('reg_be.ajax.get_shs_honor', compact('lists3', 'school_year','level'));
        }
    }

    function export($level, $school_year) {

        $lists3 = $this->getStudentCompute($school_year, $level);

        ob_end_clean();
        Excel::create('shs_honor', function($excel) use ($lists3, $level, $school_year) {
            $excel->setTitle($level.'-'.$school_year);

            $excel->sheet($level, function ($sheet) use ($lists3, $level,$school_year) {
                $sheet->loadView('reg_be.ajax.get_shs_honor', compact('lists3', 'level', 'strand', 'school_year'));
            });
        })->download('xlsx');
    }
    
    function getStudentCompute($school_year, $level) {
        if ($level == "Grade 11" or $level == "Grade 12") {
            $period = "2nd Semester";
            $list = \App\BedLevel::where('level', $level)->where('school_year', $school_year)->where('status', env('ENROLLED'))->where('period', $period)->get();
        }

        $lists = collect();
        foreach ($list as $lists2) {
            $lists->push($this->getLists($lists2->idno, $school_year, $period, $level, $lists2));
        }
        return $lists->sortByDesc('totalAve');
    }
    
    function getLists($idno, $school_year, $period, $level, $lists2) {
        $user = \App\User::where('idno', $idno)->first();
        $array2 = array();
        $array2['idno'] = $idno;
        $array2['lastname'] = $user->lastname;
        $array2['firstname'] = $user->firstname;
        $array2['middlename'] = $user->middlename;
        $array2['section'] = $lists2->section;
        $array2['strand'] = $lists2->strand;
        $array2['acadSem1'] = \App\Http\Controllers\BedRegistrar\ReportCardController::getSHS1stAve($idno, $school_year)->final_grade;
        $array2['acadSem2'] = \App\Http\Controllers\BedRegistrar\ReportCardController::getSHS2ndAve($idno, $school_year)->final_grade;
        $array2['acadWhole'] = ($array2['acadSem1'] + $array2['acadSem2']) / 2;
        $array2['acadWholeAve'] = $array2['acadWhole'] * 0.7;
        $array2['saSem1'] = $this->getSacGrades('2ndQTR', $idno, $school_year, $period, $level, 'number');
        $array2['saSem2'] = $this->getSacGrades('4thQTR', $idno, $school_year, $period, $level, 'number');
        $array2['saWhole'] = ($array2['saSem1'] + $array2['saSem2']) / 2;
        $array2['saWholeAve'] = $array2['saWhole'] * 0.3;
        $array2['totalAve'] = $array2['acadWholeAve'] + $array2['saWholeAve'];

        return $array2;
    }

    function getSacGrades($qtr, $idno, $schoolyear, $period, $level, $type) {
        //if you update this please update also the ff:
        //grade_summary_sac_view_list.blade.php

        if ($qtr == "2ndQTR") {
            $per = "1st Semester";
        } else {
            $per = "2nd Semester";
        }
        $get_grades = \App\GradeBasicEd::where('subject_name', 'like', 'Student Activities%')
                        ->where('period', $per)
                        ->where('idno', $idno)
                        ->where('school_year', $schoolyear)
                        ->where(function ($query) {
                            $query->where('level', "Grade 11")
                            ->orWhere('level', "Grade 12");
                        })->first();

////////////
        if (count($get_grades) == 0) {
            if ($qtr == "2ndQTR") {
                $per = "2nd Semester";
            } else {
                $per = "1st Semester";
            }
            $get_grades = \App\GradeBasicEd::where('subject_name', 'like', 'Student Activities%')
                            ->where('period', $per)
                            ->where('idno', $idno)
                            ->where('school_year', $schoolyear)
                            ->where(function ($query) {
                                $query->where('level', "Grade 11")
                                ->orWhere('level', "Grade 12");
                            })->first();
        }


/////////////////
        if ($qtr == "2ndQTR") {
            if ($type == 'number') {
                return $get_grades['second_grading'];
            } else {
                return getTransmu($get_grades['second_grading']);
            }
        } else {
            if ($type == 'number') {
                return $get_grades['fourth_grading'];
            } else {
                return getTransmu($get_grades['fourth_grading']);
            }
        }
    }

    function getTransmu($getAverage) {
        $letter_grade = \App\CtrTransmuLetter::where('grade', round($getAverage))->where('letter_grade_type', 'SAC')->first();
        return $letter_grade['letter_grade'];
    }

}
