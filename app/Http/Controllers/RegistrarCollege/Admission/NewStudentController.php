<?php

namespace App\Http\Controllers\RegistrarCollege\Admission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class NewStudentController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == '20') {
            $programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['program_code', 'program_name']);
            return view('reg_college.admission.new_student', compact('programs'));
        }
    }

    function add_new_student(Request $request) {
        if (Auth::user()->accesslevel == '20') {
            $this->validate($request, [
                'firstname' => 'required',
                'lastname' => 'required',
                'municipality' => 'required',
                'province' => 'required',
                'birthdate' => 'required',
                'gender' => 'required',
                'email' => 'required',
                'program_to_enroll' => 'required',
            ]);

            return $this->create_new_student($request);
        }
    }

    function create_new_student($request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {

            DB::beginTransaction();
            $reference_no = uniqid();
            $this->adduser($request, $reference_no);
            $this->addstatus($request, $reference_no);
            $this->addstudentinfo($request, $reference_no);
            $this->addregistration($request, $reference_no);
            DB::commit();

            return redirect(url('/'));
        }
    }

    function adduser($request, $reference_no) {

        $firstname = $request->firstname;
        $middlename = $request->middlename;
        $lastname = $request->lastname;
        $extensionname = $request->extensionname;
        $email = $request->email;

        $add_new_user = new \App\User;
        $add_new_user->idno = $reference_no;
        $add_new_user->firstname = $firstname;
        $add_new_user->middlename = $middlename;
        $add_new_user->lastname = $lastname;
        $add_new_user->extensionname = $extensionname;
        $add_new_user->accesslevel = 0;
        $add_new_user->status = 1; //active or not
        $add_new_user->email = $email;
        $add_new_user->save();
    }

    function addstatus($request, $reference_no) {

        $program_to_enroll = $request->program_to_enroll;

        $add_new_status = new \App\Status;
        $add_new_status->idno = $reference_no;
        $add_new_status->date_registered = date('Y-m-d');
        $add_new_status->is_new = 1;
        $add_new_status->status = 0; //registered
        $add_new_status->academic_type = "College";
        $add_new_status->school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
        $add_new_status->period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
        $add_new_status->department = $this->get_department($program_to_enroll);
        $add_new_status->program_code = $program_to_enroll;
        $add_new_status->program_name = $this->get_program_name($program_to_enroll);
        $add_new_status->save();
    }

    function addstudentinfo($request, $reference_no) {

        $street = $request->street;
        $barangay = $request->barangay;
        $municipality = $request->municipality;
        $province = $request->province;
        $zip = $request->zip;
        $birthdate = $request->birthdate;
        $place_of_birth = $request->place_of_birth;
        $gender = $request->gender;
        $civil_status = $request->civil_status;
        $nationality = $request->nationality;
        $religion = $request->religion;
        $tel_no = $request->tel_no;
        $cell_no = $request->cell_no;
        $last_school_attended = $request->last_school_attended;
        $program_to_enroll = $request->program_to_enroll;

        $add_new_student_info = new \App\StudentInfo;
        $add_new_student_info->idno = $reference_no;
        $add_new_student_info->program_code = $program_to_enroll;
        $add_new_student_info->program_name = $this->get_program_name($program_to_enroll);
        $add_new_student_info->birthdate = $birthdate;
        $add_new_student_info->place_of_birth = $place_of_birth;
        $add_new_student_info->gender = $gender;
        $add_new_student_info->street = $street;
        $add_new_student_info->barangay = $barangay;
        $add_new_student_info->municipality = $municipality;
        $add_new_student_info->province = $province;
        $add_new_student_info->zip = $zip;
        $add_new_student_info->tel_no = $tel_no;
        $add_new_student_info->cell_no = $cell_no;
        $add_new_student_info->last_school_attended = $last_school_attended;
        $add_new_student_info->save();
    }

    function addregistration($request, $reference_no) {

        $program_to_enroll = $request->program_to_enroll;

        $add_new_registration = new \App\Registration;
        $add_new_registration->idno = $reference_no;
        $add_new_registration->registration_date = date('Y-m-d');
        $add_new_registration->department = $this->get_department($program_to_enroll);
        $add_new_registration->program_code = $program_to_enroll;
        $add_new_registration->school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
        $add_new_registration->period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
        $add_new_registration->save();
    }

    function get_department($program_to_enroll) {
        $department = \App\CtrAcademicProgram::where('program_code', $program_to_enroll)->first()->department;
        return $department;
    }

    function get_program_name($program_to_enroll) {
        $program_name = \App\CtrAcademicProgram::where('program_code', $program_to_enroll)->first()->program_name;
        return $program_name;
    }

}
