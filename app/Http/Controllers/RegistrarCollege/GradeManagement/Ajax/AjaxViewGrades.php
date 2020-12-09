<?php

namespace App\Http\Controllers\RegistrarCollege\GradeManagement\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class AjaxViewGrades extends Controller {

    //
    function view_grades() {
        if (Request::ajax()) {
            $course_code = Input::get("course_code");
            $school_year = Input::get("school_year");
            $period = Input::get("period");
//            $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
//            $period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
            $schedules = \App\CourseOffering::distinct()->where('course_code', $course_code)->where('school_year', $school_year)->where('period', $period)->get(['schedule_id']);

            return view('reg_college.grade_management.ajax.display_schedule', compact('schedules', 'course_code'));
        }
    }

    function get_list_students() {
        if (Request::ajax()) {
            $course_code = Input::get("course_code");
            $schedule_id = Input::get("schedule_id");
            $school_year = Input::get("school_year");
            $period = Input::get("period");

            if ($schedule_id == "NULL") {
                $courses_id = \App\CourseOffering::where('course_code', $course_code)->where('school_year',$school_year)->where('period',$period)->get();
                $course_name = \App\CourseOffering::where('course_code', $course_code)->where('school_year',$school_year)->where('period',$period)->first()->course_name;
                return view('reg_college.grade_management.view_students_no_sched', compact('courses_id', 'course_name', 'course_code', 'school_year', 'period'));
            } else {
                $courses_id = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
                $course_name = \App\CourseOffering::where('schedule_id', $schedule_id)->first()->course_name;
                return view('reg_college.grade_management.view_students', compact('courses_id', 'schedule_id', 'course_name', 'school_year', 'period'));
            }
        }
    }

    function get_oldlist_students() {
        if (Request::ajax()) {
            $course_code = Input::get("course_code");
            $school_year = Input::get("school_year");
            $period = Input::get("period");

            return view('reg_college.grade_management.view_oldstudents', compact('course_code', 'school_year', 'period'));
        }
    }

    function lock($idno, $school_year, $period) {
        if (Request::ajax()) {
            $grade_id = Input::get("grade_id");
            $schedule_id = Input::get("schedule_id");
            $courses_id = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id', $schedule_id)->first()->course_name;
            $instructor_idno = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;

            $update_grades = \App\GradeCollege::where('id', $grade_id)->where('idno', $idno)->first();
            $close = \App\CtrCollegeGrading::where('academic_type', "College")->where('idno', $instructor_idno)->first();

            if ($close->midterm == 0) {
                $update_grades->midterm_status = 2;
            }
            if ($close->finals == 0) {
                $update_grades->finals_status = 2;
            }
            $update_grades->save();

            return view('reg_college.grade_management.view_students', compact('courses_id', 'schedule_id', 'course_name', 'school_year', 'period'));
        }
    }

    function unlock($idno, $school_year, $period) {
        if (Request::ajax()) {
            $type = Input::get("type");
            $grade_id = Input::get("grade_id");
            $schedule_id = Input::get("schedule_id");
            $courses_id = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id', $schedule_id)->first()->course_name;
            $instructor_idno = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;


            $update_grades = \App\GradeCollege::where('id', $grade_id)->where('idno', $idno)->first();
            $close = \App\CtrCollegeGrading::where('academic_type', "College")->where('idno', $instructor_idno)->first();

            if ($type == 'midterm') {
                $update_grades->midterm_status = 0;
            }
            if ($type == 'finals') {
                $update_grades->finals_status = 0;
            }
            $update_grades->save();

            return view('reg_college.grade_management.view_students', compact('courses_id', 'schedule_id', 'course_name', 'school_year', 'period'));
        }
    }

    function approve_all_midterm($school_year, $period) {
        if (Request::ajax()) {
            $schedule_id = Input::get("schedule_id");
            $course_code = Input::get("course_code");
            if ($schedule_id == "NULL") {
                $course_offerings = \App\CourseOffering::where('course_code', $course_code)->where('school_year',$school_year)->where('period',$period)->get();
                foreach ($course_offerings as $course_offering) {
                    DB::beginTransaction($course_offering);
                    $this->updateStatus($course_offering, "NONE", 3, 'midterm');
                    \App\Http\Controllers\Admin\Logs::log("Approve and Finalized midterm grades for course $course_code.");
                    DB::commit();
                }
            } else {
                $courses_id = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
                $course_name = \App\CourseOffering::where('schedule_id', $schedule_id)->first()->course_name;

                $course_offerings = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
                $instructor_idno = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;

                foreach ($course_offerings as $course_offering) {
                    DB::beginTransaction($course_offering);
                    $this->updateStatus($course_offering, $instructor_idno, 3, 'midterm');
                    \App\Http\Controllers\Admin\Logs::log("Approve and Finalized midterm grades for schedule id $schedule_id.");
                    DB::commit();
                }
                return view('reg_college.grade_management.view_students', compact('courses_id', 'schedule_id', 'course_name', 'school_year', 'period'));
            }
        }
    }

    function cancel_all_midterm($school_year, $period) {
        if (Request::ajax()) {

            $schedule_id = Input::get("schedule_id");
            $courses_id = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id', $schedule_id)->first()->course_name;

            $course_offerings = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            $instructor_idno = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;

            foreach ($course_offerings as $course_offering) {
                DB::beginTransaction($course_offering);
                $this->updateStatus($course_offering, $instructor_idno, 1, 'midterm');
                \App\Http\Controllers\Admin\Logs::log("Cancel all submission of grades for schedule id $schedule_id.");
                DB::commit();
            }
            return view('reg_college.grade_management.view_students', compact('courses_id', 'schedule_id', 'course_name', 'school_year', 'period'));
        }
    }

    function approve_all_finals($school_year, $period) {
        if (Request::ajax()) {

            $schedule_id = Input::get("schedule_id");
            $course_code = Input::get("course_code");
            if ($schedule_id == "NULL") {
                $course_offerings = \App\CourseOffering::where('course_code', $course_code)->where('school_year',$school_year)->where('period',$period)->get();
                foreach ($course_offerings as $course_offering) {
                    DB::beginTransaction($course_offering);
                    $this->updateStatus($course_offering, "NONE", 3, 'finals');
                    \App\Http\Controllers\Admin\Logs::log("Approve and Finalized midterm grades for course $course_code.");
                    DB::commit();
                }
            } else {
            $courses_id = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id', $schedule_id)->first()->course_name;

            $course_offerings = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            $instructor_idno = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;

            foreach ($course_offerings as $course_offering) {
                DB::beginTransaction($course_offering);
                $this->updateStatus($course_offering, $instructor_idno, 3, 'finals');
                \App\Http\Controllers\Admin\Logs::log("Approve and Finalized all finals grades for schedule id $schedule_id.");
                DB::commit();
            }
            return view('reg_college.grade_management.view_students', compact('courses_id', 'schedule_id', 'course_name', 'school_year', 'period'));
            }
        }
    }

    function cancel_all_finals($school_year, $period) {
        if (Request::ajax()) {

            $schedule_id = Input::get("schedule_id");
            $courses_id = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id', $schedule_id)->first()->course_name;

            $course_offerings = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            $instructor_idno = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;

            foreach ($course_offerings as $course_offering) {
                DB::beginTransaction($course_offering);
                $this->updateStatus($course_offering, $instructor_idno, 1, 'finals');
                \App\Http\Controllers\Admin\Logs::log("Cancel all submission of grades for schedule id $schedule_id.");
                DB::commit();
            }
            return view('reg_college.grade_management.view_students', compact('courses_id', 'schedule_id', 'course_name', 'school_year', 'period'));
        }
    }

    function updateStatus($course_offering, $instructor_idno, $status, $type) {
        $updateStatus = \App\GradeCollege::where('course_offering_id', $course_offering->id)->get();
        foreach ($updateStatus as $update) {
//            $checkstatus = \App\Status::where('idno', $update->idno)->first()->status;
//            if ($checkstatus == 3){
//            $close = \App\CtrCollegeGrading::where('academic_type', "College")->where('idno', $instructor_idno)->first();

            if ($type == 'midterm') {
                if ($update->midterm != NULL && $update->midterm_status != 3) {
                    $update->midterm_status = $status;
                }elseif($instructor_idno == "NONE"){
                    $update->midterm_status = $status;
                }
            }
            if ($type == 'finals') {
                if ($update->finals != NULL && $update->finals_status != 3) {
                    $update->finals_status = $status;
                }elseif($instructor_idno == "NONE"){
                    $update->finals_status = $status;
                }
            }
            $update->save();
        }
//        }
    }

    function change_midterm($idno) {
        if (Request::ajax()) {
            $grade = Input::get("grade");
            $grade_id = Input::get("grade_id");
            $stat = Input::get("stat");

            if ($stat == "old") {
                $update_grades = \App\CollegeGrades2018::where('id', $grade_id)->where('idno', $idno)->first();
                $update_grades->midterm = $grade;
                $update_grades->save();
            } else {
                $update_grades = \App\GradeCollege::where('id', $grade_id)->where('idno', $idno)->first();
                $update_grades->midterm = $grade;
                $update_grades->save();
            }
        }
    }

    function change_finals($idno) {
        if (Request::ajax()) {
            $grade = Input::get("grade");
            $grade_id = Input::get("grade_id");
            $stat = Input::get("stat");

            if ($stat == "old") {
                $update_grades = \App\CollegeGrades2018::where('id', $grade_id)->where('idno', $idno)->first();
                $update_grades->finals = $grade;
                $update_grades->save();
            } else if ($stat == "new") {
                $update_grades = \App\GradeCollege::where('id', $grade_id)->where('idno', $idno)->first();
                $update_grades->finals = $grade;
                $update_grades->save();
            } else if ($stat == "credit") {
                $update_grades = \App\CollegeCredit::where('id', $grade_id)->where('idno', $idno)->first();
                $update_grades->finals = $grade;
                $update_grades->save();
            }else{
                $update_grades = \App\GradeCollege::where('id', $grade_id)->where('idno', $idno)->first();
                $update_grades->finals = $grade;
                $update_grades->save();
            }
        }
    }

    function change_completion($idno) {
        if (Request::ajax()) {
            $grade = Input::get("grade");
            $grade_id = Input::get("grade_id");
            $stat = Input::get("stat");

            if ($stat == "old") {
                $update_grades = \App\CollegeGrades2018::where('id', $grade_id)->where('idno', $idno)->first();
                $update_grades->completion = $grade;
                $update_grades->save();
            } else if ($stat == "new") {
                $update_grades = \App\GradeCollege::where('id', $grade_id)->where('idno', $idno)->first();
                $update_grades->completion = $grade;
                $update_grades->save();
            } else if ($stat == "credit") {
                $update_grades = \App\CollegeCredit::where('id', $grade_id)->where('idno', $idno)->first();
                $update_grades->completion = $grade;
                $update_grades->save();
            }
        }
    }

    function update_midterm() {
        if (Request::ajax()) {
            $idno = Input::get("idno");
            $close = Input::get("close");

            $update = \App\CtrCollegeGrading::where('academic_type', "College")->where('idno', $idno)->first();
            $update->midterm = $close;
            $update->save();
        }
    }

    function update_finals() {
        if (Request::ajax()) {
            $idno = Input::get("idno");
            $close = Input::get("close");

            $update = \App\CtrCollegeGrading::where('academic_type', "College")->where('idno', $idno)->first();
            $update->finals = $close;
            $update->save();
        }
    }

    function generate_card() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $program_code = Input::get("program_code");
            $level = Input::get("level");

            $students = \App\CollegeLevel::where('school_year', $school_year)->where('period', $period)->where('program_code', $program_code)->where('level', $level)->join('users', 'users.idno', 'college_levels.idno')->orderBy('users.lastname')->get();

            return view('reg_college.grade_management.ajax.view_report_card', compact('level', 'program_code', 'school_year', 'period', 'students'));
        }
    }

}
