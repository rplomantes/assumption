<?php

namespace App\Http\Controllers\BedRegistrar\Ajax;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Request;
use PDF;

class GetStudentList extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
 function index(){
        if(Request::ajax()){
            if(Auth::user()->accesslevel==env("REG_BE")){
            $search = Input::get('search');
            $lists = \App\User::Where("lastname","like","%$search%")
                    ->orWhere("firstname","like","%$search%")->orWhere("idno",$search)->get();
            return view('reg_be.ajax.getstudentlist',compact('lists'));
        }
    }   
 } 
function view_list(){
    if(Request::ajax()){
        if(Auth::user()->accesslevel==env("REG_BE")){
            $schoolyear = Input::get('school_year');
            $level = Input::get('level');
            $section = Input::get('section');
            
            $strand=Input::get("strand");
            if($level=="Grade 11" || $level=="Grade 12"){
                if($section=="All"){
              
                 $status=DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                         . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                         . " and bed_levels.school_year = '$schoolyear' order by users.lastname, users.firstname, users.middlename");
                }else{
               
                 $status=DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                         . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                         . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' order by users.lastname, users.firstname, users.middlename");
            }}
         else {
            if($section=="All"){
                
                 $status=DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                         . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                         . " and bed_levels.school_year = '$schoolyear' order by users.lastname, users.firstname, users.middlename");
                
            } else {
           
             $status=DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                         . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                         . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' order by users.lastname, users.firstname, users.middlename");

            
         }}
        return view("reg_be.ajax.view_list",compact("status","level","section",'strand'));
       }
    }
}
 function getsection(){
     if(Request::ajax()){
         $level=Input::get("level");
         if($level=="Grade 11" || $level=="Grade 12"){
         $strand=Input::get("strand");
         $sections = \App\CtrSectioning::where('level',$level)->where('strand',$strand)->orderBy('section')->get();
         } else{
         $sections = \App\CtrSectioning::where('level',$level)->orderBy('section')->get();
         }
         return view('reg_be.ajax.getsection',compact('sections'));
     }
 }
 
 function print_student_list($level,$strand,$section,$schoolyear){
     if($level=="Grade 11" || $level=="Grade 12"){
                if($section=="All"){
              
                 $status=DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                         . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                         . " and bed_levels.school_year = '$schoolyear' order by users.lastname, users.firstname, users.middlename");
                }else{
               
                 $status=DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                         . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                         . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' order by users.lastname, users.firstname, users.middlename");
            }}
         else {
            if($section=="All"){
                
                 $status=DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                         . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                         . " and bed_levels.school_year = '$schoolyear' order by users.lastname, users.firstname, users.middlename");
                
            } else {
           
             $status=DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                         . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                         . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' order by users.lastname, users.firstname, users.middlename");

            
         }}
         $pdf = PDF::loadView("reg_be.view_list",compact("status","level","section",'strand'));
         $pdf->setPaper(array(0,0,612,936));
         return $pdf->stream();
         
 }
 
 function studentlevel(){
   if(Request::ajax()){
      $school_year = \App\CtrAcademicSchoolYear::where('academic_type','BED')->first(); 
      $strand=""; 
      $schoolyear=$school_year->school_year;
      $level = Input::get('level');
      $section = Input::get('section');
      if($level=="Grade 11" || $level=="Grade 12"){
          $strand = Input::get('strand');
          //$students =  \App\BedLevel::where('level',$level)->where('strand',$strand)->where('school_year',$school_year->school_year)->where('section','!=',$section)->get();
          $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  bed_levels.idno as idno, "
                  . " bed_levels.level as level, bed_levels.strand as strand, bed_levels.section as section from users, bed_levels where users.idno = bed_levels.idno "
                  . " and bed_levels.level = '$level' and bed_levels.school_year = '$schoolyear' and bed_levels.section != '$section' and bed_levels.strand= '$strand' order by lastname, firstname, middlename");
      } else {
           //$students =  \App\BedLevel::where('level',$level)->where('school_year',$school_year->school_year)->where('section','!=',$section)->get();
          $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  bed_levels.idno as idno, "
                  . " bed_levels.level as level, bed_levels.strand as strand, bed_levels.section as section from users, bed_levels where users.idno = bed_levels.idno "
                  . " and bed_levels.level = '$level' and  bed_levels.school_year = '$schoolyear' and bed_levels.section != '$section'  order by lastname, firstname, middlename");
      }
      return view('reg_be.ajax.studentlevel_list',compact('level','strand','students','school_year'));
   }  
     
 }
 
 function sectioncontrol(){
     if(Request::ajax()){
      $school_year = \App\CtrAcademicSchoolYear::where('academic_type','BED')->first(); 
      $strand=""; 
      $level = Input::get('level');
      if($level=="Grade 11" || $level=="Grade 12"){
          $strand = Input::get('strand');
          $sections = \App\CtrSectioning::where('level',$level)->where('strand',$strand)->get();
          
      } else {
          $sections = \App\CtrSectioning::where('level',$level)->get();
      }
      return view('reg_be.ajax.sectioncontrol',compact('level','strand','sections'));
   }  
 }
 
 function pop_section_list(){
       if(Request::ajax()){
      $school_year = \App\CtrAcademicSchoolYear::where('academic_type','BED')->first(); 
      $schoolyear = $school_year->school_year;
      $strand=""; 
      $level = Input::get('level');
      $section = Input::get('section');
      if($level=="Grade 11" || $level=="Grade 12"){
          $strand = Input::get('strand');
          //$students =  \App\BedLevel::where('level',$level)->where('strand',$strand)->where('school_year',$school_year->school_year)->where('section','=',$section)->get();
           $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  bed_levels.idno as idno, "
                  . " bed_levels.level as level, bed_levels.strand as strand, bed_levels.section as section from users, bed_levels where users.idno = bed_levels.idno "
                  . " and bed_levels.level = '$level' and bed_levels.school_year = '$schoolyear' and bed_levels.section = '$section' and bed_levels.strand= '$strand' order by lastname, firstname, middlename");
      } else {
           //$students =  \App\BedLevel::where('level',$level)->where('school_year',$school_year->school_year)->where('section','=',$section)->get();
           $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  bed_levels.idno as idno, "
                  . " bed_levels.level as level, bed_levels.strand as strand, bed_levels.section as section from users, bed_levels where users.idno = bed_levels.idno "
                  . " and bed_levels.level = '$level' and bed_levels.school_year = '$schoolyear' and bed_levels.section = '$section' order by lastname, firstname, middlename");
          }
      return view('reg_be.ajax.studentlevel',compact('level','strand','students','school_year'));
   }
 }
 
 function change_section(){
     if(Request::ajax()){
         $idno = Input::get('idno');
         $level = Input::get('level');
         $section = Input::get('section');
         $bedlevel = \App\BedLevel::where('idno',$idno)->where('level',$level)->first();
         $bedlevel->section = $section;
         $bedlevel->update();
         $status = \App\Status::where('idno',$idno)->where('level',$level)->first();
         $status->section = $section;
         $status->update();
         
     }
 }
}
