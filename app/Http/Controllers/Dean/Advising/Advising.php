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
        if (Auth::user()->accesslevel == env('DEAN')) {
//            $advising_status = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first()->is_available;
//            if ($advising_status == 1) {
                $status = \App\Status::where('idno', $idno)->first();
                if (count($status) == 0) {
                    return view('dean.advising.advise_new_reg', compact('status', 'idno'));
                } else {
                    if ($status->status == 0) {
                $status_level = \App\CollegeLevel::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->first();
                $student_info = \App\StudentInfo::where('idno', $idno)->first();
                        return view('dean.advising.advise', compact('status', 'idno'));
                    } else if ($status->status == 1) {
                        return redirect(url('dean', array('advising', 'confirm_advised', $idno, $status_level->program_code, $status_level->level, $student_info->curriculum_year)));
                    } else if ($status->status == 2) {
                        return view('dean.advising.already_assessed', compact('idno'));
                    } else if ($status->status == 3) {
                        return view('dean.advising.enrolled', compact('status', 'idno'));
                    } else {
                        return view('dean.advising.enrolled', compact('status', 'idno'));
                    }
                }
//            } else {
//                return view('dean.advising.not_yet_open', compact('status', 'idno'));
//            }
        }
    }

    function checkstatus($idno) {
        $checkstatus = \App\Status::where('idno', $idno)->first();
        if (count($checkstatus) == 0) {
            $addstatus = new \App\Status;
            $addstatus->idno = $idno;
            $addstatus->academic_type = "College";
            $addstatus->school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
            $addstatus->period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
            $addstatus->save();
        }
    }

    function checkstatus_level($idno, $status) {
        $checkstatus_level = \App\CollegeLevel::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->first();
        if (count($checkstatus_level)=== 0) {
            $addstatus = new \App\CollegeLevel;
            $addstatus->idno = $idno;
            $addstatus->school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
            $addstatus->period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
            $addstatus->save();
        }
    }

    function confirm_advised($idno, $program_code, $level, $curriculum_year) {
        if (Auth::user()->accesslevel == env('DEAN')) {

            $this->checkstatus($idno);
            $status = \App\Status::where('idno', $idno)->first();
            $this->checkstatus_level($idno, $status);

            $status = \App\Status::where('idno', $idno)->first()->status;
            if ($status == 0 || $status == 1) {

                $program_name = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->program_name;

                $updatestatus = \App\Status::where('idno', $idno)->first();
                $updatestatus->status = 1;
                $updatestatus->school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
                $updatestatus->period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
                $updatestatus->save();

                $updatestatus_level = \App\CollegeLevel::where('idno', $idno)->first();
                $updatestatus_level->status = 1;
                $updatestatus_level->date_advised = date('Y-m-d');
                $updatestatus_level->academic_type = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->academic_type;
                $updatestatus_level->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                $updatestatus_level->program_code = "$program_code";
                $updatestatus_level->program_name = "$program_name";
                $updatestatus_level->level = "$level";
                $updatestatus_level->school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
                $updatestatus_level->period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
                $updatestatus_level->save();

                $updatestudentinfo = \App\StudentInfo::where('idno', $idno)->first();
                $updatestudentinfo->curriculum_year = $curriculum_year;
                $updatestudentinfo->save();

                return view('dean.advising.confirmed_advised', compact('idno'));
            } else if ($status == 2) {
                $error = "Student already assessed.";
                return view('dean.advising.error', compact('error'));
            } else {
                $error = "Student already enrolled.";
                return view('dean.advising.error', compact('error'));
            }
        }
    }

    function print_advising_slip($idno) {
        if (Auth::user()->accesslevel == env('DEAN')) {

            $pdf = PDF::loadView('dean.advising.advising_slip', compact('idno'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("advising_slip_" . $idno . ".pdf");
        }
    }

}
