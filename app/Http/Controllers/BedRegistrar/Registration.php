<?php

namespace App\Http\Controllers\BedRegistrar;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;


class Registration extends Controller
{
    //
    
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function register(){
        if(Auth::user()->accesslevel == env("REG_BE")){
            $referenceid = uniqid();
            return view('reg_be.registration',compact('referenceid'));
        }
    }
    function post_register(Request $request){
        if(Auth::user()->accesslevel == env("REG_BE")){
            $validate = $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
            ]);
            
            if($validate){
               DB::beginTransaction(); 
               $addstudent = new \App\User;
               $addstudent->idno = $request->referenceid;
               $addstudent->lastname = $request->lastname;
               $addstudent->firstname = $request->firstname;
               $addstudent->middlename = $request->middlename;
               $addstudent->extensionname = $request->extensionname;
               $addstudent->academic_type="BED";
               $addstudent->lrn = $request->lrn;
               $addstudent->save();
               
               $addprofile = new \App\BedProfile;
               $addprofile->idno = $request->referenceid;
               $addprofile->date_of_birth = $request->date_of_birth;
               $addprofile->address = $request->address;
               $addprofile->contact_no = $request->contact_no;
               $addprofile->parent_name = $request->parent_name;
               $addprofile->parent_email = $request->parent_email;
               $addprofile->save();
               
               $addstatus = new \App\Status;
               $addstatus->idno = $request->referenceid;
               $addstatus->status = 0;
               $addstatus->academic_type="BED";
               $addstatus->save();
               DB::Commit();
               return view('reg_be.sucessfull');
            }
        }
    }
}
