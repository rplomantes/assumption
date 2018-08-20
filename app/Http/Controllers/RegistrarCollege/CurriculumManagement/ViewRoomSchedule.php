<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Knp\Snappy\Pdf as Pdfs;

class ViewRoomSchedule extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN')) {
            return view('reg_college.curriculum_management.view_room_schedules');
        }
    }

    function print_room_schedule($school_year, $period, $room) {
        $selected_room = $room;
        $rooms = \App\ScheduleCollege::distinct()->where('schedule_colleges.school_year', $school_year)->where('schedule_colleges.period', $period)->where('schedule_colleges.room', $selected_room)->join('course_offerings', 'course_offerings.schedule_id', '=', 'schedule_colleges.schedule_id')->get(array('schedule_colleges.course_code', 'schedule_colleges.schedule_id', 'room', 'day','time_start','time_end','instructor_id'));

            foreach ($rooms as $key=>$room){
                switch ($room->day) {
                    case "M": $room->day = "monday"; break;
                    case "T": $room->day = "tuesday"; break;
                    case "W": $room->day = "wednesday"; break;
                    case "Th": $room->day = "thursday"; break;
                    case "F": $room->day = "friday"; break;
                    case "Sa": $room->day = "saturday"; break;
                    case "Su": $room->day = "sunday"; break;
                }
                $color_now = "#".substr($room->schedule_id, -6);
                if($room->instructor_id != NULL){
                $instructor = \App\User::where('idno', $room->instructor_id)->first();
                    $instructor_name = $instructor->lastname. ', '. $instructor->firstname;
                } else {
                    $instructor_name = "";
                }
                
                $date = date( 'Y-m-d', strtotime( $room->day.' this week' ) );
                $events[$key] = \Calendar::event(
                        $room->course_code. ' '.$instructor_name,
                        false,
                        $date.'T'.$room->time_start,
                        $date.'T'.$room->time_end,
                        $room->schedule_id,
                            [
                                'color' => "$color_now",
                                'textColor' => "black",
                            ]
                );
            }

            $calendar = \Calendar::addEvents($events)
                            ->setOptions([
                                'firstDay' => 0,
                                'header' => false,
                                'columnFormat' => 'dddd',
                                'allDaySlot' => false,
                                'defaultView' => 'agendaWeek',
                                'minTime' => '07:00:00',
                                'maxTime' => '20:00:00',
                                'height' => 650,
                            ])->setCallbacks([
            ]);
        
            return view('reg_college.curriculum_management.ajax.generateRoom2', array('calendar' => $calendar), compact('selected_room'));
    }

}
