<?php

namespace App\Http\Controllers\AdmissionBED;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use Session;

class PreRegistrationSettings extends Controller
{
    //

    public function __construct() {
        $this->middleware('auth');
    }

    function view() {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            
            $levels = \App\PreRegistrationSetting::all();
            
            return view('admission-bed.settings.levels', compact('levels'));
        }
    }

    function update($level) {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            
            $get_levels = \App\PreRegistrationSetting::where('level',$level)->first();
            if($get_levels->is_on == 1){
                $get_levels->is_on = 0;
            }else{
                $get_levels->is_on = 1;
            }
            $get_levels->update();
            
            \App\Http\Controllers\Admin\Logs::log("Change pre-registration settings of level $level to $get_levels->is_on.");
            
            $levels = \App\PreRegistrationSetting::all();
            
            return redirect('/bedadmission/settings/levels');
        }
    }

    function view_waive_payments() {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            
            $levels = \App\WavePayments::where('academic_type', "!=", "College")->get();
            
            return view('admission-bed.settings.waive_payments', compact('levels'));
        }
    }

    function update_waive_payments($academic_type) {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            
            $get_levels = \App\WavePayments::where('academic_type',$academic_type)->first();
            if($get_levels->is_wave == 1){
                $get_levels->is_wave = 0;
            }else{
                $get_levels->is_wave = 1;
            }
            $get_levels->update();
            
            \App\Http\Controllers\Admin\Logs::log("Change wave payment settings of department $academic_type to $get_levels->is_wave.");
            
            return redirect('/bedadmission/settings/waive_payments');
        }
    }
    
    function view_pre_registration_email() {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            return view('admission-bed.pre_registration_email');
        }
    }
    
    function view_pre_registration_email_post(Request $request) {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            if($request->submit == "regular"){
                $update = \App\CtrPreRegMessages::where('type',$request->submit)->first();
                $update->message=$request->message_regular;
                $update->save();
            }elseif($request->submit == "waive"){
                $update = \App\CtrPreRegMessages::where('type',$request->submit)->first();
                $update->message=$request->message_waive;
                $update->save();
            }
            return redirect(url('/bedadmission/settings/pre_registration_email'));
        }
    }
    
    function view_application_result_email() {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            return view('admission-bed.application_result_email');
        }
    }
    
    function view_application_result_email_post(Request $request) {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            if($request->submit == "Approved"){
                $update = \App\CtrPreRegMessages::where('type',$request->submit)->first();
                $update->message=$request->message_approved;
                $update->save();
            }elseif($request->submit == "Regret"){
                $update = \App\CtrPreRegMessages::where('type',$request->submit)->first();
                $update->message=$request->message_regret;
                $update->save();
            }
            return redirect(url('/bedadmission/settings/application_result_email'));
        }
    }
}
