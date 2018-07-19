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
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            return view('reg_college.curriculum_management.view_room_schedules');
        }
    }

    function print_room_schedule($school_year, $period, $room) {
        $rooms = \App\ScheduleCollege::where('schedule_colleges.school_year', $school_year)->where('schedule_colleges.period', $period)->where('schedule_colleges.room', $room)->join('course_offerings', 'course_offerings.schedule_id', '=', 'schedule_colleges.schedule_id')->get();

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
                case "Sa": $room->day = "saturday";
                    break;
                case "Su": $room->day = "sunday";
                    break;
            }

            $date = date('Y-m-d', strtotime($room->day . ' this week'));
            $events[$key] = \Calendar::event(
                            $room->course_code . $room->section_name, false, $date . 'T' . $room->time_start, $date . 'T' . $room->time_end, 0
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
                            'maxTime' => '19:00:00'
                        ])->setCallbacks([
        ]);

        $style = "<script type=\"text/javascript\" src=\"//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js\"></script>
<script type=\"text/javascript\" src=\"//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js\"></script>
<link rel=\"stylesheet\" href=\"//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css\"/>


{!! $calendar->calendar() !!}
{!! $calendar->script() !!}

<script type=\"text/javascript\">
</script>";
        
        $projectDirectory = base_path();

        //wkhtmltopdf binary as composer dependencies if it is 64 bit based system
        $snappy = new Pdfs($projectDirectory . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
        $snappy->setOption('enable-javascript', true);

        header('Content-Type: application/pdf');
        echo $snappy->getOutputFromHtml($style);
        

//        $pdf = PDF::loadView('reg_college.curriculum_management.print_room_schedules', array('calendar' => $calendar));
//        $pdf->setPaper('letter', 'landscape');
//        return $pdf->stream("ched_enrollment_report.pdf");
    }

}
