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
               return view('reg_be.successfull');
            }
        }
    }
    function info($idno){
        if(Auth::user()->accesslevel==env("REG_BE")){
            $student = \App\User::where("idno",$idno)->first();
            return view("reg_be.info",compact('student'));
        }
    }
    
    function reset_password(Request $request){
        if(Auth::user()->accesslevel==env("REG_BE")){
            $user=  \App\User::where('idno',$request->idno)->first();
            $user->password = bcrypt($request->password);
            $user->is_first_login=1;
            $user->update();
            return redirect(url('/bedregistrar',array('info',$request->idno)));
        }
    }
    function student_list(){
        if(Auth::user()->accesslevel==env("REG_BE")){
            $students = \App\Status::where('academic_type',"BED")->where('status',env("ENROLLED"))->get();
            return view("reg_be.student_list",compact('students'));
        }
    }
    function updateinfo(Request $request) {
        if(Auth::user()->accesslevel==env("REG_BE")){
            $updateuser = \App\User::where('idno', $request->referenceid)->first();
            $updateuser->firstname = $request->firstname;
            $updateuser->lastname = $request->lastname;
            $updateuser->middlename = $request->middlename;
            $updateuser->extensionname = $request->extensionname;
            $updateuser->status = $request->user_status;
            $updateuser->lrn = $request->lrn;
            $updateuser->save();
            
            $addprofile = \App\BedProfile::where('idno', $request->referenceid)->first();
            if(count($addprofile)>0){
            $addprofile->date_of_birth = $request->date_of_birth;
            $addprofile->address = $request->address;
            $addprofile->contact_no = $request->contact_no;
            $addprofile->parent_name = $request->parent_name;
            $addprofile->parent_email = $request->parent_email;
            $addprofile->save();
            } else {
              $addpro = new \App\BedProfile;
              $addpro->idno = $request->referenceid;
              $addpro->address = $request->address;
              $addpro->contact_no = $request->contact_no;
              $addpro->parent_name = $request->parent_name;
              $addpro->parent_email = $request->parent_email;
              $addpro->save();
            }
            return redirect(url('/bedregistrar',array('info',$request->idno)));
        }
    }
}
