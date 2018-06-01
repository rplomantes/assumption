<?php

namespace App\Http\Controllers\AdmissionHED;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Session;

class ViewInfoAdmissionHedController extends Controller
{
    function view_info($idno){
        if(Auth::user()->accesslevel == env('ADMISSION_HED')){
            $users = \App\User::where('idno', $idno)->first();   
            $adhedinfo = \App\AdmissionHed::where('idno', $idno)->first();
            $studentinfos = \App\StudentInfo::where('idno',$idno)->first();
            $admissionreq = \App\AdmissionHedRequirements::where('idno', $idno)->first();
        return view('admission-hed.viewinfo.view', compact('idno','users','adhedinfo', 'studentinfos', 'admissionreq'));
        }
    }
    
    function update_info(Request $request){
        $validate = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'municipality' => 'required',
            'province' => 'required',
            'birthdate' => 'required',
//            'gender' => 'required',
            'email' => 'required',            
        ]);        
        if ($validate){
            DB::beginTransaction();
                $this->updatePersonalInfo($request);
                $this->update_admission_checklist($request);
            DB::Commit();        
            
            Session::flash('message', "Information Updated!");
            
            return redirect(url('admission_hed', array('view_info', $request->idno)));
        }
    }
        function updatePersonalInfo($request){
            $updatePersonalInfo = \App\User::where('idno', $request->idno)->first();
            $updatePersonalInfo->firstname = $request->firstname;
            $updatePersonalInfo->middlename = $request->middlename;
            $updatePersonalInfo->lastname = $request->lastname;
            $updatePersonalInfo->extensionname = $request->extensionname;
            $updatePersonalInfo->email = $request->email;   
            $updatePersonalInfo->is_foreign = $request->is_foreign;
            $updatePersonalInfo->save();            
            
            $updatePersonalInfo = \App\StudentInfo::where('idno', $request->idno)->first();
            $updatePersonalInfo->street = $request->street;
            $updatePersonalInfo->barangay = $request->barangay;
            $updatePersonalInfo->municipality = $request->municipality;
            $updatePersonalInfo->province = $request->province;
            $updatePersonalInfo->zip = $request->zip;
            $updatePersonalInfo->birthdate = $request->birthdate;
            $updatePersonalInfo->place_of_birth = $request->place_of_birth;
            $updatePersonalInfo->gender = $request->gender;
            $updatePersonalInfo->tel_no = $request->tel_no;
            $updatePersonalInfo->cell_no = $request->cell_no;
            $updatePersonalInfo->civil_status = $request->civil_status;
            $updatePersonalInfo->nationality = $request->nationality;
            $updatePersonalInfo->religion = $request->religion;
            $updatePersonalInfo->last_school_attended = $request->last_school_attended;
            $updatePersonalInfo->save();
            
            $updatePersonalInfo = \App\AdmissionHed::where('idno', $request->idno)->first();
            $updatePersonalInfo->assumption_scholar = $request->assumption_scholar;
            $updatePersonalInfo->partner_scholar = $request->partner_scholar;
            $updatePersonalInfo->agreement = $request->agreement;
            $updatePersonalInfo->admission_status = $request->admission_status;
            $updatePersonalInfo->summer_classes = $request->summer_classes;
            $updatePersonalInfo->program_code = $request->program_code;
            $updatePersonalInfo->program_name = $request->program_name;
            $updatePersonalInfo->strand = $request->strand;
            $updatePersonalInfo->student_status = $request->student_status;
            $updatePersonalInfo->tagged_as = $request->tagged_as;
            $updatePersonalInfo->applying_for = $request->applying_for;
            
//            if($request->admission_status == 'Probationary'){
//                $updatePersonalInfo->admission_status = $request->agreement;
//            }else{
//                $updatePersonalInfo->admission_status = $request->admission_status;
//            }
            if($request->conditionType == 'Others'){
                $updatePersonalInfo->condition = $request->specifyCondition;
            }else{
                $updatePersonalInfo->condition = $request->conditionType; 
            }
            $updatePersonalInfo->save();
    }
    
        function update_admission_checklist($request){
            $update_admission_checklist = \App\AdmissionHedRequirements::where('idno', $request->idno)->first();
            $update_admission_checklist->birth_certificate = $request->birth_certificate;
            $update_admission_checklist->form138 = $request->form138;
            $update_admission_checklist->labtest = $request->labtest;
            $update_admission_checklist->admission_agreement = $request->admission_agreement;
            $update_admission_checklist->parent_partnership = $request->parent_partnership;
            $update_admission_checklist->tor = $request->tor;
            $update_admission_checklist->honor_dismiss = $request->honor_dismiss;
            $update_admission_checklist->course_desc = $request->course_desc;
            $update_admission_checklist->cbc = $request->cbc;
            $update_admission_checklist->bt = $request->bt;
            $update_admission_checklist->x_ray = $request->x_ray;
            $update_admission_checklist->visa = $request->visa;
            $update_admission_checklist->passport = $request->passport;
            $update_admission_checklist->photocopy_diploma = $request->photocopy_diploma;
            $update_admission_checklist->marriage_contract = $request->marriage_contract;        
            $update_admission_checklist->child_birth_cert = $request->child_birth_cert;        
            $update_admission_checklist->save();
    }
}
