<?php

namespace App\Http\Controllers\AdmissionHED;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Session;

class TestingSchedules extends Controller
{
    //

    public function __construct() {
        $this->middleware('auth');
    }

    function view() {
        if (Auth::user()->accesslevel == env("ADMISSION_HED")) {
            $schedules = \App\HedTestingSchedule::where('id', '!=', NULL)->orderBy('datetime', 'asc')->where('is_remove', 0)->get();
            return view("admission-hed.testing_schedules", compact('schedules'));
        }
    }

    function add(Request $request) {
        if (Auth::user()->accesslevel == env("ADMISSION_HED")) {
            $new_schedule = new \App\HedTestingSchedule;
            $new_schedule->datetime = $request->datetime;
            $new_schedule->is_remove = 0;
            $new_schedule->room = "";
            $new_schedule->save();
            
            \App\Http\Controllers\Admin\Logs::log("Add HED testing schedule: $request->datetime.");
            return redirect('/admissionhed/testing_schedules');
        }
    }
    
    function edit($id){
        if (Auth::user()->accesslevel == env("ADMISSION_HED")) {
            $schedule = \App\HedTestingSchedule::find($id);
            
            return view('admission-hed.modify_sched', compact('id','schedule'));
        }
    }
    
    function edit_now(Request $request){
        if (Auth::user()->accesslevel == env("ADMISSION_HED")) {
            $schedule = \App\HedTestingSchedule::find($request->id);
            $schedule->datetime = $request->datetime;
            $schedule->save();
            
            \App\Http\Controllers\Admin\Logs::log("Edit HED testing schedule of $request->id.");
            
            
            Session::flash('message', 'Schedule Updated!');
            
            return redirect('/admissionhed/testing_schedules');
        }
    }
    
    function view_list($id){
        if (Auth::user()->accesslevel == env("ADMISSION_HED")) {
            $lists = \App\HedTestingStudent::where('schedule_id',$id)->get();
            
            return view('admission-hed.view_sched_list', compact('id','lists'));
        }
    }
    
    function remove_list($id,$idno){
        if (Auth::user()->accesslevel == env("ADMISSION_HED")) {
            $lists = \App\HedTestingStudent::where('idno',$idno)->first();
            $lists->schedule_id = "";
            $lists->update();
            
            \App\Http\Controllers\Admin\Logs::log("Remove applicant number $idno in HED testing schedule id $id.");
            
            Session::flash('message', 'Applicant Remove!');
            return redirect('/admissionhed/view_testing_list/'.$id);
        }
    }
}
