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

            $deletesched = \App\ScheduleCollege::where('course_offering_id', $course_offering_id)->get();
            foreach ($deletesched as $delete) {
                $delete->delete();
            }

            $final_start = date("H:i:s", strtotime($time_start));
            $final_end = date("H:i:s", strtotime($time_end));

            $updateCourseOffering = \App\CourseOffering::where('id', $course_offering_id)->first();

            if ($updateCourseOffering->schedule_id == NULL) {
                $schedule_id = uniqid();
                $updateCourseOffering->schedule_id = $schedule_id;
            } else {
                $schedule_id = $updateCourseOffering->schedule_id;
            }
            $updateCourseOffering->save();

            $addSchedule = new \App\ScheduleCollege;
            $addSchedule->course_code = \App\CourseOffering::where('id', $course_offering_id)->first()->course_code;
            $addSchedule->course_offering_id = "$course_offering_id";
            $addSchedule->schedule_id = "$schedule_id";
            $addSchedule->school_year = "$school_year->school_year";
            $addSchedule->period = "$school_year->period";
            $addSchedule->room = "$room";
            $addSchedule->day = "$day";
            $addSchedule->time_start = "$final_start";
            $addSchedule->time_end = "$final_end";
            $addSchedule->save();

            return redirect("/registrar_college/curriculum_management/edit_course_schedule/$course_offering_id");
        }
    }

    function unmerged_schedule($course_offering_id) {

        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {

            $deleteSchedule = \App\CourseOffering::where('id', $course_offering_id)->first();
            $deleteSchedule->schedule_id = NULL;
            $deleteSchedule->save();

            return redirect("/registrar_college/curriculum_management/edit_course_schedule/$course_offering_id");
        }
    }

    function merge_schedule($schedule_id, $course_id) {

        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $course = \App\CourseOffering::where('id', $course_id)->first();
            $course->schedule_id = "$schedule_id";
            $course->save();

            return redirect("/registrar_college/curriculum_management/edit_course_schedule/$course_id");
        }
    }

    function add_tba($course_offering_id) {
        $deletesched = \App\ScheduleCollege::where('course_offering_id', $course_offering_id)->get();
        foreach ($deletesched as $delete) {
            $delete->delete();
        }

        $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first();

        $updateCourseOffering = \App\CourseOffering::where('id', $course_offering_id)->first();
        if ($updateCourseOffering->schedule_id == NULL) {
            $schedule_id = uniqid();
            $updateCourseOffering->schedule_id = $schedule_id;
        } else {
            $schedule_id = $updateCourseOffering->schedule_id;
        }
        $updateCourseOffering->save();

        $addSchedule = new \App\ScheduleCollege;
        $addSchedule->course_code = \App\CourseOffering::where('id', $course_offering_id)->first()->course_code;
        $addSchedule->course_offering_id = "$course_offering_id";
        $addSchedule->schedule_id = "$schedule_id";
        $addSchedule->room = 'TBA';
        $addSchedule->day = 'TBA';
        $addSchedule->school_year = "$school_year->school_year";
        $addSchedule->period = "$school_year->period";
        $addSchedule->time_start = 'TBA';
        $addSchedule->time_end = 'TBA';
        $addSchedule->is_tba = 1;
        $addSchedule->save();

        return redirect("/registrar_college/curriculum_management/edit_course_schedule/$course_offering_id");
    }

}
