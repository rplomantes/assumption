<?php

namespace App\Http\Controllers\Dean\Advising\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class advising_ajax extends Controller {

    //
    function get_section() {
        if (Request::ajax()) {

            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $level = Input::get("level");
            $program_code = Input::get("program_code");

            $sections = \App\CourseOffering::distinct()->where('school_year', $school_year)->where('period', $period)->where('level', $level)->where('program_code', $program_code)->get(['section', 'section_name']);

            $display = "<label>Section</label>"
                    . "<select id=\"section\" name=\"section\" class=\"form-control select2\" onchange=\"get_course_offering(level.value, program_code.value, section.value)\">"
                    . "<option value=\'\'>Select Section</option>";
            foreach ($sections as $section) {
                $display = $display . "<option value=".$section->section.">" . $section->section_name . "</option>";
            }
            $display = $display . "</select>";
            return $display;
        }
    }

    function get_course_offering() {
        if (Request::ajax()) {

            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $level = Input::get("level");
            $program_code = Input::get("program_code");
            $section = Input::get("section");

            $courses_offering = \App\CourseOffering::distinct()->where('school_year', $school_year)->where('period', $period)->where('level', $level)->where('program_code', $program_code)->where('section', $section)->get();

            return view('dean.advising.ajax.get_course_offering', compact('courses_offering', 'level', 'section', 'program_code'));
        }
    }

    function add_to_course_offered() {
        if (Request::ajax()) {
            $idno = Input::get('idno');
            $offering = \App\CourseOffering::find(Input::get('course_offering_id'));
            $checkcourse = \App\GradeCollege::where('idno', $idno)->where('course_code', $offering->course_code)->get();
            if (count($checkcourse) == 0) {
                $newgrade = new \App\GradeCollege;
                $newgrade->idno = $idno;
                $newgrade->course_offering_id = Input::get('course_offering_id');
                $newgrade->course_code = $offering->course_code;
                $newgrade->course_name = $offering->course_name;
                $newgrade->level = $offering->level;
                $newgrade->lec = $offering->lec;
                $newgrade->lab = $offering->lab;
                $newgrade->hours = $offering->hours;
                $newgrade->school_year = $offering->school_year;
                $newgrade->period = $offering->period;
                $newgrade->srf = $offering->srf;
                $newgrade->percent_tuition = $offering->percent_tuition;
                $newgrade->save();
            }
            $studentcourses = \App\GradeCollege::where('idno', $idno)
                    ->where('school_year', $newgrade->school_year)
                    ->where('period', $newgrade->period)
                    ->get();

            if (count($studentcourses) > 0) {
                $data = "<table class=\"table table-striped\" width=\"100%\"><tr><thead><th>Course</th><th>Units</th><th>Room/Schedule</th><th>Instructor</th><th></th></tr></thead><tbody>";
                $units = 0;
                foreach ($studentcourses as $studentcourse) {
                    $data = $data . "<tr><td>" . $studentcourse->course_code . " - " . $studentcourse->course_name
                            . "</td><td>" . ($studentcourse->lec + $studentcourse->lab)
                            . "</td><td>" . $this->getSchedule($studentcourse->course_offering_id)
                            . "</td><td>" . $this->getInstructorId($studentcourse->course_offering_id) . "</td><td><button class=\"btn btn-danger\" onclick=\"removecourse('" . $studentcourse->id . "')\"><span class=\"fa fa-minus-circle\"></span></button></td></tr>";

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

    public function getInstructorId($offeringid) {
        $offering_id = \App\CourseOffering::find($offeringid);
        $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_id->schedule_id)->get(['instructor_id']);
        $data="";
        
        foreach ($schedule_instructor as $get){
            if ($get->instructor_id != NULL){
            $instructor = \App\User::where('idno', $get->instructor_id)->first();
            $data = $instructor->firstname." ".$instructor->lastname." ".$instructor->extensionname;
            return $data;
            
            }
        }
        return $data;
    }

    public function getSchedule($course_offering_id) {
        $schedules = \App\ScheduleCollege::distinct()->where('course_offering_id', $course_offering_id)->get(['time_start', 'time_end', 'room']);
        $data = "";
        $whatDay = "";
        $finalSched = "";

        foreach ($schedules as $schedule) {
            $days = \App\ScheduleCollege::distinct()->where('course_offering_id', $course_offering_id)->where('time_start', $schedule->time_start)->where('time_end', $schedule->time_end)->where('room', $schedule->room)->get(['day']);
            foreach ($days as $day) {
                $whatDay = $whatDay . "" . $day->day;
            }
            $finalSched = $schedule->room . " [" . $whatDay . " " . date('g:i A', strtotime($schedule->time_start)) . " - " . date('g:i A', strtotime($schedule->time_end)) . "]";
            $whatDay = "";
            $data = $data . " " . $finalSched . "<br>";
        }
        return $data;
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
                $data = "<table class=\"table table-striped\" width=\"100%\"><thead><tr><th>Course</th><th>Units</th><th>Room/Schedule</th><th>Instructor</th><th></th></tr></thead><tbody>";
                $units = 0;
                foreach ($studentcourses as $studentcourse) {
                    $data = $data . "<tr><td>" . $studentcourse->course_code . " - " . $studentcourse->course_name
                            . "</td><td>" . ($studentcourse->lec + $studentcourse->lab)
                            . "</td><td>" . $this->getSchedule($studentcourse->course_offering_id)
                            . "</td><td>" . $this->getInstructorId($studentcourse->course_offering_id) . "</td><td><button class=\"btn btn-danger\" onclick=\"removecourse('" . $studentcourse->id . "')\"><span class=\"fa fa-minus-circle\"></span></button></td></tr>";

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
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $search = Input::get("search");

            $courses_offering = \App\CourseOffering::where("school_year", $school_year)->where("period", $period)
                            ->where("course_code", "like", $search . "%")
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
            $section = Input::get("section");

            $offerings = \App\CourseOffering::where("school_year", $school_year)
                            ->where("period", $period)
                            ->where("program_code", $program_code)
                            ->where("level", $level)
                            ->where("section", $section)->get();

            if (count($offerings) > 0) {
                foreach ($offerings as $offering) {
                    if ($this->checkcourse($idno, $offering->course_code)) {
                        $newgrade = new \App\GradeCollege;
                        $newgrade->idno = $idno;
                        $newgrade->course_offering_id = $offering->id;
                        $newgrade->course_code = $offering->course_code;
                        $newgrade->course_name = $offering->course_name;
                        $newgrade->level = $offering->level;
                        $newgrade->school_year = $offering->school_year;
                        $newgrade->period = $offering->period;
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
                    $data = "<table class=\"table table-striped\" width=\"100%\"><tr><thead><th>Course</th><th>Units</th><th>Room/Schedule</th><th>Instructor</th><th></th></tr></thead><tbody>";
                    $units = 0;
                    foreach ($studentcourses as $studentcourse) {
                        $data = $data . "<tr><td>" . $studentcourse->course_code . " - " . $studentcourse->course_name
                                . "</td><td>" . ($studentcourse->lec + $studentcourse->lab)
                                . "</td><td>" . $this->getSchedule($studentcourse->course_offering_id)
                                . "</td><td>" . $this->getInstructorId($studentcourse->course_offering_id) . "</td><td><button class=\"btn btn-danger\" onclick=\"removecourse('" . $studentcourse->id . "')\"><span class=\"fa fa-minus-circle\"></span></button></td></tr>";

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
    }

}