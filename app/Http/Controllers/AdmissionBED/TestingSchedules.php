<?php

namespace App\Http\Controllers\AdmissionBED;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use Session;

class TestingSchedules extends Controller
{
    //

    public function __construct() {
        $this->middleware('auth');
    }

    function view() {
        if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            if(Auth::user()->accesslevel== env("ADMISSION_BED")){
                $auth_type =  "BED";
            }else{
                $auth_type = "SHS";
            }
            $schedules = \App\TestingSchedule::where('id', '!=', NULL)->orderBy('datetime', 'asc')->where('academic_type', $auth_type)->where('is_remove', 0)->get();
            return view("admission-bed.testing_schedules", compact('schedules'));
        }
    }

    function add(Request $request) {
        if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            if(Auth::user()->accesslevel== env("ADMISSION_BED")){
                $auth_type =  "BED";
            }else{
                $auth_type = "SHS";
            }
            $new_schedule = new \App\TestingSchedule;
            $new_schedule->datetime = $request->datetime;
            $new_schedule->is_remove = 0;
            $new_schedule->room = "";
            $new_schedule->academic_type = $auth_type;
            $new_schedule->save();
            
            \App\Http\Controllers\Admin\Logs::log("Add testing schedule: $request->datetime.");
            
            return redirect('/admissionbed/testing_schedules');
        }
    }
    
    function edit($id){
        if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            $schedule = \App\TestingSchedule::find($id);
            
            return view('admission-bed.modify_sched', compact('id','schedule'));
        }
    }
    
    function edit_now(Request $request){
        if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            $schedule = \App\TestingSchedule::find($request->id);
            $schedule->datetime = $request->datetime;
            $schedule->save();
            
            \App\Http\Controllers\Admin\Logs::log("Edit testing schedule of $request->id.");
            
            
            Session::flash('message', 'Schedule Updated!');
            
            return redirect('/admissionbed/testing_schedules');
        }
    }
    
    function view_list($id){
        if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            $lists = \App\TestingStudent::where('schedule_id',$id)->get();
            
            return view('admission-bed.view_sched_list', compact('id','lists'));
        }
    }
    
    function remove_list($id,$idno){
        if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            $lists = \App\TestingStudent::where('idno',$idno)->first();
            $lists->schedule_id = "";
            $lists->update();
            
            \App\Http\Controllers\Admin\Logs::log("Remove applicant number $idno in testing schedule id $id.");
            
            Session::flash('message', 'Applicant Remove!');
            return redirect('/admissionbed/view_testing_list/'.$id);
        }
    }
}
