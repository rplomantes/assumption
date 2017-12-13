<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class CourseScheduleController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            return view('reg_college.curriculum_management.course_schedule');
        }
    }
    
    function edit_course_schedule($id) {

        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {

            $schedules = \App\ScheduleCollege::where('course_offering_id', $id)->get();
            $course_offering = \App\CourseOffering::where('id', $id)->first();

            return view('reg_college.curriculum_management.course_schedule_editor', compact('schedules', 'course_offering'));
        }
    }
    
    function add_course_schedule(Request $request) {

        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            
            $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first();
            $day = $request->day;
            $time_start = $request->time_start;
            $time_end = $request->time_end;
            $course_offering_id = $request->course_offering_id;
            $room = $request->room;
            
            $final_start = date("H:i:s", strtotime($time_start));
            $final_end = date("H:i:s", strtotime($time_end));
                    
            $addSchedule = new \App\ScheduleCollege;
            $addSchedule->course_offering_id="$course_offering_id";
            $addSchedule->school_year="$school_year->school_year";
            $addSchedule->period="$school_year->period";
            $addSchedule->room="$room";
            $addSchedule->day="$day";
            $addSchedule->time_start="$final_start";
            $addSchedule->time_end="$final_end";
            $addSchedule->save();
            
            return redirect("/registrar_college/curriculum_management/edit_course_schedule/$course_offering_id");

        }
    }
    
    function delete_course_schedule($course_offering_id, $schedule_id) {

        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            
            $deleteSchedule = \App\ScheduleCollege::where('id', $schedule_id)->first();
            $deleteSchedule->delete();
            
            return redirect("/registrar_college/curriculum_management/edit_course_schedule/$course_offering_id");

        }
    }

}
