<?php

namespace App\Http\Controllers\Dean\Advising\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class advising_ajax extends Controller {

    //
    function get_course_offering() {
        if (Request::ajax()) {

            $curriculum_year = Input::get("curriculum_year");
            $period = Input::get("period");
            $level = Input::get("level");
            $program_code = Input::get("program_code");
            $section = Input::get("section");

            $courses_offering = \App\Curriculum::distinct()->where('curriculum_year', $curriculum_year)->where('period', $period)->where('level', $level)->where('program_code', $program_code)->get();

            return view('dean.advising.ajax.get_course_offering', compact('courses_offering', 'level', 'section', 'program_code'));
        }
    }

    function add_to_course_offered() {
        if (Request::ajax()) {
            $idno = Input::get('idno');
            $offering = \App\Curriculum::find(Input::get('course_offering_id'));
            $checkcourse = \App\Advising::where('idno', $idno)->where('course_code', $offering->course_code)->get();
            if (count($checkcourse) == 0) {
                $newgrade = new \App\Advising;
                $newgrade->idno = $idno;
                $newgrade->school_year = Input::get('school_year');
                $newgrade->period = Input::get('period');
                $newgrade->program_code = $offering->program_code;
                $newgrade->course_code = $offering->course_code;
                $newgrade->course_name = $offering->course_name;
                $newgrade->course_level = $offering->level;
                $newgrade->course_period = $offering->period;
                $newgrade->lec = $offering->lec;
                $newgrade->lab = $offering->lab;
                $newgrade->hours = $offering->hours;
                $newgrade->save();
            }
            $studentcourses = \App\Advising::where('idno', $idno)
                    ->where('school_year', Input::get('school_year'))
                    ->where('period', Input::get('period'))
                    ->get();

            if (count($studentcourses) > 0) {
                $data = "<table class=\"table table-striped\" width=\"100%\"><tr><thead><th>Course Code</th><th>Course Name</th><th>Units</th><th></th></tr></thead><tbody>";
                $units = 0;
                foreach ($studentcourses as $studentcourse) {
                    $data = $data . "<tr><td>" . $studentcourse->course_code
                            . "</td><td>" . $studentcourse->course_name
                            . "</td><td>" . ($studentcourse->lec + $studentcourse->lab)
                            . "</td><td><button class=\"btn btn-danger\" onclick=\"removecourse('" . $studentcourse->id . "')\"><span class=\"fa fa-minus-circle\"></span></button></td></tr>";

                    $units = $units + $studentcourse->lec + $studentcourse->lab;
                }
                $data = $data . "<tr><td><strong>Total Units</strong></td><td colspan=\"2\"><strong>$units</strong></td></tr>";
                $data = $data . "</tbody></table>";
                return $data;
            } else {
                return "<div class='alert alert-danger'>No Course Selected Yet!!</div>";
            }
        }
    }

    function checkcourse($idno, $course_code) {
        $hassubject = \App\Advising::where('idno', $idno)->where('course_code', $course_code)->get();
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
            $removesubject = \App\Advising::find($id);
            $removesubject->delete();

            $studentcourses = \App\Advising::where('idno', $idno)
                    ->where('school_year', $school_year)
                    ->where('period', $period)
                    ->get();

            if (count($studentcourses) > 0) {
                $data = "<table class=\"table table-striped\" width=\"100%\"><thead><tr><th>Course Code</th><th>Course Name</th><th>Units</th><th></th></tr></thead><tbody>";
                $units = 0;
                foreach ($studentcourses as $studentcourse) {
                    $data = $data . "<tr><td>" . $studentcourse->course_code 
                            . "</td><td>" . $studentcourse->course_name
                            . "</td><td>" . ($studentcourse->lec + $studentcourse->lab)
                            . "</td><td><button class=\"btn btn-danger\" onclick=\"removecourse('" . $studentcourse->id . "')\"><span class=\"fa fa-minus-circle\"></span></button></td></tr>";

                    $units = $units + $studentcourse->lec + $studentcourse->lab;
                }
                $data = $data . "<tr><td><strong>Total Units</strong></td><td colspan=\"4\"><strong>$units</strong></td></tr>";
                $data = $data . "</tbody></table>";
                return $data;
            } else {
                return "<div class='alert alert-danger'>No Course Selected Yet!!</div>";
            }
        }
    }

    function get_offering_per_search() {
        if (Request::ajax()) {
            $search = Input::get("search");

            $courses_offering = \App\Curriculum::where("course_code", "like", $search . "%")
                            ->orWhere("course_name", "like", $search . "%")->get();

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
            $curriculum_year = Input::get("curriculum_year");
            $course_period = Input::get("course_period");

            $offerings = \App\Curriculum::where("curriculum_year", $curriculum_year)
                            ->where("period", $course_period)
                            ->where("program_code", $program_code)
                            ->where("level", $level)->get();

            if (count($offerings) > 0) {
                foreach ($offerings as $offering) {
                    if ($this->checkcourse($idno, $offering->course_code)) {
                        $newgrade = new \App\Advising;
                        $newgrade->idno = $idno;
                        $newgrade->school_year = Input::get('school_year');
                        $newgrade->period = Input::get('period');
                        $newgrade->program_code = $offering->program_code;
                        $newgrade->course_code = $offering->course_code;
                        $newgrade->course_name = $offering->course_name;
                        $newgrade->course_level = $offering->level;
                        $newgrade->course_period = $offering->period;
                        $newgrade->lec = $offering->lec;
                        $newgrade->lab = $offering->lab;
                        $newgrade->hours = $offering->hours;
                        $newgrade->save();
                    }
                }
                
                $studentcourses = \App\Advising::where('idno', $idno)
                    ->where('school_year', Input::get('school_year'))
                    ->where('period', Input::get('period'))
                    ->get();

                if (count($studentcourses) > 0) {
                    $data = "<table class=\"table table-striped\" width=\"100%\"><tr><thead><th>Course Code</th><th>Course Name</th><th>Units</th><th></th></tr></thead><tbody>";
                    $units = 0;
                    foreach ($studentcourses as $studentcourse) {
                        $data = $data . "<tr><td>" . $studentcourse->course_code
                                . "</td><td>" . $studentcourse->course_name
                                . "</td><td>" . ($studentcourse->lec + $studentcourse->lab)
                                . "</td><td><button class=\"btn btn-danger\" onclick=\"removecourse('" . $studentcourse->id . "')\"><span class=\"fa fa-minus-circle\"></span></button></td></tr>";

                        $units = $units + $studentcourse->lec + $studentcourse->lab;
                    }
                    $data = $data . "<tr><td><strong>Total Units</strong></td><td colspan=\"2\"><strong>$units</strong></td></tr>";
                    $data = $data . "</tbody></table>";
                    return $data;
                } else {
                    return "<div class='alert alert-danger'>No Course Selected Yet!!</div>";
                }
            }
        }
    }

}
