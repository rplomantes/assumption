<?php

namespace App\Http\Controllers\RegistrarCollege\Advising\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AjaxAdvisingStatistics extends Controller
{
    //
    function get_advising_statistics() {
        if (Request::ajax()) {
            $course_code = Input::get("course_code");
            $school_year = Input::get("school_year");
            $period = Input::get("period");

            return view('reg_college.advising.ajax.get_advising_statistics', compact('course_code', 'school_year', 'period'));
        }
    }
    function addtosection(){
        if (Request::ajax()) {
            $course_code = Input::get("course_code");
            $idno = Input::get("idno");
            $schedule_id = Input::get("schedule_id");
            $section = Input::get("section");
            
            $course_offering_id = \App\CourseOffering::where('schedule_id', $schedule_id)->where('course_code', $course_code)->where('section', $section)->first();
            
            $update_grade_college = \App\GradeCollege::where('idno',$idno)->where('course_code', $course_code)->first();
            $update_grade_college->course_offering_id = $course_offering_id->id;
            $update_grade_college->is_advising = 0;
            $update_grade_college->save();
            
            return $schedule_id;
        }
    }
    function getsection(){
        if (Request::ajax()) {
            $schedule_id = Input::get("schedule_id");
            
            $sections = \App\CourseOffering::where('schedule_id', $schedule_id)->get(['section', 'section_name']);
            
            $data = "<option value=''>Select Section</option>";
            foreach ($sections as $section) {
                $data = $data."<option value=".$section->section.">".$section->section_name."</section>";
            }
            return $data;
        }
    }
    function getschedulestudentlist(){
        if (Request::ajax()) {
            $schedule_id = Input::get("schedule_id");
            $section = Input::get("section");
            $course_code = Input::get("course_code");
            
            $course_offering_id= \App\CourseOffering::where('schedule_id', $schedule_id)->where('course_code', $course_code)->first();
            
            $lists = \App\GradeCollege::where('grade_colleges.course_offering_id', $course_offering_id->id)->where('grade_colleges.is_advising',0)->join('users','grade_colleges.idno','=','users.idno' )->orderBy('users.lastname')->get();
            
            if(count($lists)>0){
            $counter=0;
            $data = "<table class='\table table-condensed'\><tr><th>No.</th><th>ID Number</th><th>Name</th></tr>";
            foreach ($lists as $list){
                $counter= $counter+1;
                $user= \App\User::where('idno', $list->idno)->first();
                $data = $data."<tr><td>".$counter."</td><td>".$list->idno."</td><td>".$user->lastname.", ".$user->firstname."</td></tr>";
            }
            $data = $data."</table>";
            } else {
                $data = "No Student Yet.";
            }
            
            return $data;
        }
    }
    
}
