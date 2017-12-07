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
        if (Auth::user()->accesslevel == '20') {
            
            $status = \App\Status::where('idno', $idno)->first();
            if ($status->status == 0) {
                //return view('dean.assessment.assess', compact('status', 'idno'));
            } else if ($status->status == 1) {
                return view('reg_college.assessment.view_assessment', compact('idno'));
            } else if ($status >= 2) {
//                return view('dean.assessment.enrolled', compact('status', 'idno'));
            } else {
//                return view('dean.assessment.assess', compact('status', 'idno'));
            }
            
        }
    }
}
