<?php

namespace App\Http\Controllers\CollegeInstructor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class MyScheduleController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('INSTRUCTOR')) {
            return view('college_instructor.my_schedule');
        }
    }

    function print_my_schedule($school_year, $period) {

        $rooms = \App\ScheduleCollege::distinct()->where('schedule_colleges.school_year', $school_year)->where('schedule_colleges.period', $period)->where('schedule_colleges.instructor_id', Auth::user()->idno)->join('course_offerings', 'course_offerings.schedule_id', '=', 'schedule_colleges.schedule_id')->get(array('schedule_colleges.course_code', 'schedule_colleges.schedule_id', 'room', 'day', 'time_start', 'time_end', 'instructor_id'));
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
                    $instructor_name = $instructor->firstname . ' ' . $instructor->lastname;
                } else {
                    $instructor_name = "";
                }

                $date = date('Y-m-d', strtotime($room->day . ' this week'));

                $event_array[] = array(
                    'title' => 'Rm. ' . $room->room . '<br><strong>' . $room->course_code . '</strong> ',
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

        return view('college_instructor.print_my_schedule', compact('event_json','instructor_name'));
    }

}
