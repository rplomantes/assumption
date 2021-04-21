<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SetupDueDate extends Controller
{
    public function __construct() {
        $this->middleware("auth");
    }
    
    function due_date(){
        return view("accounting.setup_due_date.academic_type");
    }
    
    function index_due_date($academic_type){
        if($academic_type == "College"){
            $duedates = \App\CtrDueDate::get();
        }else{
            $duedates = \App\CtrDueDateBed::get();
        }
        
        return view("accounting.setup_due_date.duedate", compact("duedates","academic_type"));
    }
    
    function new_due_date(Request $request){
       
           $newduedate = new \App\CtrDueDate;
           $newduedate->type_of_period = $request->type_of_period;
           $newduedate->school_year = $request->school_year;
           $newduedate->period = $request->period;
           $newduedate->due_date = $request->due_date;
           $newduedate->save();
           
           return back()->withSuccess("New due date have been set ".$request->school_year." ".$request->period);
       
    }
    
    function view_due_date($academic_type, $plan){
       
        if($academic_type == "College"){
            $duedates = \App\CtrDueDate::where("plan", $plan)->get();
        }else{
            $duedates = \App\CtrDueDateBed::where("plan", $plan)->get();
        }
       return view("accounting.setup_due_date.viewduedate", compact("duedates","plan","academic_type"));
    }
    
    function update_due_date(Request $request){
      
          $academic_type = $request->academic_type;
          $plan = $request->plan;
          
          foreach($request->new_dates as $key=>$currentduedate){
              if($academic_type == "College"){
                $duedates = \App\CtrDueDate::where("plan", $plan)->where("due_date", $key)->get();
              }else{
                $duedates = \App\CtrDueDateBed::where("plan", $plan)->where("due_date", $key)->get();
              }
              
              foreach($duedates as $updatedate){
                  $updatedate->due_date = $request->new_dates[$key];
                  $updatedate->update();
              }
          }
          
          return back()->withSuccess("You have updated the due date!");
    }  
    
}
