<?php

namespace App\Http\Controllers\AdmissionBED;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use Session;

class InterviewSchedules extends Controller
{
    //

    public function __construct() {
        $this->middleware('auth');
    }

    function view() {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $schedules = \App\InterviewSchedule::where('id', '!=', NULL)->orderBy('datetime', 'asc')->get();
            return view("admission-bed.interview_schedules", compact('schedules'));
        }
    }

    function add(Request $request) {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $new_schedule = new \App\InterviewSchedule;
            $new_schedule->datetime = $request->datetime;
            $new_schedule->is_remove = 0;
            $new_schedule->room = "";
            $new_schedule->save();
            
            return redirect('/admissionbed/interview_schedules');
        }
    }
    
    function edit($id){
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $schedule = \App\InterviewSchedule::find($id);
            
            return view('admission-bed.modify_sched_interview', compact('id','schedule'));
        }
    }
    
    function edit_now(Request $request){
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $schedule = \App\InterviewSchedule::find($request->id);
            $schedule->datetime = $request->datetime;
            $schedule->save();
            
            
            Session::flash('message', 'Schedule Updated!');
            
            return redirect('/admissionbed/interview_schedules');
        }
    }
}
