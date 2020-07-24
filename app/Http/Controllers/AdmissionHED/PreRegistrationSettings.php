<?php

namespace App\Http\Controllers\AdmissionHED;

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
        if (Auth::user()->accesslevel == env("ADMISSION_HED")) {
            
            $programs = \App\PreRegistrationSettingCollege::orderBy('program_name','asc')->get();
            
            return view('admission-hed.settings.programs', compact('programs'));
        }
    }

    function update($program_code) {
        if (Auth::user()->accesslevel == env("ADMISSION_HED")) {
            
            $get_levels = \App\PreRegistrationSettingCollege::where('program_code',$program_code)->first();
            if($get_levels->is_on == 1){
                $get_levels->is_on = 0;
            }else{
                $get_levels->is_on = 1;
            }
            $get_levels->update();
            
            \App\Http\Controllers\Admin\Logs::log("Change pre-application settings of Program $program_code to $get_levels->is_on.");
            
            $progams = \App\PreRegistrationSettingCollege::all();
            
            return redirect('/admissions/settings/programs');
        }
    }
}
