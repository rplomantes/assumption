<?php

namespace App\Http\Controllers\CollegeInstructor\Grades\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class GradesAjaxController extends Controller
{
    //
    function change_midterm($idno){
        if (Request::ajax()) {
            $grade = Input::get("grade");
            $grade_id = Input::get("grade_id");
            
            $update_grades = \App\GradeCollege::where('id', $grade_id)->where('idno', $idno)->first();
            $update_grades->midterm = $grade;
            $update_grades->save();
        }
    }
    function change_midterm_absences($idno){
        if (Request::ajax()) {
            $grade = Input::get("grade");
            $grade_id = Input::get("grade_id");
            
            $update_grades = \App\GradeCollege::where('id', $grade_id)->where('idno', $idno)->first();
            $update_grades->midterm_absences = $grade;
            $update_grades->save();
        }
    }
    function change_finals_absences($idno){
        if (Request::ajax()) {
            $grade = Input::get("grade");
            $grade_id = Input::get("grade_id");
            
            $update_grades = \App\GradeCollege::where('id', $grade_id)->where('idno', $idno)->first();
            $update_grades->finals_absences = $grade;
            $update_grades->save();
        }
    }
    function change_finals($idno){
        if (Request::ajax()) {
            $grade = Input::get("grade");
            $grade_id = Input::get("grade_id");
            
            $update_grades = \App\GradeCollege::where('id', $grade_id)->where('idno', $idno)->first();
            $update_grades->finals = $grade;
            $update_grades->save();
        }
    }
    function change_grade_point($idno){
        if (Request::ajax()) {
            $grade = Input::get("grade");
            $grade_id = Input::get("grade_id");
            
            $update_grades = \App\GradeCollege::where('id', $grade_id)->where('idno', $idno)->first();
            $update_grades->grade_point = $grade;
            $update_grades->save();
        }
    }
}
