<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class Updater extends Controller
{
    //
    
    function updateBedLevel(){
        $users =  \App\User::where('accesslevel',0)->where('is_first_login',1)->where('academic_type','BED')->get();
        foreach($users as $user){
            $update = \App\User::find($user->id);
            $update->password = bcrypt(strtolower($user->idno));
            $update->update();
        }
        return "Done";
    }
    
    function updateCollege(){
        $users =  \App\User::where('accesslevel',0)->where('is_first_login',1)->where('academic_type','College')->get();
        foreach($users as $user){
            $update = \App\User::find($user->id);
            $update->password = bcrypt(strtolower($user->idno));
            $update->status = 1;
            $update->update();
        }
        return "Done";
    }
    
    function updateInstructor(){
        $users =  \App\User::where('accesslevel',1)->where('is_first_login',1)->get();
        foreach($users as $user){
            $update = \App\User::find($user->id);
            $update->password = bcrypt(strtolower($user->idno));
            $update->update();
        }
        return "Done";
    }
      /*  
        $data = DB::Select("Select * from partial_student_discount");
        $notmuch="";
        foreach($data as $dat){
            $find = \App\Promotion::where('idno',$dat->idno)->first();
            if(count($find)>0){
            $find->discount = $dat->discount;
            $find->update();
            } else {
             $notmuch=$notmuch."-".$dat->idno;   
            }
        }
        
        return $notmuch;
       
    */   
          
  /*       
        $students = \App\Status::where('level','Pre-Kinder')->get();
        $current_level="";
        foreach($students as $student){
            $add = new \App\Promotion;
            $add->idno=$student->idno;
            switch ($student->level){
case "Pre-Kinder":
    $current_level = "Kinder";
    break;

case "Kinder":
    $current_level = "Grade 1";
    break;
case "Grade 1":
    $current_level = "Grade 2";
    break;    
case "Grade 2":
    $current_level = "Grade 3";
    break;
case "Grade 3":
    $current_level = "Grade 4";
    break;
case "Grade 4":
    $current_level = "Grade 5";
    break;
case "Grade 5":
    $current_level = "Grade 6";
    break;
case "Grade 6":
    $current_level = "Grade 7";
    break;
case "Grade 7":
    $current_level = "Grade 8";
    break;
case "Grade 8":
    $current_level = "Grade 9";
    break;
case "Grade 9":
    $current_level = "Grade 10";
    break;
case "Grade 10":
    $current_level = "Grade 11";
    break;
case "Grade 11":
    $current_level = "Grade 12";
    break;

 
}
    $add->level = $current_level;
    $add->section = $student->section;
    $add->section = $student->section;
    $add->strand = $student->strand;
    $add->save();
            
        }*/
 /*
        $levels = \App\Status::where('level','Kinder')->get();
        foreach($levels as $level){
            $add = new \App\BedLevel;
            $add->idno = $level->idno;
            $add->level = $level->level;
            $add->strand = $level->strand;
            $add->track = $level->track;
            $add->section = $level->section;
            $add->status =$level->status;
            $add->department = $level->department;
            $add->school_year = $level->school_year;
            $add->period = $level->period;
            $add->save();
            
        
    }
       */
       /*
        $users = \App\User::where('level','Pre-Kinder')->get();
        foreach($users as $user){
            $add = new \App\Status;
            $add->idno = $user->idno;
            $add->level = $user->level;
            $add->section = $user->section;
            $add->academic_type = "BED";
            $add->status = "3";
            if($user->is_new == "New"){
                $add->is_new=1;
            } else {
                $add->is_new=0;
            }
            $add->department = "Pre-Kinder";
            $add->school_year="2017";
            $add->save();
        }*/
}
