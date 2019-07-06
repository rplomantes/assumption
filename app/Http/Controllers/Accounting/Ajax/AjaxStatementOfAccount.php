<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Request;

class AjaxStatementOfAccount extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function get_section(){
        if(Request::ajax()){
            $level = Input::get('level');
            $strand = Input::get('strand');
            
            if($level == "Grade 11" || $level == "Grade 12"){
                $strand = $strand;                
                if ($strand == "ALL") {
                    $qstrand = "";
                } else {
                    $qstrand = "and strand = '" . $strand . "'";
                }
            }else{
                $qstrand= "";
                $strand = NULL;
            }
            if ($level == "ALL") {
                $qlevel = "";
            } else {
                $qlevel = "and level = '" . $level . "'";
            }
            
            if($level == "Grade 11" || $level == "Grade 12"){
                $sections = \App\CtrSectioning::distinct()->whereRaw("id is not null $qstrand $qlevel")->get(['section']);
            }else{
                $sections = \App\CtrSectioning::distinct()->whereRaw("id is not null $qlevel")->get(['section']);
            }
            return view('accounting.ajax.get_section',compact('sections'));
        }
    }
    function get_soa(){
        if(Request::ajax()){
            $plan = Input::get('plan');
            $level = Input::get('level');
            $strand = Input::get('strand');
            $section = Input::get('section');
            $due_date = Input::get('due_date');
            $remarks = Input::get('remarks');
            
            if($level == "Grade 11" || $level == "Grade 12"){
                $strand = $strand;                
                if ($strand == "ALL") {
                    $qstrand = "";
                } else {
                    $qstrand = "and strand = '" . $strand . "'";
                }
            }else{
                $qstrand= "";
                $strand = NULL;
            }
            
            if ($plan == "ALL") {
                $qplan = "";
            } else {
                $qplan = "and type_of_plan = '" . $plan . "'";
            }
            if ($level == "ALL") {
                $qlevel = "";
            } else {
                $qlevel = "and level = '" . $level . "'";
            }
            if ($section == "ALL") {
                $qsection = "";
            } else {
                $qsection = "and section = '" . $section . "'";
            }
            $students = \App\Status::whereRaw("statuses.id is not null $qplan $qlevel $qstrand $qsection and statuses.status=3")
                    ->where('statuses.academic_type', "!=",'College')
                    ->where('statuses.status', 3)
                    ->join('users', 'users.idno','=','statuses.idno')
                    ->orderBy('users.lastname', 'asc')
                    ->get();
            
            return view('accounting.ajax.display_student',compact('students', 'plan','level','strand','section','due_date','remarks'));
        }
    }
}
