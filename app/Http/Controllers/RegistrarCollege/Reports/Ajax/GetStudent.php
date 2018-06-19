<?php

namespace App\Http\Controllers\RegistrarCollege\Reports\Ajax;

use App\Http\Controllers\Controller;
use DB;
use Request;
use Illuminate\Support\Facades\Input;

class GetStudent extends Controller
{
    function getstudent(){
        if(Request::ajax()){
            $programs = Input::get('programs');           
                 $college_levels = \App\CollegeLevel::where('program_name',$programs)->orderBy('idno')->get();
             return view ("reg_college.reports.ajax.getstudent", compact('programs','college_levels'));
        }
    }
}
