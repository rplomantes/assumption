<?php

namespace App\Http\Controllers\CollegeInstructor\Grades;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use PDF;

class GradesController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($schedule_id) {
        if (Auth::user()->accesslevel == env('INSTRUCTOR')) {
            
            $confirm_instructor = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;
            
            if ($confirm_instructor == Auth::user()->idno){
            
            $courses_id = \App\CourseOffering::where('schedule_id',$schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id',$schedule_id)->first()->course_name; 
            return view('college_instructor.view_students', compact('courses_id','schedule_id','course_name'));
            } else {
                
            }
        }
    }
    
    function save_submit(Request $request) {
        if (Auth::user()->accesslevel == env('INSTRUCTOR')) {
            $course_offerings = \App\CourseOffering::where('schedule_id', $request->schedule_id)->get();
            
            foreach ($course_offerings as $course_offering){
                DB::beginTransaction($course_offering, $request);
                    $this->updateStatus($course_offering, $request);
                DB::commit();
            }
            return redirect(url('college_instructor', array('grades',$request->schedule_id)));
        }
    }
    
    function updateStatus($course_offering,$request){
        $updateStatus = \App\GradeCollege::where('course_offering_id', $course_offering->id)->get();
        foreach ($updateStatus as $update){
        if ($request->midterm_status == 0){
            $update->midterm_status = 1;
            $update->save();
        }
        if ($request->finals_status == 0){
            $update->finals_status = 1;
            $update->save();
        }
        if ($request->grade_point_status == 0){
            $update->grade_point_status = 1;
            $update->save();
        }
        }
    }
    
    function print_list($schedule_id) {
        if (Auth::user()->accesslevel == env('INSTRUCTOR')) {
            
            $confirm_instructor = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;
            
            if ($confirm_instructor == Auth::user()->idno){
            
            $courses_id = \App\CourseOffering::where('schedule_id',$schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id',$schedule_id)->first()->course_name; 
            
            $pdf = PDF::loadView('college_instructor.print_class_list', compact('courses_id','schedule_id','course_name'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("student_list_.pdf");
            
            } else {
                
            }
            
        }
    }
    
    function print_grade($schedule_id) {
        if (Auth::user()->accesslevel == env('INSTRUCTOR')) {
            
            $confirm_instructor = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;
            
            if ($confirm_instructor == Auth::user()->idno){
            
            $courses_id = \App\CourseOffering::where('schedule_id',$schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id',$schedule_id)->first()->course_name; 
            
            $pdf = PDF::loadView('college_instructor.print_grade', compact('courses_id','schedule_id','course_name'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("student_list_.pdf");
            
            } else {
                
            }
            
        }
    }
}
