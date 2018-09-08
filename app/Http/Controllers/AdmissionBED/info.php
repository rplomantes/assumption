<?php

namespace App\Http\Controllers\AdmissionBED;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use Session;

class info extends Controller
{
    //

    public function __construct() {
        $this->middleware('auth');
    }

    function info($idno) {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("ADMISSION_BED")) {


            $user = \App\User::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            $info = \App\BedProfile::where('bed_profiles.idno', $idno)->join('bed_parent_infos', 'bed_parent_infos.idno','=','bed_profiles.idno')->first();
            return view("admission-bed.info", compact('user', 'info','status'));
        }
    }
    
    function approve_application($idno){
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $status = \App\Status::where('idno', $idno)->first();
            $status->status=0;
            $status->save();
            return redirect('admissionbed/info/'.$idno);
        }
    }
}
