<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use Session;
use Mail;

class Registration extends Controller {

    //

    public function __construct() {
        $this->middleware('auth');
    }

    function register() {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $referenceid = uniqid();
            return view('reg_be.registration', compact('referenceid'));
        }
    }

    function post_register(Request $request) {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $validate = $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required',
            ]);

            if ($validate) {
                DB::beginTransaction();
                $addstudent = new \App\User;
                $addstudent->idno = $request->referenceid;
                $addstudent->lastname = $request->lastname;
                $addstudent->firstname = $request->firstname;
                $addstudent->middlename = $request->middlename;
                $addstudent->extensionname = $request->extensionname;
                $addstudent->is_foreign = $request->is_foreign;
                $addstudent->academic_type = "BED";
                $addstudent->lrn = $request->lrn;
                $addstudent->save();

                $addprofile = new \App\BedProfile;
                $addprofile->idno = $request->referenceid;
                $addprofile->date_of_birth = $request->date_of_birth;
                $addprofile->street = $request->street;
                $addprofile->barangay = $request->barangay;
                $addprofile->municipality = $request->municipality;
                $addprofile->province = $request->province;
                $addprofile->zip = $request->zip;
                $addprofile->tel_no = $request->tel_no;
                $addprofile->cell_no = $request->cell_no;
                $addprofile->save();

                $addstatus = new \App\Status;
                $addstatus->idno = $request->referenceid;
                $addstatus->section = "";
                $addstatus->status = 0;
                $addstatus->academic_type = "BED";
                $addstatus->save();
                
                $addParent = new \App\BedParentInfo;
                $addParent->idno = $request->referenceid;
                $addParent->save();

                \App\Http\Controllers\Admin\Logs::log("Register new student - [$request->referenceid]: $request->lastname, $request->firstname $request->middlename.");
                DB::Commit();
                return view('reg_be.successfull');
            }
        }
    }

    function info($idno) {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("ADMISSION_BED")) {
            
            $addprofile = \App\BedProfile::where('idno', $idno)->first();
            if (count($addprofile) == 0) {
                $addpro = new \App\BedProfile;
                $addpro->idno = $idno;
                $addpro->save();
            }
            $addparent = \App\BedParentInfo::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedParentInfo;
                $addpar->idno = $idno;
                $addpar->save();
            }
            
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\BedProfile::where('bed_profiles.idno', $idno)->join('bed_parent_infos', 'bed_parent_infos.idno','=','bed_profiles.idno')->first();
            
            return view("reg_be.info", compact('user', 'info'));
        }
    }

    function reset_password(Request $request) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $user = \App\User::where('idno', $request->idno)->first();
            $user->password = bcrypt($request->password);
            $user->is_first_login = 1;
            $user->update();
            \App\Http\Controllers\Accounting\SetReceiptController::log("Reset password of $request->idno.");
            return redirect(url('/bedregistrar', array('info', $request->idno)));
        }
    }

    function student_list() {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env('ADMISSION_BED')) {
            $students = \App\Status::where('academic_type', "BED")->where('status', env("ENROLLED"))->get();
            return view("reg_be.student_list", compact('students'));
        }
    }

    function updateinfo(Request $request) {

        if (Auth::user()->accesslevel == env("REG_BE")) {
            $validate = $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'municipality' => 'required',
                'province' => 'required',
                'date_of_birth' => 'required',
                'gender' => 'required',
                'email' => 'required',
            ]);

            if ($validate) {
                DB::beginTransaction();
                $this->updateInfoNow($request);
                $this->updateFamilyBackground($request);
                $this->updateEducBackground($request);
                $this->updateUser($request);

                \App\Http\Controllers\Admin\Logs::log("Update student information of $request->idno.");
                $status= \App\Status::where('idno', $request->idno)->first();
                if($status->status == env("PRE_REGISTERED")){
                    $status->status = env("FOR_APPROVAL");
                    $status->save();
                    
                    $testing = new \App\TestingStudent;
                    $testing->idno = $request->idno;
                    $testing->save();
                    
                \App\Http\Controllers\Admin\Logs::log("Update student'status to REGISTERED of $request->idno.");
                }
                
                DB::Commit();

                Session::flash('message', 'Information Updated!');
                return redirect(url('/bedregistrar', array('info', $request->idno)));
            }
        }
    }
    
    function updateFamilyBackground($request){
        $updatefamilybackground = \App\BedParentInfo::where('idno', $request->idno)->first();
        $updatefamilybackground->father = $request->father;
        $updatefamilybackground->f_citizenship = $request->f_citizenship;
        $updatefamilybackground->f_is_living = $request->f_is_living;
        $updatefamilybackground->f_religion = $request->f_religion;
        $updatefamilybackground->f_education = $request->f_education;
        $updatefamilybackground->f_school = $request->f_school;
        $updatefamilybackground->f_occupation = $request->f_occupation;
        $updatefamilybackground->f_company_name = $request->f_company_name;
        $updatefamilybackground->f_company_address = $request->f_company_address;
        $updatefamilybackground->f_company_number = $request->f_company_number;
        $updatefamilybackground->f_phone = $request->f_phone;
        $updatefamilybackground->f_cell_no = $request->f_cell_no;
        $updatefamilybackground->f_address = $request->f_address;
        $updatefamilybackground->f_email = $request->f_email;
        $updatefamilybackground->f_any_org = $request->f_any_org;
        $updatefamilybackground->f_type_of_org = $request->f_type_of_org;
        $updatefamilybackground->f_expertise = $request->f_expertise;
        
        
        $updatefamilybackground->mother = $request->mother;
        $updatefamilybackground->m_citizenship = $request->m_citizenship;
        $updatefamilybackground->m_is_living = $request->m_is_living;
        $updatefamilybackground->m_religion = $request->m_religion;
        $updatefamilybackground->m_education = $request->m_education;
        $updatefamilybackground->m_school = $request->m_school;
        $updatefamilybackground->m_occupation = $request->m_occupation;
        $updatefamilybackground->m_company_name = $request->m_company_name;
        $updatefamilybackground->m_company_address = $request->m_company_address;
        $updatefamilybackground->m_company_number = $request->m_company_number;
        $updatefamilybackground->m_phone = $request->m_phone;
        $updatefamilybackground->m_cell_no = $request->m_cell_no;
        $updatefamilybackground->m_address = $request->m_address;
        $updatefamilybackground->m_email = $request->m_email;
        $updatefamilybackground->m_any_org = $request->m_any_org;
        $updatefamilybackground->m_type_om_org = $request->m_type_om_org;
        $updatefamilybackground->m_expertise = $request->m_expertise;
        
        
        $updatefamilybackground->parents_civil_status = $request->parents_civil_status;
        $updatefamilybackground->guardian = $request->guardian;
        $updatefamilybackground->g_relation = $request->g_relation;
        $updatefamilybackground->g_address = $request->g_address;
        $updatefamilybackground->g_contact_no = $request->g_contact_no;
        
        $updatefamilybackground->save();
    }
    
    function updateEducBackground($request){
        $updateEducbackground = \App\BedProfile::where('idno', $request->idno)->first();
        
        $updateEducbackground->present_school = $request->present_school;
        $updateEducbackground->present_school_address = $request->present_school_address;
        $updateEducbackground->present_principal = $request->present_principal;
        $updateEducbackground->present_tel_no = $request->present_tel_no;
        $updateEducbackground->present_guidance = $request->present_guidance;
        
        $updateEducbackground->primary = $request->primary;
        $updateEducbackground->primary_address = $request->primary_address;
        $updateEducbackground->primary_year = $request->primary_year;
        $updateEducbackground->gradeschool = $request->gradeschool;
        $updateEducbackground->gradeschool_address = $request->gradeschool_address;
        $updateEducbackground->gradeschool_year = $request->gradeschool_year;
        $updateEducbackground->highschool = $request->highschool;
        $updateEducbackground->highschool_address = $request->highschool_address;
        $updateEducbackground->highschool_year = $request->highschool_year;
//        
        $updateEducbackground->save();
    }
    
    function updateInfoNow($request){  
        $updateInfo = \App\BedProfile::where('idno', $request->idno)->first();
        $updateInfo->street = $request->street;
        $updateInfo->barangay = $request->barangay;
        $updateInfo->municipality = $request->municipality;
        $updateInfo->province = $request->province;
        $updateInfo->zip = $request->zip;
        $updateInfo->tel_no = $request->tel_no;
        $updateInfo->cell_no = $request->cell_no;
        $updateInfo->date_of_birth = $request->date_of_birth;
        $updateInfo->place_of_birth = $request->place_of_birth;
        $updateInfo->gender = $request->gender;
        $updateInfo->nationality = $request->nationality;
        $updateInfo->religion = $request->religion;
        
        $updateInfo->immig_status = $request->immig_status;
        $updateInfo->auth_stay = $request->auth_stay;
        $updateInfo->passport = $request->passport;
        $updateInfo->passport_exp_date = $request->passport_exp_date;
        $updateInfo->passport_place_issued = $request->passport_place_issued;
        $updateInfo->acr_no = $request->acr_no;
        $updateInfo->acr_date_issued = $request->acr_date_issued;
        $updateInfo->acr_place_issued = $request->acr_place_issued;
        $updateInfo->save();
    }
    
    function updateUser($request){
        $updateUser = \App\User::where('idno',$request->idno)->first();
        $updateUser->firstname = $request->firstname;
        $updateUser->middlename = $request->middlename;
        $updateUser->lastname = $request->lastname;
        $updateUser->extensionname = $request->extensionname;
        $updateUser->is_foreign = $request->is_foreign;
        $updateUser->status = $request->user_status;
        $updateUser->save();
    }

    function sectioning() {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("GUIDANCE_BED")) {
            return view("reg_be.sectioning");
        }
    }

}
