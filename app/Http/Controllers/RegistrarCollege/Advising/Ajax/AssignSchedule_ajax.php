<?php

namespace App\Http\Controllers\RegistrarCollege\Advising\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AssignSchedule_ajax extends Controller
{
    //
    function get_section() {
        if (Request::ajax()) {
            $count=0;
            $schedule_id = Input::get("schedule_id");
            $course_id = Input::get("course_id");
            $idno = Input::get("idno");
            
            $school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first();
            
            $selected_schedules = \App\ScheduleCollege::where('schedule_id', $schedule_id)->get();
                       
            foreach($selected_schedules as $selected){
            $is_conflict = \App\ScheduleCollege::
                    join('course_offerings', 'schedule_colleges.schedule_id', '=', 'course_offerings.schedule_id')
                    ->join('grade_colleges', 'grade_colleges.course_offering_id', '=', 'course_offerings.id')
                    ->where('grade_colleges.idno', $idno)
                    ->where('grade_colleges.school_year', $school_year->school_year)
                    ->where('grade_colleges.period', $school_year->period)
                    ->where('schedule_colleges.schedule_id', '!=',$schedule_id)
//                    ->where('schedule_college.schedule_id', $info_course_offering->schedule_id)
                    ->where('schedule_colleges.day', $selected->day)
                    ->where(function($q) use ($selected) {
                        $q->whereBetween('time_start', array(date("H:i:s", strtotime($selected->time_start)), date("H:i:s", strtotime($selected->time_end))))
                        ->orwhereBetween('time_end', array(date("H:i:s", strtotime($selected->time_start)), date("H:i:s", strtotime($selected->time_end))));
                    })
                    ->get();
            $count = $count + count($is_conflict);
            }
            if($count == 0){
            $sections = \App\CourseOffering::where('schedule_id', $schedule_id)->get(['section', 'section_name', 'id']);
            $value=0;
            return view('reg_college.advising.ajax.show_sections', compact('sections', 'schedule_id', 'course_id', 'idno', 'value','count'));
            }else{
            $sections = \App\CourseOffering::where('schedule_id', $schedule_id)->get(['section', 'section_name', 'id']);
            $value=1;
            return view('reg_college.advising.ajax.show_sections', compact('sections', 'schedule_id', 'course_id', 'idno','value','count'));
            }

        }
    }
}
