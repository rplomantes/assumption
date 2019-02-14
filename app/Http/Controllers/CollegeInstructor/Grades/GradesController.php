<?php

namespace App\Http\Controllers\CollegeInstructor\Grades;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use PDF;
use Excel;

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
            if($request->submit == "Save & Submit MIDTERM grades for Checking of Dean"){
                $value=1;
                $type="midterm";
            }else if($request->submit == "Forward to Records and Finalize MIDTERM grades"){
                $value=3;
                $type="midterm";
            }
            if($request->submit == "Save & Submit FINALS grades for Checking of Dean"){
                $value=1;
                $type="finals";
            }else if($request->submit == "Forward to Records and Finalize FINALS grades"){
                $value=3;
                $type="finals";
            }
            $course_offerings = \App\CourseOffering::where('schedule_id', $request->schedule_id)->get();

            foreach ($course_offerings as $course_offering){
                DB::beginTransaction($course_offering, $request, $value);
                    $this->updateStatus($course_offering, $request, $value, $type);
                    if($value != 3){
                    \App\Http\Controllers\Admin\Logs::log("$request->submit for course_offering_id: $course_offering->id.");
                    }else{
                    \App\Http\Controllers\Admin\Logs::log("Submit to Records to Finalize $type grades for course_offering_id: $course_offering->id.");
                    }
                DB::commit();
            }
            
            return redirect(url('college_instructor', array('grades',$request->schedule_id)));
        }
    }
    
    function updateStatus($course_offering,$request, $status, $type){
        $updateStatus = \App\GradeCollege::where('course_offering_id', $course_offering->id)->get();
        foreach ($updateStatus as $update){
        
            if($type == 'midterm'){
                $update->midterm_status = $status;
            }
            if ($type == 'finals'){
                $update->finals_status = $status;
            }
        $update->save();
        }
    }
    
    function print_list($schedule_id) {
        if (Auth::user()->accesslevel == env('INSTRUCTOR')) {
            
            $confirm_instructor = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;
            
            if ($confirm_instructor == Auth::user()->idno){
            
            $courses_id = \App\CourseOffering::where('schedule_id',$schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id',$schedule_id)->first()->course_name; 
            $course_code = \App\CourseOffering::where('schedule_id',$schedule_id)->first()->course_code; 
            
             \App\Http\Controllers\Admin\Logs::log("Print Student List of schedule id: $schedule_id");
            $pdf = PDF::loadView('college_instructor.print_class_list', compact('courses_id','schedule_id','course_name','course_code'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("student_list_.pdf");
            
            } else {
                
            }
            
        }
    }
    
    function print_grade($school_year,$period,$schedule_id) {
        if (Auth::user()->accesslevel == env('INSTRUCTOR') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE')) {
            
            $confirm_instructor = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;
            $instructor = \App\User::where('idno', $confirm_instructor)->first();
            
            $courses_id = \App\CourseOffering::where('schedule_id',$schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id',$schedule_id)->first()->course_name; 
            
             \App\Http\Controllers\Admin\Logs::log("Print Grade of schedule id $schedule_id PDF");
            $pdf = PDF::loadView('college_instructor.print_grade', compact('courses_id','schedule_id','course_name','instructor','school_year','period'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("student_list_.pdf");
            
            
        }
    }
    
    
    function export_list($schedule_id) {
        if (Auth::user()->accesslevel == env('INSTRUCTOR')) {
            
            $confirm_instructor = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;
            
            if ($confirm_instructor == Auth::user()->idno){
            
            $courses_id = \App\CourseOffering::where('schedule_id',$schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id',$schedule_id)->first()->course_name; 
            $course_code = \App\CourseOffering::where('schedule_id',$schedule_id)->first()->course_code; 
            
             \App\Http\Controllers\Admin\Logs::log("Download Grade of schedule id $schedule_id Excel");
            
            ob_end_clean();
            Excel::create('Student List-'.$course_code, function($excel) use ($courses_id,$schedule_id,$course_name,$course_code) {
                $excel->setTitle($course_code);

                $excel->sheet($course_code, function ($sheet) use ($courses_id,$schedule_id,$course_name,$course_code) {
                    $sheet->loadView('college_instructor.export_list', compact('courses_id','schedule_id','course_name','course_code'));
                });
            })->download('xlsx');
            
            } else {
                
            }
            
        }
    }
}
