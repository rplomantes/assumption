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
            $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
            $period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
            $schedules = \App\CourseOffering::distinct()->where('course_code', $course_code)->where('school_year', $school_year)->where('period', $period)->get(['schedule_id']);

            return view('reg_college.grade_management.ajax.display_schedule', compact('schedules'));
        }
    }

    function get_list_students() {
        if (Request::ajax()) {
            $schedule_id = Input::get("schedule_id");

            $courses_id = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id', $schedule_id)->first()->course_name;
            return view('reg_college.grade_management.view_students', compact('courses_id', 'schedule_id', 'course_name'));
        }
    }
    
    function lock($idno) {
        if (Request::ajax()) {
            $grade_id = Input::get("grade_id");
            $schedule_id = Input::get("schedule_id");
            $courses_id = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id', $schedule_id)->first()->course_name;
            
            $update_grades = \App\GradeCollege::where('id', $grade_id)->where('idno', $idno)->first();
            $update_grades->midterm_status = 2;
            $update_grades->finals_status = 2;
            $update_grades->grade_point_status = 2;
            $update_grades->save();
            
            return view('reg_college.grade_management.view_students', compact('courses_id', 'schedule_id', 'course_name'));
        }
    }
    
    function unlock($idno) {
        if (Request::ajax()) {
            $grade_id = Input::get("grade_id");
            $schedule_id = Input::get("schedule_id");
            $courses_id = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id', $schedule_id)->first()->course_name;
            
            $update_grades = \App\GradeCollege::where('id', $grade_id)->where('idno', $idno)->first();
            $update_grades->midterm_status = 0;
            $update_grades->finals_status = 0;
            $update_grades->grade_point_status = 0;
            $update_grades->save();
            
            return view('reg_college.grade_management.view_students', compact('courses_id', 'schedule_id', 'course_name'));
        }
    }
    
    function approve_all(){
        if (Request::ajax()) {
            
            $schedule_id = Input::get("schedule_id");
            $courses_id = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id', $schedule_id)->first()->course_name;
            
            $course_offerings = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            
            foreach ($course_offerings as $course_offering){
                DB::beginTransaction($course_offering);
                    $this->updateStatus($course_offering);
                DB::commit();
            }
                    return view('reg_college.grade_management.view_students', compact('courses_id', 'schedule_id', 'course_name'));
        }
    }
    
    function updateStatus($course_offering){
        $updateStatus = \App\GradeCollege::where('course_offering_id', $course_offering->id)->get();
        foreach ($updateStatus as $update){
            $update->midterm_status = 2;
            $update->finals_status = 2;
            $update->grade_point_status = 2;
            $update->save();
        }
    }

}
