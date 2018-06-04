<?php

namespace App\Http\Controllers\RegistrarCollege\Admission\Ajax;

use App\Http\Controllers\Controller;
use DB;
use Request;
use Illuminate\Support\Facades\Input;

class getcourseController extends Controller
{
        function getCourse(){
        if(Request::ajax()){
            $applying_for = Input::get('applying_for');
            if($applying_for == "Senior High School"){
                $ctr_academic_program = \App\CtrAcademicProgram::SelectRaw("distinct strand")->where('academic_code','SHS')->get();
            }elseif($applying_for == "College"){
                $ctr_academic_program = \App\CtrAcademicProgram::SelectRaw("distinct program_name")->where('academic_type','College')->get();
                
            }elseif($applying_for == "Graduate School"){
                $ctr_academic_program = \App\CtrAcademicProgram::SelectRaw("distict program_name")->where('academic_type','Masters Degree')->get();
            }
                return view("reg_college.admission.getProgram", compact("applying_for", "ctr_academic_program"));   
        }

    }
}

