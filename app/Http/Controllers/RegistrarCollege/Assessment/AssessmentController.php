<?php

namespace App\Http\Controllers\RegistrarCollege\Assessment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

class AssessmentController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {

            $status = \App\Status::where('idno', $idno)->first();
            if ($status->status == 0) {
                return view('reg_college.assessment.not_advised', compact('status', 'idno'));
            } else if ($status->status == 1) {
                return view('reg_college.assessment.view_assessment', compact('idno'));
            } else if ($status->status == 2) {
                return view('reg_college.assessment.assessed', compact('status', 'idno'));
            } else if ($status->status >= 3) {
                return view('reg_college.assessment.enrolled', compact('status', 'idno'));
            } else {
                return view('reg_college.assessment.enrolled', compact('status', 'idno'));
            }
        }
    }

    function save_assessment($idno) {
//        $updatestatus = \App\Status::where('idno', $idno)->first();
//        $updatestatus->status = 2;
//        $updatestatus->save();

        return redirect("/registrar_college/assessment/$idno");
    }

    function reassess($idno) {
        $updatestatus = \App\Status::where('idno', $idno)->first();
        $updatestatus->status = 1;
        $updatestatus->save();

        return redirect("/registrar_college/assessment/$idno");
    }

    function print_registration_form($idno) {

        $user = \App\User::where('idno', $idno)->first();
        $status = \App\Status::where('idno', $idno)->first();
        $y = \App\CtrAcademicSchoolYear::where('academic_type', $status->academic_type)->first();

        $school_year = \App\CtrAcademicSchoolYear::where('academic_type', $status->academic_type)->first();
        $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
        $ledger_due_dates = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('due_switch', 1)->get();
        $downpayment = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('due_switch', 0)->first();

        $pdf = PDF::loadView('reg_college.assessment.registration_form', compact('grades', 'user', 'status', 'school_year', 'ledger_due_dates', 'downpayment', 'y'));
        $pdf->setPaper(array(0, 0, 612.00, 936.0));
        return $pdf->stream("registration_form_$status->registration_no.pdf");

        //return "Printing of Registration form will be here.";
    }

}
