<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class GradeDisplayController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }
    
    function view(){
        $view_grades = \App\CtrBedDisplayGrade::all();
        return view('reg_be.grade_display.portal',compact('view_grades'));
    }
    
    function updateStatus($level){
        $update = \App\CtrBedDisplayGrade::where('level',$level)->first();
        
        if($update->is_display == 0){
            $update->is_display = 1;
            $update->save();
            \App\Http\Controllers\Admin\Logs::log("Enable viewing of View Grades in $level");
        }elseif($update->is_display == 1){
            $update->is_display = 0;
            $update->save();
            \App\Http\Controllers\Admin\Logs::log("Disable viewing of View Grades in $level");
        }
        
        $view_grades = \App\CtrBedDisplayGrade::all();
        return view('reg_be.grade_display.portal',compact('view_grades'));
    }
}