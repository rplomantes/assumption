<?php

namespace App\Http\Controllers\Dean\Advising\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class advising_ajax extends Controller {

    //
    function get_curricula() {
        if (Request::ajax()) {

            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $level = Input::get("level");
            $program_code = Input::get("program_code");
            $curriculum_period = Input::get("curriculum_period");
            $curriculum_year = Input::get("curriculum_year");

            $curricula = \App\Curriculum::where('period', $curriculum_period)->where('level', $level)->where('program_code', $program_code)->where('curriculum_year', $curriculum_year)->get();

            return view('dean.advising.ajax.get_course_offering', compact('curricula', 'level', 'section', 'program_code', 'curriculum_year'));
        }
    }

    function add_to_course_offered() {
        if (Request::ajax()) {
            $idno = Input::get('idno');
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $curriculum = \App\Curriculum::find(Input::get('curriculum_id'));
            $checkcourse = \App\GradeCollege::where('idno', $idno)->where('course_code', $curriculum->course_code)->get();
            if (count($checkcourse) == 0) {
                $newgrade = new \App\GradeCollege;
                $newgrade->idno = $idno;
                $newgrade->course_offering_id = NULL;
                $newgrade->course_code = $curriculum->course_code;
                $newgrade->course_name = $curriculum->course_name;
                $newgrade->level = $curriculum->level;
                $newgrade->lec = $curriculum->lec;
                $newgrade->lab = $curriculum->lab;
                $newgrade->hours = $curriculum->hours;
                $newgrade->school_year = $school_year;
                $newgrade->period = $period;
                $newgrade->srf = $curriculum->srf;
                $newgrade->percent_tuition = $curriculum->percent_tuition;
                $newgrade->save();
            }
            $studentcourses = \App\GradeCollege::where('idno', $idno)
                    ->where('school_year', $newgrade->school_year)
                    ->where('period', $newgrade->period)
                    ->get();

            if (count($studentcourses) > 0) {
                $data = "<table class=\"table table-striped\" width=\"100%\"><tr><thead><th>Code</th><th>Course Name</th><th>Lec</th><th>Lab</th><th></th></tr></thead><tbody>";
                $units = 0;
                foreach ($studentcourses as $studentcourse) {
                    $data = $data . "<tr><td>" . $studentcourse->course_code
                            . "</td><td>" . $studentcourse->course_name
                            . "</td><td>" . ($studentcourse->lec)
                            . "</td><td>" . ($studentcourse->lab)
                            . "</td><td><button class=\"btn btn-danger\" onclick=\"removecourse('" . $studentcourse->id . "')\"><span class=\"fa fa-minus-circle\"></span></button></td></tr>";

                    $units = $units + $studentcourse->lec + $studentcourse->lab;
                }
                $data = $data . "<tr><td><strong>Total Units</strong></td><td></td><td></td><td></td><td colspan=\"4\"><strong>$units</strong></td></tr>";
                $data = $data . "</tbody></table>";
                return $data;
            } else {
                return "<div class='alert alert-danger'>No Course Selected Yet!!</div>";
            }
        }
    }

    function checkcourse($idno, $course_code) {
        $hassubject = \App\GradeCollege::where('idno', $idno)->where('course_code', $course_code)->get();
        if (count($hassubject) > 0) {
            return false;
        } else {
            return true;
        }
    }

    function remove_to_course_offered() {
        if (Request::ajax()) {
            $id = Input::get('id');
            $idno = Input::get('idno');
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $removesubject = \App\GradeCollege::find($id);
            $removesubject->delete();

            $studentcourses = \App\GradeCollege::where('idno', $idno)
                    ->where('school_year', $school_year)
                    ->where('period', $period)
                    ->get();

            if (count($studentcourses) > 0) {
                $data = "<table class=\"table table-striped\" width=\"100%\"><tr><thead><th>Code</th><th>Course Name</th><th>Lec</th><th>Lab</th><th></th></tr></thead><tbody>";
                $units = 0;
                foreach ($studentcourses as $studentcourse) {
                    $data = $data . "<tr><td>" . $studentcourse->course_code
                            . "</td><td>" . $studentcourse->course_name
                            . "</td><td>" . ($studentcourse->lec)
                            . "</td><td>" . ($studentcourse->lab)
                            . "</td><td><button class=\"btn btn-danger\" onclick=\"removecourse('" . $studentcourse->id . "')\"><span class=\"fa fa-minus-circle\"></span></button></td></tr>";

                    $units = $units + $studentcourse->lec + $studentcourse->lab;
                }
                $data = $data . "<tr><td><strong>Total Units</strong></td><td></td><td></td><td></td><td colspan=\"4\"><strong>$units</strong></td></tr>";
                $data = $data . "</tbody></table>";
                return $data;
            } else {
                return "<div class='alert alert-danger'>No Course Selected Yet!!</div>";
            }
        }
    }

    function get_offering_per_search() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $search = Input::get("search");

            $courses_offering = \App\Curriculum::
//                    where("school_year", $school_year)->where"school_year", $school_year)->where("period", $period)("period", $period)
                          //  ->
                    where("course_code", "like", $search . "%")
                            ->orWhere("course_name", "like", "%" . $search . "%")->get();

            return view('dean.advising.ajax.get_course_offering_per_search', compact('courses_offering'));
        }
    }

    function add_all_courses() {
        if (Request::ajax()) {
            $idno = Input::get("idno");
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $program_code = Input::get("program_code");
            $level = Input::get("level");
            $curriculum_period = Input::get("curriculum_period");
            $curriculum_year = Input::get("curriculum_year");

            $offerings = \App\Curriculum::where("curriculum_year", $curriculum_year)
                            ->where("program_code", $program_code)
                            ->where("level", $level)
                            ->where("period", $curriculum_period)->get();

            if (count($offerings) > 0) {
                foreach ($offerings as $offering) {
                    if ($this->checkcourse($idno, $offering->course_code)) {
                        $newgrade = new \App\GradeCollege;
                        $newgrade->idno = $idno;
                        $newgrade->course_offering_id = NULL;
                        $newgrade->course_code = $offering->course_code;
                        $newgrade->course_name = $offering->course_name;
                        $newgrade->level = $offering->level;
                        $newgrade->school_year = $school_year;
                        $newgrade->period = $period;
                        $newgrade->lec = $offering->lec;
                        $newgrade->lab = $offering->lab;
                        $newgrade->hours = $offering->hours;
                        $newgrade->srf = $offering->srf;
                        $newgrade->percent_tuition = $offering->percent_tuition;
                        $newgrade->save();
                    }
                }

                $studentcourses = \App\GradeCollege::where('idno', $idno)
                        ->where('school_year', $school_year)
                        ->where('period', $period)
                        ->get();

                if (count($studentcourses) > 0) {
                $data = "<table class=\"table table-striped\" width=\"100%\"><tr><thead><th>Code</th><th>Course Name</th><th>Lec</th><th>Lab</th><th></th></tr></thead><tbody>";
                $units = 0;
                foreach ($studentcourses as $studentcourse) {
                    $data = $data . "<tr><td>" . $studentcourse->course_code
                            . "</td><td>" . $studentcourse->course_name
                            . "</td><td>" . ($studentcourse->lec)
                            . "</td><td>" . ($studentcourse->lab)
                            . "</td><td><button class=\"btn btn-danger\" onclick=\"removecourse('" . $studentcourse->id . "')\"><span class=\"fa fa-minus-circle\"></span></button></td></tr>";

                    $units = $units + $studentcourse->lec + $studentcourse->lab;
                }
                $data = $data . "<tr><td><strong>Total Units</strong></td><td></td><td></td><td></td><td colspan=\"4\"><strong>$units</strong></td></tr>";
                $data = $data . "</tbody></table>";
                return $data;
            } else {
                return "<div class='alert alert-danger'>No Course Selected Yet!!</div>";
            }
            }
        }
    }

}