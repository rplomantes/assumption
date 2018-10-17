<?php

namespace App\Http\Controllers\RegistrarCollege\Instructor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class ViewInstructorsController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN')) {
            return view('reg_college.instructor.view_instructor');
        }
    }

    function view_add() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN')) {
            return view('reg_college.instructor.view_add_instructor');
        }
    }

    function add(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN')) {
            $this->validate($request, [
                'firstname' => 'required',
                'lastname' => 'required',
                'gender' => 'required',
                'email' => 'required',
                'employment_status' => 'required',
                'idno' => 'required',
            ]);

            return $this->create_new_instructor($request);
        }
    }

    function create_new_instructor($request) {
        DB::beginTransaction();
        $this->adduser($request);
        $this->addinstructorinfo($request);
        
            \App\Http\Controllers\Admin\Logs::log("Create new instructor idno: $request->idno");
        DB::commit();

        return redirect(url('/registrar_college/instructor/view_instructor'));
    }

    function adduser($request) {
        $add_new_user = new \App\User;
        $add_new_user->idno = $request->idno;
        $add_new_user->firstname = $request->firstname;
        $add_new_user->middlename = $request->middlename;
        $add_new_user->lastname = $request->lastname;
        $add_new_user->extensionname = $request->extensionname;
        $add_new_user->accesslevel = 1;
        $add_new_user->status = 1; //active or not
        $add_new_user->email = $request->email;
        $password = bcrypt($request->lastname);
        $add_new_user->password = $password;
        $add_new_user->save();
    }

    function addinstructorinfo($request) {
        $add_info = new \App\InstructorsInfo;
        $add_info->idno = $request->idno;
        $add_info->employment_status = $request->employment_status;
        $add_info->academic_type = $request->academic_type;
        $add_info->department = $request->department;
        $add_info->birthdate = $request->birthdate;
        $add_info->place_of_birth = $request->place_of_birth;
        $add_info->gender = $request->gender;
        $add_info->civil_status = $request->civil_status;
        $add_info->nationality = $request->nationality;
        $add_info->religion = $request->religion;
        $add_info->street = $request->street;
        $add_info->barangay = $request->barangay;
        $add_info->municipality = $request->municipality;
        $add_info->province = $request->province;
        $add_info->zip = $request->zip;
        $add_info->tel_no = $request->tel_no;
        $add_info->cell_no = $request->cell_no;
        $add_info->degree_status = $request->degree_status;
        $add_info->program_graduated = $request->program_graduated;
        $add_info->save();
    }

    function view_modify($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN')) {
            $user_info = \App\User::where('idno', $idno)->first();
            $instructor_info = \App\InstructorsInfo::where('idno', $idno)->first();
            
            if(count($instructor_info)==0) {
                $add_info = new \App\InstructorsInfo;
                $add_info->idno = $idno;
                $add_info->save();
            }

            return view('reg_college.instructor.view_modify', compact('user_info', 'instructor_info', 'idno'));
        }
    }

    function modify(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN')) {
            $this->validate($request, [
                'firstname' => 'required',
                'lastname' => 'required',
                'municipality' => 'required',
                'province' => 'required',
                'birthdate' => 'required',
                'gender' => 'required',
                'email' => 'required',
                'employment_status' => 'required',
                'idno' => 'required',
            ]);

            return $this->modify_old_instructor($request);
        }
    }

    function modify_old_instructor($request) {
        DB::beginTransaction();
        $this->modifyuser($request);
        $this->modifyinstructorinfo($request);
            \App\Http\Controllers\Admin\Logs::log("Update information of instructor $request->idno");
        DB::commit();

        return redirect(url('registrar_college', array('instructor', 'view_instructor')));
    }
    
    function modifyuser($request){
        $idno = $request->idno;

        $modify_user = \App\User::where('idno', $idno)->first();
        $modify_user->firstname = $request->firstname;
        $modify_user->middlename = $request->middlename;
        $modify_user->lastname = $request->lastname;
        $modify_user->extensionname = $request->extensionname;
        $modify_user->accesslevel = 1;
        $modify_user->status = 1; //active or not
        $modify_user->email = $request->email;
        $modify_user->save();      
    }
    
    function modifyinstructorinfo($request){
        $idno = $request->idno;

        $add_info = \App\InstructorsInfo::where('idno', $idno)->first();
        $add_info->employment_status = $request->employment_status;
        $add_info->academic_type = $request->academic_type;
        $add_info->department = $request->department;
        $add_info->birthdate = $request->birthdate;
        $add_info->place_of_birth = $request->place_of_birth;
        $add_info->gender = $request->gender;
        $add_info->civil_status = $request->civil_status;
        $add_info->nationality = $request->nationality;
        $add_info->religion = $request->religion;
        $add_info->street = $request->street;
        $add_info->barangay = $request->barangay;
        $add_info->municipality = $request->municipality;
        $add_info->province = $request->province;
        $add_info->zip = $request->zip;
        $add_info->tel_no = $request->tel_no;
        $add_info->cell_no = $request->cell_no;
        $add_info->degree_status = $request->degree_status;
        $add_info->program_graduated = $request->program_graduated;
        $add_info->save();        
    }

    function reset_password(Request $request){
        if(Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN')){
            
            $user_info = \App\User::where('idno', $request->idno)->first();
            $user_info->password = bcrypt($request->password);
            $user_info->is_first_login = 1;
            $user_info->update();  
            \App\Http\Controllers\Admin\Logs::log("Reset password of instructor $request->idno");
            
            return redirect(url('/registrar_college/instructor/modify_instructor', array($request->idno)));
        }
    }
    
}
