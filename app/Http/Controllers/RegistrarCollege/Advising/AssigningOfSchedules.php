<?php

namespace App\Http\Controllers\RegistrarCollege\Advising;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;

class AssigningOfSchedules extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $advising_school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first();

            $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $advising_school_year->school_year)->where('period', $advising_school_year->period)->get();

            return view('reg_college.advising.assigning_of_schedules', compact('advising_school_year', 'idno', 'grades'));
        }
    }

    function assign_schedule(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $course_id = $request->course_id;
            $section_id = $request->section;
            $idno = $request->idno;
            $schedule_id = $request->schedule_id;
            $count = 0;
            $scheds = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            foreach ($scheds as $sched) {
                $lists = \App\GradeCollege::where('course_offering_id', $sched->id)->get();
                if (count($lists) > 0) {
                    foreach ($lists as $list) {
                        $count = $count + 1;
                    }
                }
            }

            if ($count < 35) {

                $update_grade_college = \App\GradeCollege::where('id', $course_id)->first();
                if ($section_id == "dna") {
                    $update_grade_college->course_offering_id = NULL;
                } else {
                    $update_grade_college->course_offering_id = $section_id;
                }
                $update_grade_college->save();

                Session::flash('message', "Schedule Updated!");
            } else {
                Session::flash('danger', "Students enrolled is more than 35 students.");
            }

            \App\Http\Controllers\Admin\Logs::log("Assign schedule to $idno's course_id: $course_id schedule to schedule_id: $schedule_id");
            return redirect("/registrar_college/advising/assigning_of_schedules/$idno");
        }
    }

    function print_schedule($idno) {
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', "College")->first()->school_year;
        $period = \App\CtrEnrollmentSchoolYear::where('academic_type', "College")->first()->period;

        $rooms = \App\ScheduleCollege::distinct()->where('schedule_colleges.school_year', $school_year)->where('schedule_colleges.period', $period)->where('grade_colleges.idno', $idno)->join('course_offerings', 'course_offerings.schedule_id', '=', 'schedule_colleges.schedule_id')->join('grade_colleges', 'grade_colleges.course_offering_id', '=', 'course_offerings.id')->get(array('schedule_colleges.course_code', 'schedule_colleges.schedule_id', 'room', 'day', 'time_start', 'time_end', 'instructor_id'));
        if (count($rooms) > 0) {
            foreach ($rooms as $key => $room) {
                switch ($room->day) {
                    case "M": $room->day = "monday";
                        break;
                    case "T": $room->day = "tuesday";
                        break;
                    case "W": $room->day = "wednesday";
                        break;
                    case "Th": $room->day = "thursday";
                        break;
                    case "F": $room->day = "friday";
                        break;
                    case "S": $room->day = "saturday";
                        break;
                }
                $color_now = "#" . substr($room->schedule_id, -6);
                if ($room->instructor_id != NULL) {
                    $instructor = \App\User::where('idno', $room->instructor_id)->first();
                    $instructor_name = '<br>' . $instructor->firstname . ' ' . $instructor->lastname;
                } else {
                    $instructor_name = "";
                }

                $date = date('Y-m-d', strtotime($room->day . ' this week'));

                $event_array[] = array(
                    'title' => 'Rm. ' . $room->room . '<br><strong>' . $room->course_code . '</strong> ' . $instructor_name,
                    'start' => $date . 'T' . $room->time_start,
                    'end' => $date . 'T' . $room->time_end,
                    'color' => $color_now,
                    "textEscape" => 'false',
                    'textColor' => 'black'
                );
            }

            $event_json = json_encode($event_array);
        } else {
            $event_json = NULL;
        }
        return view('reg_college.advising.print_schedule', compact('event_json','idno'));
    }

}
