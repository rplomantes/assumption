<?php

namespace App\Http\Controllers\RegistrarCollege\Assessment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AssessmentController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            
            $status = \App\Status::where('idno', $idno)->first();
            if ($status->status == 0) {
                //return view('dean.assessment.assess', compact('status', 'idno'));
            } else if ($status->status == 1) {
                return view('reg_college.assessment.view_assessment', compact('idno'));
            } else if ($status->status == 2) {
                return view('reg_college.assessment.assessed', compact('status', 'idno'));
            } else if ($status->status >= 3){
                return view('reg_college.assessment.enrolled', compact('status', 'idno'));
            } else {
                return view('reg_college.assessment.enrolled', compact('status', 'idno'));
            }
            
        }
    }
    
    function save_assessment($idno){
//        $updatestatus = \App\Status::where('idno', $idno)->first();
//        $updatestatus->status = 2;
//        $updatestatus->save();
        
        return redirect ("/registrar_college/assessment/$idno");
        
    }
    
    function reassess($idno){
        $updatestatus = \App\Status::where('idno', $idno)->first();
        $updatestatus->status = 1;
        $updatestatus->save();
        
        return redirect ("/registrar_college/assessment/$idno");
        
    }
}
