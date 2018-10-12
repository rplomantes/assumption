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
            
//            $years = \App\Curriculum::distinct()->where('program_code', $program_code)->where('level', $level)->where('period', $period)->orderBy('curriculum_year', 'asc')->get(['curriculum_year']);
            $years = \App\Curriculum::distinct()->where('level', $level)->where('period', $period)->orderBy('curriculum_year', 'asc')->get(['curriculum_year']);
            
            $data = "<b>Note:</b> All modified SRF in the same period with the same subject code will also be modified."
                    . "<table class='table table-striped'><thead><tr><th>Code</th><th>Description</th><th>SRF</th><th>Lab Fee</th><th>Modify</th></tr></thead>"
                    . "<tbody>";
            foreach($years as $year){
                $data = $data."<tr><td align='center' colspan='5'><strong> Curriculum Year: ".$year->curriculum_year."</strong></td>";
                
//            $lists = \App\Curriculum::distinct()->where('program_code', $program_code)->where('level', $level)->where('period', $period)->where('curriculum_year', $year->curriculum_year)->get(['course_code','course_name', 'srf','lab_fee']);
            $lists = \App\Curriculum::distinct()->where('level', $level)->where('period', $period)->where('curriculum_year', $year->curriculum_year)->orderBy('course_name','asc')->get(['course_code','course_name', 'srf','lab_fee']);
                foreach($lists as $list){
                    $data = $data."<tr>"
                            . "<td>".$list->course_code."</td>"
                            . "<td>".$list->course_name."</td>"
                            . "<td>".$list->srf."</td>"
                            . "<td>".$list->lab_fee."</td>"
                            . "<td><a target='_blank' href='".url('dean', array('srf','modify',$period,$list->course_code))."'>Modify</a></td>"
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
    
    
    function get_srf_balances(){
        if(Request::ajax()){
            $school_year = Input::get('school_year');
            $period = Input::get('period');
            $program_code = Input::get('program');
            $number=1;
            
            
            
            $lists = \App\Ledger::distinct('ledgers.idno', 'users.lastname', 'users.firstname', 'users.middlename')
                    ->selectRaw("sum(payment) as total_payment,sum(discount) as total_discount,sum(debit_memo) as total_dm, sum(amount) as total_amount, ledgers.idno, users.lastname, users.firstname, users.middlename")
                    ->where('ledgers.school_year', $school_year)
                    ->where('ledgers.period', $period)
                    ->where('ledgers.program_code', '!=', null)
                    ->where('ledgers.category','SRF')
                    ->where('statuses.program_code', $program_code)
                    ->where('statuses.status', 3)
                    ->groupBy('ledgers.idno')
                    ->join('users', 'users.idno','=','ledgers.idno')
                    ->join('statuses', 'statuses.idno','=','ledgers.idno')
                    ->orderBy('users.lastname', 'asc')
                    ->get(array('ledgers.idno','users.firstname','users.lastname','users.middlename','total_dm','total_discount','total_payment','total_amount'));
            
            $data = "<table class='table table-condensed'><thead><tr><th>#</th><th>ID Number</th><th>Name</th><th>Program</th><th>Level</th><th>Amount to Collect</th><th>Discount</th><th>Debit Memo</th><th>Payment Rendered</th><th>Balance</th><th>View Subjects</th></tr></thead>"
                    . "<tbody>";
            foreach($lists as $list){
                $deduct = $list->total_payment + $list->total_dm + $list->total_discount;
                $balance = $list->total_amount - $deduct;
                $other_info = \App\Status::where('idno', $list->idno)->first();
                
                $data = $data."<tr>"
                        . "<td>".$number++."</td>"
                        . "<td>".$list->idno."</td>"
                        . "<td>".$list->lastname.", ".$list->firstname." ".$list->middlename."</td>"
                        . "<td>".$other_info->program_code."</td>"
                        . "<td>".$other_info->level."</td>"
                        . "<td style='color: blue;'>".$list->total_amount."</td>"
                        . "<td>".$list->total_discount."</td>"
                        . "<td>".$list->total_dm."</td>"
                        . "<td>".$list->total_payment."</td>"
                        . "<td style='color:red;'><strong>".$balance."</strong></td>"
                        . "<td><a href=\"javascript:void(0)\"  onclick=\"get_subjects('".$list->idno."', '".$school_year."','".$period."','".$program_code."')\" data-toggle=\"modal\" data-target=\"#show_subjects\">View</a></td>"
                        . "</tr>";
            }
            $data = $data."</tbody></table>";
            
            return ($data);
        }
    }
    
    function get_subjects(){
        if(Request::ajax()){
            $idno = Input::get('idno');
            $school_year = Input::get('school_year');
            $period = Input::get('period');
            $program_code = Input::get('program');
            
            $srfs = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period',$period)->where('category', 'SRF')->get();
            return view('dean.srf.ajax_get_subjects', compact('srfs'));
        }
    }
}
