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
            $advising_status = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first()->is_available;
            if ($advising_status == 1) {
                $status = \App\Status::where('idno', $idno)->first();
                $student_info = \App\StudentInfo::where('idno', $idno)->first();
                if ($status->status == 0) {
                    return view('dean.advising.advise', compact('status', 'idno'));
                } else if ($status->status == 1) {
                    return redirect(url('dean', array('advising', 'confirm_advised', $idno, $status->program_code, $status->level, $student_info->curriculum_year)));
                } else if ($status->status == 2) {
                    return view('dean.advising.already_assessed', compact('idno'));
                } else if ($status->status == 3) {
                    return view('dean.advising.enrolled', compact('status', 'idno'));
                } else {
                    return view('dean.advising.enrolled', compact('status', 'idno'));
                }
            } else {
                    return view('dean.advising.not_yet_open', compact('status', 'idno'));
            }
        }
    }

    function confirm_advised($idno, $program_code, $level, $curriculum_year) {
        if (Auth::user()->accesslevel == env('DEAN')) {
            $status = \App\Status::where('idno', $idno)->first()->status;
            if ($status == 0 || $status == 1) {

                $program_name = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->program_name;

                $updatestatus = \App\Status::where('idno', $idno)->first();
                $updatestatus->status = 1;
                $updatestatus->date_advised = date('Y-m-d');
                $updatestatus->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                $updatestatus->program_code = "$program_code";
                $updatestatus->program_name = "$program_name";
                $updatestatus->level = "$level";
                $updatestatus->school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first()->school_year;
                $updatestatus->period = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first()->period;
                $updatestatus->save();
                
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
