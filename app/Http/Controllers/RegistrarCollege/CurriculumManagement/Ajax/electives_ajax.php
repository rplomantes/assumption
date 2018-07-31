<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class electives_ajax extends Controller {

    //
    function getelectives() {
        if (Request::ajax()) {
            $program_code = Input::get("program_code");
            $electives = \App\CtrElective::where('program_code', $program_code)->get();
            
            $data = "<div class='box'>"
                    . "<div class='box-body'>"
                    . "<table class='table table-striped'>"
                    . "<thead>"
                    . "<tr>"
                    . "<th>Curriculum Year</th><th>Course Code</th><th>Course Name</th><th>Lec</th><th>Lab</th><th>SRF</th><th>Lab Fee</th><th>Remove</th>"
                    . "</tr>"
                    . "</thead><tbody>"; 
            
            foreach($electives as $elective){
                $data = $data . "<tr>"
                        . "<td>". $elective->curriculum_year ."</td>"
                        . "<td>". $elective->course_code ."</td>"
                        . "<td>". $elective->course_name ."</td>"
                        . "<td>". $elective->lec ."</td>"
                        . "<td>". $elective->lab ."</td>"
                        . "<td>". $elective->srf ."</td>"
                        . "<td>". $elective->lab_fee ."</td>"
                        . "<td><a href='javascript:void(0)' onclick='remove_electives(". $elective->id .", program_code.value)'>Remove</a></td>"
                        . "</tr>";
            }
            $data = $data . "</tbody></table>";
            return $data;
        }
    }
    
    function addelectives(){
        if (Request::ajax()) {
            $course_code = Input::get("course_code");
            $course_name = Input::get("course_name");
            $lec = Input::get("lec");
            $lab = Input::get("lab");
            $curriculum_year = Input::get("curriculum_year");
            $program_code = Input::get("program_code");
            $srf = Input::get("srf");
            $lab_fee = Input::get("lab_fee");
            
            $program_name = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->program_name;
            
            $addelectives = new \App\CtrElective();
            $addelectives->curriculum_year = $curriculum_year;
            $addelectives->course_code = $course_code;
            $addelectives->course_name = $course_name;
            $addelectives->lec = $lec;
            $addelectives->lab = $lab;
            $addelectives->srf = $srf;
            $addelectives->lab_fee = $lab_fee;
            $addelectives->program_code = $program_code;
            $addelectives->program_name = $program_name;
            $addelectives->save();
        }
    }
    
    function removeelectives(){
        if (Request::ajax()) {
            $id = Input::get("id");
            $program_code = Input::get("program_code");
            
            $remove = \App\CtrElective::where('id',$id)->first();
            $remove->delete();
        }
    }

}
