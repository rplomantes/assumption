<?php

namespace App\Http\Controllers\RegistrarCollege\GradeManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class OpenCloseController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function setup() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $status = \App\CtrCollegeGrading::where('academic_type', 'College')->where('idno', 999991)->first();
            
            return view('reg_college.grade_management.open_close', compact('status'));
        }
    }
    
    function submit(Request $request){
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $update = \App\CtrCollegeGrading::where('academic_type', 'College')->get();
            foreach($update as $u){
            $u->midterm = $request->midterm;
            $u->finals = $request->finals;
            $u->save();
            }
            
            \App\Http\Controllers\Admin\Logs::log("Setup grading for HED midterms: $request->midterm and finals:$request->finals");
            
            return redirect('/registrar_college/grade_management/open_close');
        }
    }
}
