<?php

namespace App\Http\Controllers\Dean\SRF\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Request;

class setup extends Controller
{
    //
    function get_list(){
        if(Request::ajax()){
            $program_code = Input::get('program_code');
            $level = Input::get('level');
            $period = Input::get('period');
            
            $lists = \App\Curriculum::distinct()->where('program_code', $program_code)->where('level', $level)->where('period', $period)->get(['course_code','course_name', 'srf']);
            
            $data = "<b>Note:</b> All modified subjects with the same subject code will also be modified."
                    . "<table class='table table-striped'><thead><tr><th>Code</th><th>Description</th><th>SRF</th><th>Modify</th></tr></thead>"
                    . "<tbody>";
            foreach($lists as $list){
                $data = $data."<tr>"
                        . "<td>".$list->course_code."</td>"
                        . "<td>".$list->course_name."</td>"
                        . "<td>".$list->srf."</td>"
                        . "<td><a target='_blank' href='".url('dean', array('srf','modify',$list->course_code))."'>Modify</a></td>"
                        . "</tr>";
            }
            $data = $data."</tbody></table>";
            
            return ($data);
        }
    }
    
    function print_list(){
        if(Request::ajax()){
            $program_code = Input::get('program_code');
            $level = Input::get('level');
            $period = Input::get('period');
            $curriculum_year = Input::get('curriculum_year');
            
            $lists = \App\Curriculum::distinct()->where('curriculum_year', $curriculum_year)->where('program_code', $program_code)->where('level', $level)->where('period', $period)->get(['course_code','course_name', 'srf']);
            
            $data = "<table class='table table-striped'><thead><tr><th>Code</th><th>Description</th><th>SRF</th></tr></thead>"
                    . "<tbody>";
            foreach($lists as $list){
                $data = $data."<tr>"
                        . "<td>".$list->course_code."</td>"
                        . "<td>".$list->course_name."</td>"
                        . "<td>".$list->srf."</td>"
                        . "</tr>";
            }
            $data = $data."</tbody></table>";
            
            return ($data);
        }
    }
}
