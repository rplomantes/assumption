<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Request as Request2;
use Illuminate\Support\Facades\Input;
use DB;
use Session;

class BulkOtherPayment extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        $currentDatas = \App\BulkOtherPayments::where('processed_by', Auth::user()->idno)->get();
        $otherPayments = \App\OtherPayment::all();

        return view('accounting.bulk_other_payment.index', compact('currentDatas', 'otherPayments'));
    }

    function get_list() {
        if (Request2::ajax()) {
            $level = Input::get("level");
            $section = Input::get("section");
            $strand = Input::get("strand");

            $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'BED')->first()->school_year;
            $period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'BED')->first()->period;

            $studentLists = \App\Status::where('level', $level)->where('section', $section)->get();
            if ($level == "Grade 11" || $level == "Grade 12") {
                $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'SHS')->first()->school_year;
                $period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'SHS')->first()->period;

                $studentLists->where('school_year', $school_year)->where('strand', $strand)->where('period', $period);
            } else {
                $studentLists->where('school_year', $school_year);
            }

            return view('accounting.bulk_other_payment.get_list', compact('studentLists'));
        }
    }

    function add_student() {
        if (Request2::ajax()) {
            $idno = Input::get("idno");

            $checkStudents = \App\BulkOtherPayments::where('idno', $idno)->where('processed_by', Auth::user()->idno)->get();
            if (count($checkStudents) == 0) {
                $addStudent = new \App\BulkOtherPayments();
                $addStudent->idno = $idno;
                $addStudent->processed_by = Auth::user()->idno;
                $addStudent->save();
            }

            $currentDatas = \App\BulkOtherPayments::where('processed_by', Auth::user()->idno)->get();

            return view('accounting.bulk_other_payment.student_to_process', compact('currentDatas'));
        }
    }

    function remove_student() {
        if (Request2::ajax()) {
            $idno = Input::get("idno");

            $deleteStudents = \App\BulkOtherPayments::where('idno', $idno)->where('processed_by', Auth::user()->idno)->get();
            if (count($deleteStudents) > 0) {
                foreach ($deleteStudents as $deleteStudent) {
                    $deleteStudent->delete();
                }
            }

            $currentDatas = \App\BulkOtherPayments::where('processed_by', Auth::user()->idno)->get();

            return view('accounting.bulk_other_payment.student_to_process', compact('currentDatas'));
        }
    }

    function process(Request $request) {
        $other_payment = \App\OtherPayment::find($request->id);
        $students = \App\BulkOtherPayments::where('processed_by', Auth::user()->idno)->get();
        $amount = $request->amount;

        DB::beginTransaction();
        foreach ($students as $student) {
            $this->processOtherPayments($student, $other_payment, $amount);
            $student->delete();
            \App\Http\Controllers\Admin\Logs::log("Post Bulk Other Payment. Php $amount. Other Payment ID: $request->id. IDNO: $student->idno");
        }
        DB::Commit();
        
        Session::flash('message', 'Bulk Other Payment Posted!');
        return redirect(url('/accounting/bulk_other_payment'));
    }

    function processOtherPayments($student, $other_payment, $amount) {
        $addledger = new \App\Ledger;
        $addledger->idno = $student->idno;
        $status = \App\Status::where('idno', $student->idno)->first();
        if (count($status) > 0) {
            if (($status->academic_type == "BED" || $status->academic_type == "SHS") && $status->status > "0") {
                $level = \App\BedLevel::where('idno', $student->idno)
                                ->where('school_year', $status->school_year)
                                ->where('period', $status->period)->first();
            } else if ($status->academic_type == "College" && $status->status > 0) {
                $level = \App\CollegeLevel::where('idno', $student->idno)
                                ->where('school_year', $status->school_year)
                                ->where('period', $status->period)->first();
            } else {
                $level = \App\Status::where('idno', $student->idno)->first();
            }
        }
        if (isset($level)) {
            if (count($level) > 0) {
                if ($status->academic_type == "BED" || $status->academic_type == "SHS") {
                    $addledger->department = $level->department;
                    $addledger->track = $level->track;
                    $addledger->strand = $level->strand;
                    $addledger->level = $level->level;
                    $addledger->school_year = $level->school_year;
                    $addledger->period = $level->period;
                } else if ($status->academic_type == "College") {
                    $addledger->program_code = $level->program_code;
                    $addledger->level = $level->level;
                    $addledger->school_year = $level->school_year;
                    $addledger->period = $level->period;
                }
            }
        } else {
            if ($status->academic_type == "BED" || $status->academic_type == "SHS") {
                $a_school_year = \App\CtrAcademicSchoolYear::where('academic_type', $status->academic_type)->first();
                $addledger->school_year = $a_school_year->school_year;
                $addledger->period = $a_school_year->period;
            } else if ($status->academic_type == "College") {
                $a_school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first();
                $addledger->school_year = $a_school_year->school_year;
                $addledger->period = $a_school_year->period;
            }
        }
        $addledger->category = "Other Miscellaneous";
        $addledger->subsidiary = $other_payment->subsidiary;
        $addledger->receipt_details = $other_payment->subsidiary;
        $addledger->accounting_code = $other_payment->accounting_code;
        $addledger->accounting_name = \App\ChartOfAccount::where('accounting_code', $other_payment->accounting_code)->first()->accounting_name;
        $addledger->category_switch = env("OTHER_MISC");
        $addledger->amount = $amount;
        $addledger->save();
    }

}
