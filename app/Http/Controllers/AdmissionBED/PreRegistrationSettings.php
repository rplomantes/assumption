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
}
