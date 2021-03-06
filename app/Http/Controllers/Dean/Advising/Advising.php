<?php

namespace App\Http\Controllers\Dean\Advising;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class Advising extends Controller {

    //

    public function __construct() {
        $this->middleware('auth');
    }

    function advising($idno) {
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $advising_status = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first()->is_available;
            $enrollment_school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
            if ($advising_status == 1) {
                $status_is_new = \App\Status::where('idno', $idno)->first()->is_new;
                $status_stat = \App\Status::where('idno', $idno)->first()->status;
                if ($status_is_new == 1 && ($status_stat == 0 || $status_stat == 1)) {
                    $status = \App\Status::where('idno', $idno)->first();
                    $student_info = \App\StudentInfo::where('idno', $idno)->first();
                    if ($status->is_advised == 0) {
                        return view('dean.advising.advise_new_reg', compact('status', 'idno'));
                    } else if ($status->is_advised == 1) {

                        if ($status->status == env('ASSESSED')) {
                            return view('dean.advising.already_assessed', compact('idno'));
//                            return redirect(url('dean', array('advising', 'confirm_advised', $idno, $status->program_code, $status->level, $student_info->curriculum_year, $status->period)));
                        } else if ($status->status == env('ASSESSED')) {
                            return view('dean.advising.already_assessed', compact('idno'));
                        } else if ($status->status == env('ENROLLED') && $status->school_year == "$enrollment_school_year->school_year" && $status->period == "$enrollment_school_year->period") {
                            return view('dean.advising.enrolled', compact('status', 'idno'));
                        }else{
                            return view('dean.advising.already_assessed', compact('idno'));
                        }
                    }
                } else {
                    $status = \App\Status::where('idno', $idno)->first();
                    $student_info = \App\StudentInfo::where('idno', $idno)->first();
                    if ($status->is_advised == 0) {
                        return view('dean.advising.advise', compact('status', 'idno'));
                    } else if ($status->is_advised == 1) {

                        if ($status->status <= env('ADVISING') ) {
                            return view('dean.advising.confirmed_advised', compact('idno'));
                        } else if ($status->status == env('ASSESSED')) {
                            return view('dean.advising.already_assessed', compact('idno'));
                        } else if ($status->status == env('ENROLLED') && $status->school_year == "$enrollment_school_year->school_year" && $status->period == "$enrollment_school_year->period") {
                            return view('dean.advising.enrolled', compact('status', 'idno'));
                        } else {
                            return view('dean.advising.already_advised', compact('idno'));
                        }
                    }
                }
            } else {
                return view('dean.advising.not_yet_open', compact('status', 'idno'));
            }
        }
    }

    function checkstatus($idno) {
        $checkstatus = \App\Status::where('idno', $idno)->first();
        if (count($checkstatus) == 0) {
            $addstatus = new \App\Status;
            $addstatus->idno = $idno;
            $addstatus->is_new = 1;
            $addstatus->academic_type = "College";
            $addstatus->school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first()->school_year;
            $addstatus->period = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first()->period;
            $addstatus->save();
        }
    }

    function checkstatus_level($idno, $status) {
        $checkstatus_level = \App\CollegeLevel::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->first();
        if (count($checkstatus_level) === 0) {
            $addstatus = new \App\CollegeLevel;
            $addstatus->idno = $idno;
            $addstatus->school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first()->school_year;
            $addstatus->period = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first()->period;
            $addstatus->save();
        }
    }

    function confirm_advised($idno, $program_code, $level, $curriculum_year, $period) {
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE')) {

            $this->checkstatus($idno);
            $enrollment_school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();

            $status = \App\Status::where('idno', $idno)->first();

            if ($status->is_advised == 1 && $status->advising_school_year == "$enrollment_school_year->school_year" && $status->advising_period == "$enrollment_school_year->period") {
                $error = "Student already assessed.";
                return view('dean.advising.error', compact('error'));
            } else if ($status->is_advised == 1 && $status->advising_school_year == "$enrollment_school_year->school_year" && $status->advising_period == "$enrollment_school_year->period") {
                $error = "Student already enrolled.";
                return view('dean.advising.error', compact('error'));
            } else {

                $program_name = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->program_name;

                $updatestatus = \App\Status::where('idno', $idno)->first();
//                $updatestatus->status = env('ADVISING');
                $updatestatus->date_advised = date('Y-m-d');
                $updatestatus->academic_type = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->academic_type;
                $updatestatus->academic_code = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->academic_code;
                $updatestatus->program_code = "$program_code";
                $updatestatus->program_name = "$program_name";
                $updatestatus->level = "$level";
                $updatestatus->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                $updatestatus->advising_school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first()->school_year;
                $updatestatus->advising_period = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first()->period;
                $updatestatus->advised_by = Auth::user()->idno;
                $updatestatus->is_advised = 1;
                $updatestatus->save();

                $sy = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first()->school_year;
                $pr = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first()->period;

                $updateadmission_hed = \App\AdmissionHed::where('idno', $idno)->first();
                $updateadmission_hed->program_code = "$program_code";
                $updateadmission_hed->program_name = "$program_name";
                $updateadmission_hed->save();

//                $updatestatus_level = \App\CollegeLevel::where('idno', $idno)->first();
//                $updatestatus_level->status = 1;
//                $updatestatus_level->date_advised = date('Y-m-d');
//                $updatestatus_level->academic_type = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->academic_type;
//                $updatestatus_level->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
//                $updatestatus_level->program_code = "$program_code";
//                $updatestatus_level->program_name = "$program_name";
//                $updatestatus_level->level = "$level";
//                $updatestatus_level->school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
//                $updatestatus_level->period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
//                $updatestatus_level->save();

                $updatestudentinfo = \App\StudentInfo::where('idno', $idno)->first();
                $updatestudentinfo->curriculum_year = $curriculum_year;
                $updatestudentinfo->program_code = "$program_code";
                $updatestudentinfo->program_name = "$program_name";
                $updatestudentinfo->save();


                \App\Http\Controllers\Admin\Logs::log("Confirm adivsing of student $idno for S.Y. $sy, $pr.");

                return view('dean.advising.confirmed_advised', compact('idno'));
            }
        }
    }

    function print_advising_slip($idno) {
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE')) {

            $pdf = PDF::loadView('dean.advising.advising_slip', compact('idno'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("advising_slip_" . $idno . ".pdf");
        }
    }

    function view_grades($idno) {
        if (Auth::user()->accesslevel == env('DEAN')) {
            $student_info = \App\StudentInfo::where('idno', $idno)->first();

            $curricula = \App\Curriculum::where('curriculum_year', $student_info->curriculum_year)->where('program_code', $student_info->program_code)->get();

            return view('dean.advising.view_grades', compact('idno', 'student_info', 'curricula'));
        }
    }
    
    function update_advising_remarks(Request $request){
        if (Auth::user()->accesslevel == env('DEAN')) {
            $check_remarks = \App\AdvisingRemarks::where('idno',$request->idno)->where('school_year',$request->school_year)->where('period',$request->period)->first();
            if(count($check_remarks)>0){
                $check_remarks->remarks=$request->remarks;
                $check_remarks->save();
            }else{
                $new_remarks = new \App\AdvisingRemarks();
                $new_remarks->idno = $request->idno;
                $new_remarks->school_year = $request->school_year;
                $new_remarks->period = $request->period;
                $new_remarks->remarks = $request->remarks;
                $new_remarks->remarks_by = Auth::user()->idno;
                $new_remarks->save();
            }
            return redirect()->back();
        }
    }

}
