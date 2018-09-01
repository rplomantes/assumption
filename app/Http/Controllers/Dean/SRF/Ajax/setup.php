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
            
            $years = \App\Curriculum::distinct()->where('program_code', $program_code)->where('level', $level)->where('period', $period)->orderBy('curriculum_year', 'asc')->get(['curriculum_year']);
            
            $data = "<b>Note:</b> All modified subjects with the same subject code will also be modified."
                    . "<table class='table table-striped'><thead><tr><th>Code</th><th>Description</th><th>SRF</th><th>Lab Fee</th><th>Modify</th></tr></thead>"
                    . "<tbody>";
            foreach($years as $year){
                $data = $data."<tr><td align='center' colspan='5'><strong>".$year->curriculum_year."</strong></td>";
                
            $lists = \App\Curriculum::distinct()->where('program_code', $program_code)->where('level', $level)->where('period', $period)->where('curriculum_year', $year->curriculum_year)->get(['course_code','course_name', 'srf','lab_fee']);
                foreach($lists as $list){
                    $data = $data."<tr>"
                            . "<td>".$list->course_code."</td>"
                            . "<td>".$list->course_name."</td>"
                            . "<td>".$list->srf."</td>"
                            . "<td>".$list->lab_fee."</td>"
                            . "<td><a target='_blank' href='".url('dean', array('srf','modify',$list->course_code))."'>Modify</a></td>"
                            . "</tr>";
                }
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
            
            $lists = \App\Curriculum::distinct()->where('curriculum_year', $curriculum_year)->where('program_code', $program_code)->where('level', $level)->where('period', $period)->get(['course_code','course_name', 'srf','lab_fee']);
            
            $data = "<table class='table table-striped'><thead><tr><th>Code</th><th>Description</th><th>SRF</th><th>Lab Fee</th></tr></thead>"
                    . "<tbody>";
            foreach($lists as $list){
                $data = $data."<tr>"
                        . "<td>".$list->course_code."</td>"
                        . "<td>".$list->course_name."</td>"
                        . "<td>".$list->srf."</td>"
                        . "<td>".$list->lab_fee."</td>"
                        . "</tr>";
            }
            $data = $data."</tbody></table>";
            
            return ($data);
        }
    }
    function get_student_list(){
        if(Request::ajax()){
            $course_code = Input::get('course_code');
            $school_year = Input::get('school_year');
            $period = Input::get('period');
            $number=1;
            
            $lists = \App\GradeCollege::where('grade_colleges.school_year', $school_year)->where('grade_colleges.period', $period)->where('grade_colleges.course_code', $course_code)->join('users', 'users.idno','=','grade_colleges.idno')->join('statuses', 'statuses.idno','=','grade_colleges.idno')->where('statuses.status', 3)->orderBy('users.lastname', 'asc')->get();
            
            $data = "<table class='table table-condensed'><thead><tr><th>#</th><th>ID Number</th><th>Name</th><th>SRF</th><th>Lab Fee</th><th>Balance</th></tr></thead>"
                    . "<tbody>";
            foreach($lists as $list){
                $totalbalance=0;
                $balance = \App\Ledger::where('idno',$list->idno)->where('subsidiary', $course_code)->where('school_year', $school_year)->where('period', $period)->first();
                if(count($balance)>0){
                    $deduct=$balance->payment+$balance->debit_memo+$balance->discount;
                    $totalbalance = $balance->amount-$deduct;
                }
                $data = $data."<tr>"
                        . "<td>".$number++."</td>"
                        . "<td>".$list->idno."</td>"
                        . "<td>".$list->lastname.', ' .$list->firstname." ".$list->middlename."</td>"
                        . "<td>".$list->srf."</td>"
                        . "<td>".$list->lab_fee."</td>"
                        . "<td>".$totalbalance."</td>"
                        . "</tr>";
            }
            $data = $data."</tbody></table>";
            
            return ($data);
        }
    }
    function get_courses(){if(Request::ajax()){
            $school_year = Input::get('school_year');
            $period = Input::get('period');
            $students = \App\GradeCollege::distinct()->where('school_year', $school_year)->where('period', $period)->get(['course_code', 'course_name']);
            return view('dean.srf.ajax_get_courses', compact('students'));
        }
    }
}
