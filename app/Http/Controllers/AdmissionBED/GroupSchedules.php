<?php

namespace App\Http\Controllers\AdmissionBED;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use Session;

class GroupSchedules extends Controller
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
            $schedules = \App\GroupSchedule::where('id', '!=', NULL)->orderBy('datetime', 'asc')->where('academic_type', $auth_type)->where('is_remove', 0)->get();
            return view("admission-bed.group_schedules", compact('schedules'));
        }
    }

    function add(Request $request) {
        if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            if(Auth::user()->accesslevel== env("ADMISSION_BED")){
                $auth_type =  "BED";
            }else{
                $auth_type = "SHS";
            }
            $new_schedule = new \App\GroupSchedule;
            $new_schedule->datetime = $request->datetime;
            $new_schedule->is_remove = 0;
            $new_schedule->room = "";
            $new_schedule->academic_type = $auth_type;
            $new_schedule->save();
            
            \App\Http\Controllers\Admin\Logs::log("Add group schedule: $request->datetime.");
            return redirect('/admissionbed/group_schedules');
        }
    }
    
    function edit($id){
        if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            $schedule = \App\GroupSchedule::find($id);
            
            return view('admission-bed.modify_sched_group', compact('id','schedule'));
        }
    }
    
    function edit_now(Request $request){
        if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            $schedule = \App\GroupSchedule::find($request->id);
            $schedule->datetime = $request->datetime;
            $schedule->save();
            
            \App\Http\Controllers\Admin\Logs::log("Edit group schedule of $request->id.");
            
            Session::flash('message', 'Schedule Updated!');
            
            return redirect('/admissionbed/group_schedules');
        }
    }
    
    function view_list($id){
        if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            $lists = \App\GroupStudent::where('schedule_id',$id)->get();
            
            return view('admission-bed.view_sched_list_group', compact('id','lists'));
        }
    }
    
    function remove_list($id,$idno){
        if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
            $lists = \App\GroupStudent::where('idno',$idno)->first();
            $lists->schedule_id = "";
            $lists->update();
            
            \App\Http\Controllers\Admin\Logs::log("Remove applicant number $idno in group schedule id $id.");
            
            Session::flash('message', 'Applicant Remove!');
            return redirect('/admissionbed/view_group_list/'.$id);
        }
    }
}
