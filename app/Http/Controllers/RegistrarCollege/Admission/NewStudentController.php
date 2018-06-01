<?php

namespace App\Http\Controllers\RegistrarCollege\Admission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Input;
use DB;

class NewStudentController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('ADMISSION_HED')) {
            $programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['program_code', 'program_name']);
            return view('reg_college.admission.new_student', compact('programs'));
        }
    }

    function update_student(){
    return view ('reg_college.admission.new_student');
    }
    
    function add_new_student(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('ADMISSION_HED')) {
            $this->validate($request, [
                'firstname' => 'required',
                'lastname' => 'required',
                'municipality' => 'required',
                'province' => 'required',
                'birthdate' => 'required',
//                'gender' => 'required',
                'email' => 'required',
                'see_professional' => 'required',
                'applying_for' => 'required',
//                'program_to_enroll' => 'required',
            ]);

            return $this->create_new_student($request);
        }
    }
    
       
    function create_new_student($request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('ADMISSION_HED')) {

            DB::beginTransaction();
            $reference_no = uniqid();
            $this->adduser($request, $reference_no);
            $this->addstatus($request, $reference_no);
            $this->addstudentinfo($request, $reference_no);
            $this->addregistration($request, $reference_no);
            $this->admission_hed($request, $reference_no);
            $this->admissionchecklist($request, $reference_no);
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
        $is_foreign = $request->is_foreign;
        $password = $request->password;

        $add_new_user = new \App\User;
        $add_new_user->idno = $reference_no;
        $add_new_user->firstname = $firstname;
        $add_new_user->middlename = $middlename;
        $add_new_user->lastname = $lastname;
        $add_new_user->extensionname = $extensionname;
        $add_new_user->accesslevel = 0;
        $add_new_user->status = 1; //active or not
        $add_new_user->email = $email;
        $add_new_user->password = bcrypt($password);
        $add_new_user->is_foreign = $is_foreign;
        $add_new_user->academic_type = "College";
        $add_new_user->save();
    }

    function addstatus($request, $reference_no) {

        $program_to_enroll = $request->program_to_enroll;

        $add_new_status = new \App\Status;
        $add_new_status->idno = $reference_no;
        $add_new_status->is_new = 1;
        $add_new_status->status = 0; //registered
        $add_new_status->academic_type = "College";
        //$add_new_status->academic_code = $this->get_academic_code($program_to_enroll);
        $add_new_status->school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
        $add_new_status->period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
        //$add_new_status->department = $this->get_department($program_to_enroll);
        //$add_new_status->program_code = $program_to_enroll;
        //$add_new_status->program_name = $this->get_program_name($program_to_enroll);
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
        //$program_to_enroll = $request->program_to_enroll;

        $add_new_student_info = new \App\StudentInfo;
        $add_new_student_info->idno = $reference_no;
        //$add_new_student_info->program_code = $program_to_enroll;
        //$add_new_student_info->program_name = $this->get_program_name($program_to_enroll);
        $add_new_student_info->birthdate = $birthdate;
        $add_new_student_info->place_of_birth = $place_of_birth;
        $add_new_student_info->gender = $gender;
        $add_new_student_info->nationality = $nationality;
        $add_new_student_info->civil_status = $civil_status;
        $add_new_student_info->religion = $religion;
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

        //$program_to_enroll = $request->program_to_enroll;

        $add_new_registration = new \App\Admission;
        $add_new_registration->idno = $reference_no;
        $add_new_registration->registration_date = date('Y-m-d');
        //$add_new_registration->department = $this->get_department($program_to_enroll);
        //$add_new_registration->program_code = $program_to_enroll;
        $add_new_registration->school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
        $add_new_registration->period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
        $add_new_registration->save();
    }
    
        
    function admission_hed($request, $reference_no){
        
        $idno = $request->idno;
        $applying_for = $request->applying_for;
        $strand = $request->strand;
        $program_name = $request->program_name;
        $program_code = \App\CtrAcademicProgram::findCode($request->program_name);
        $assumption_scholar = $request->assumption_scholar;
        $partner_scholar = $request->partner_scholar; 
        $agreement = $request->agreement;
        $summer_classes = $request->summer_classes; 
        $see_professional = $request->see_professional;
        $admission_status = $request->admission_status;
        $tagged_as = $request->tagged_as;
        $student_status = $request->student_status;
        $condition = $request->conditionType;
        if($condition == 'Others'){
            $condition = $request->specifyCondition;
        }
        
        $add_new_student_info = new \App\AdmissionHed;
        $add_new_student_info->idno = $reference_no;
        $add_new_student_info->applying_for = $applying_for;
        $add_new_student_info->strand = $strand;
        $add_new_student_info->program_code = $program_code;
        $add_new_student_info->program_name = $program_name;
        $add_new_student_info->assumption_scholar = $assumption_scholar;
        $add_new_student_info->partner_scholar = $partner_scholar;
        $add_new_student_info->student_status = $student_status;
        $add_new_student_info->agreement = $agreement;
        $add_new_student_info->summer_classes = $summer_classes;     
        $add_new_student_info->see_professional = $see_professional;
        $add_new_student_info->admission_status = $admission_status;
        $add_new_student_info->tagged_as = $tagged_as;
        $add_new_student_info->condition = $condition;
        $add_new_student_info->save();
        
    }
    
    function admissionchecklist($request, $reference_no){
        $idno = $request->idno;
        $birth_certificate = $request->birth_certificate;
        $form138 = $request->form138;
        $labtest = $request->labtest;
        $admission_agreement = $request->admission_agreement;
        $parent_partnership = $request->parent_partnership;
        $tor = $request->tor;
        $honor_dismiss = $request->honor_dismiss;
        $course_desc = $request->course_desc; 
        $cbc = $request->cbc;
        $bt = $request->bt;
        $x_ray = $request->x_ray;
        $visa = $request->visa;
        $passport = $request->passport;
        $photocopy_diploma = $request->photocopy_diploma;
        $marriage_contract = $request->marriage_contract;
        $child_birth_cert = $request->child_birth_cert;  
                
        $add_admission_checklist = new \App\AdmissionHedRequirements;
        $add_admission_checklist->idno = $reference_no;
        $add_admission_checklist->birth_certificate = $birth_certificate;
        $add_admission_checklist->form138 = $form138;
        $add_admission_checklist->labtest = $labtest;
        $add_admission_checklist->admission_agreement = $admission_agreement;
        $add_admission_checklist->parent_partnership = $parent_partnership;
        $add_admission_checklist->tor = $tor;
        $add_admission_checklist->honor_dismiss = $honor_dismiss;
        $add_admission_checklist->course_desc = $course_desc;
        $add_admission_checklist->cbc = $cbc;
        $add_admission_checklist->bt = $bt;
        $add_admission_checklist->x_ray = $x_ray;
        $add_admission_checklist->visa = $visa;
        $add_admission_checklist->passport = $passport;
        $add_admission_checklist->photocopy_diploma = $photocopy_diploma;
        $add_admission_checklist->marriage_contract = $marriage_contract;
        $add_admission_checklist->child_birth_cert = $child_birth_cert;
        $add_admission_checklist->save();
        
    }

    function get_department($program_to_enroll) {
        $department = \App\CtrAcademicProgram::where('program_code', $program_to_enroll)->first()->department;
        return $department;
    }

    function get_program_name($program_to_enroll) {
        $program_name = \App\CtrAcademicProgram::where('program_code', $program_to_enroll)->first()->program_name;
        return $program_name;
    }
    
    function get_academic_code($program_to_enroll) {
        $academic_code = \App\CtrAcademicProgram::where('program_code', $program_to_enroll)->first()->academic_code;
        return $academic_code;
    }

}
