<?php

namespace App\Http\Controllers\RegistrarCollege\ViewInfo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Session;
use PDF;

class ViewInfoController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function withdraw($value, $date_today,$idno) {
        if (Auth::user()->accesslevel == env("REG_COLLEGE")) {
            if ($value == "w") {
                $v = env('WITHDRAWN');
            } else if ($value == "e") {
                $v = env('ENROLLED');
            }
            $status = \App\Status::where('idno', $idno)->first();
            if ($value == "w") {
                $status->date_dropped = $date_today;
                $mes = "Withdraw";
            } else if ($value == "e") {
                $status->date_dropped = NULL;
                $mes = "Enrolled";
            }
            $status->status = $v;
            $status->save();

            $bedlevel = \App\CollegeLevel::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->first();
            $status = \App\Status::where('idno', $idno)->first();
            if ($value == "w") {
                $status->date_dropped = $date_today;
            } else if ($value == "e") {
                $status->date_dropped = NULL;
            }
            $bedlevel->status = $v;
            $bedlevel->save();

            \App\Http\Controllers\Accounting\SetReceiptController::log("$mes student $idno.");
            return redirect(url('/registrar_college', array('view_info', $idno)));
        }
    }

    function view_info($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            return view('reg_college.view_info.view', compact('idno', 'user', 'info'));
        }
    }

    function save_info(Request $request) {
//        return $request->f_is_living;
        $validate = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'municipality' => 'required',
            'province' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
        ]);

        if ($validate) {
            DB::beginTransaction();
            //$this->updateStatus($request);
            $this->updateInfo($request);
            $this->updateFamilyBackground($request);
            $this->updateEducBackground($request);
            $this->updateUser($request);
            
            \App\Http\Controllers\Admin\Logs::log("Update information of student: $request->idno");
            DB::Commit();
            
            Session::flash('message', 'Information Updated!');
            return redirect("registrar_college/view_info/$request->idno");
        }
    }
    
    function updateFamilyBackground($request){
        $updatefamilybackground = \App\StudentInfo::where('idno', $request->idno)->first();
        $updatefamilybackground->father = $request->father;
        $updatefamilybackground->f_is_living = $request->f_is_living;
        $updatefamilybackground->f_occupation = $request->f_occupation;
        $updatefamilybackground->f_phone = $request->f_phone;
        $updatefamilybackground->f_address = $request->f_address;
        $updatefamilybackground->mother = $request->mother;
        $updatefamilybackground->m_is_living = $request->m_is_living;
        $updatefamilybackground->m_occupation = $request->m_occupation;
        $updatefamilybackground->m_phone = $request->m_phone;
        $updatefamilybackground->m_address = $request->m_address;
        $updatefamilybackground->spouse = $request->spouse;
        $updatefamilybackground->s_is_living = $request->s_is_living;
        $updatefamilybackground->s_occupation = $request->s_occupation;
        $updatefamilybackground->s_phone = $request->s_phone;
        $updatefamilybackground->s_address = $request->s_address;
        $updatefamilybackground->save();
    }
    
    function updateEducBackground($request){
        $updateEducbackground = \App\StudentInfo::where('idno', $request->idno)->first();
        $updateEducbackground->primary = $request->primary;
        $updateEducbackground->primary_address = $request->primary_address;
        $updateEducbackground->primary_year = $request->primary_year;
        $updateEducbackground->gradeschool = $request->gradeschool;
        $updateEducbackground->gradeschool_address = $request->gradeschool_address;
        $updateEducbackground->gradeschool_year = $request->gradeschool_year;
        $updateEducbackground->highschool = $request->highschool;
        $updateEducbackground->highschool_address = $request->highschool_address;
        $updateEducbackground->highschool_year = $request->highschool_year;
        $updateEducbackground->senior_highschool = $request->senior_highschool;
        $updateEducbackground->senior_highschool_address = $request->senior_highschool_address;
        $updateEducbackground->senior_highschool_year = $request->senior_highschool_year;
        $updateEducbackground->last_school_attended = $request->last_school_attended;
        $updateEducbackground->last_school_address = $request->last_school_address;
        $updateEducbackground->last_school_year = $request->last_school_year;
        $updateEducbackground->save();
    }
    
    function updateStatus($request){
        
    }
    
    function updateInfo($request){  
        $updateInfo = \App\StudentInfo::where('idno', $request->idno)->first();
        $updateInfo->street = $request->street;
        $updateInfo->barangay = $request->barangay;
        $updateInfo->municipality = $request->municipality;
        $updateInfo->province = $request->province;
        $updateInfo->zip = $request->zip;
        $updateInfo->tel_no = $request->tel_no;
        $updateInfo->cell_no = $request->cell_no;
        $updateInfo->birthdate = $request->birthdate;
        $updateInfo->place_of_birth = $request->place_of_birth;
        $updateInfo->gender = $request->gender;
        $updateInfo->civil_status = $request->civil_status;
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
        $updateUser->email = $request->email;
        $updateUser->is_foreign = $request->is_foreign;
        $updateUser->status = $request->user_status;
        $updateUser->save();
    }
    

    function reset_password(Request $request) {
        if (Auth::user()->accesslevel == env("REG_COLLEGE")) {
            $user = \App\User::where('idno', $request->idno)->first();
            $user->password = bcrypt($request->password);
            $user->is_first_login = 1;
            $user->update();
            
            \App\Http\Controllers\Admin\Logs::log("Reset password of student: $request->idno");
            return redirect(url('/registrar_college', array('view_info', $request->idno)));
        }
    }
    
    function print_envelope($idno){
        if (Auth::user()->accesslevel == env("REG_COLLEGE")) {
            $student_info = \App\StudentInfo::where('idno', $idno)->first();
            $pdf = PDF::loadView('reg_college.view_info.envelope', compact('student_info'));           
            $pdf->setPaper(array(0,0,684,297));
            return $pdf->stream("envelope_$idno.pdf");
        }
    }

}
