<?php

namespace App\Http\Controllers\RegistrarCollege\StudentRecord;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use DB;

class StudentRecordController extends Controller {

    //
    function view_record($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            return view('reg_college.view_record.view', compact('idno', 'user', 'info', 'status'));
        }
    }

    function view_transcript($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            return view('reg_college.view_record.transcript', compact('idno', 'user', 'info', 'status'));
        }
    }

    function finalize_transcript($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $user = \App\User::where('idno', $idno)->first();
            $level = \App\CollegeLevel::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            return view('reg_college.view_record.finalize_transcript', compact('idno', 'user', 'level', 'info', 'status'));
        }
    }

    function print_transcript(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $idno = $request->idno;
            $user = \App\User::where('idno', $idno)->first();
            $level = \App\CollegeLevel::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $info->date_of_admission = $request->date_of_admission;
            $info->award = $request->award;
            $info->date_of_grad = $request->date_of_grad;
            $info->remarks = $request->remarks;
            $info->tertiary = $request->tertiary;
            $info->save();

            return redirect(url('/registrar_college/view_transcript/print_transcript/' . $request->idno));
        }
    }

    function print_now($idno) {

        $user = \App\User::where('idno', $idno)->first();
        $level = \App\CollegeLevel::where('idno', $idno)->first();
        $info = \App\StudentInfo::where('idno', $idno)->first();

        \App\Http\Controllers\Admin\Logs::log("Print transcript of student: $idno");

        $pdf = PDF::loadView('reg_college.view_record.print_transcript', compact('idno', 'user', 'info', 'level'));
        $pdf->setPaper(array(0, 0, 612, 936));
        return $pdf->stream("transcript_" . $idno . ".pdf");
    }

    function true_copy_of_grades($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();

            \App\Http\Controllers\Admin\Logs::log("Print True copy of grades for student: $idno");

            $pdf = PDF::loadView('reg_college.view_record.print_true_copy_of_grades', compact('idno', 'user', 'info', 'level'));
            //$pdf->setPaper(array(0, 0, 612, 792));
            $pdf->setPaper('letter','portrait');
//            return $request;
            return $pdf->stream("true_copy_of_grades" . $idno . ".pdf");
        }
    }

    function print_curriculum_record($idno) {

        $user = \App\User::where('idno', $idno)->first();
        $status = \App\Status::where('idno', $idno)->first();
        $info = \App\StudentInfo::where('idno', $idno)->first();

        \App\Http\Controllers\Admin\Logs::log("Print curriculum record of student: $idno");

        $pdf = PDF::loadView('reg_college.view_record.print_curriculum_record', compact('idno', 'user', 'info', 'status'));
        $pdf->setPaper(array(0, 0, 612, 936));
//            return $request;
        return $pdf->stream("curriculum_record_" . $idno . ".pdf");
    }

    function edit_college_grades2018($id) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $grade = \App\CollegeGrades2018::where('id', $id)->first();

            return view('reg_college.view_record.edit_college_grades2018', compact('grade', 'id'));
        }
    }

    function edit_now_college_grades2018(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $grade = \App\CollegeGrades2018::where('id', $request->id)->first();
            $grade->school_year = $request->school_year;
            $grade->period = $request->period;
            $grade->course_code = $request->course_code;
            $grade->course_name = $request->course_name;
            $grade->lec = $request->lec;
            $grade->lab = $request->lab;
            $grade->save();
            
            
             \App\Http\Controllers\Admin\Logs::log("Edit grades of student:$grade->idno, ID: $request->id");

            return redirect('/registrar_college/view_transcript/' . $grade->idno);
        }
    }

    function delete_now_college_grades2018($id) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $grade = \App\CollegeGrades2018::where('id', $id)->first();
            $grade->deleted_at = date("Y-m-d H:i:s");
            $grade->save();

            \App\Http\Controllers\Admin\Logs::log("Delete Transcript Record of ID $id");
            return redirect('/registrar_college/view_transcript/' . $grade->idno);
        }
    }

    function edit_college_grades($id) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $grade = \App\GradeCollege::where('id', $id)->first();

            return view('reg_college.view_record.edit_college_grades', compact('grade', 'id'));
        }
    }

    function edit_now_college_grades(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $grade = \App\GradeCollege::where('id', $request->id)->first();
            $grade->school_year = $request->school_year;
            $grade->period = $request->period;
            $grade->course_code = $request->course_code;
            $grade->course_name = $request->course_name;
            $grade->lec = $request->lec;
            $grade->lab = $request->lab;
            $grade->save();

             \App\Http\Controllers\Admin\Logs::log("Edit grades of student:$grade->idno, ID: $request->id");
            return redirect('/registrar_college/view_transcript/' . $grade->idno);
        }
    }

    function delete_now_college_grades($id) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $grade = \App\GradeCollege::where('id', $id)->first();
            $grade->deleted_at = date("Y-m-d H:i:s");
            $grade->save();
            \App\Http\Controllers\Admin\Logs::log("Delete Transcript Record of ID $id");

            return redirect('/registrar_college/view_transcript/' . $grade->idno);
        }
    }

    function edit_credit_grades($id) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $grade = \App\CollegeCredit::where('id', $id)->first();

            return view('reg_college.view_record.edit_credit_grades', compact('grade', 'id'));
        }
    }

    function edit_now_credit_grades(Request $request) {
//        return $request;
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $grade = \App\CollegeCredit::where('id', $request->id)->first();
            $grade->school_year = $request->school_year;
            $grade->period = $request->period;
            $grade->course_code = $request->course_code;
            $grade->course_name = $request->course_name;
            $grade->lec = $request->lec;
            $grade->lab = $request->lab;
            $grade->save();

            
            \App\Http\Controllers\Admin\Logs::log("Edit credited grades in transcript of ID $request->id");
            
            return redirect('/registrar_college/view_transcript/' . $grade->idno);
        }
    }

    function add_record($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            $courses = \App\Curriculum::distinct()->get(['course_code', 'course_name']);
            return view('reg_college.view_record.add_record', compact('idno', 'user', 'info', 'status', 'courses'));
        }
    }

    function add_record_now(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            if ($request->school_year == 2017) {
                if ($request->period == "1st Semester") {
                    $add_new = new \App\CollegeGrades2018;
                    $add_new->idno = $request->idno;
                    $add_new->school_year = $request->school_year;
                    $add_new->period = $request->period;
                    $add_new->course_code = $request->course_code;
                    $course_name = \App\Curriculum::where('course_code', $request->course_code)->first()->course_name;
                    $lec = \App\Curriculum::where('course_code', $request->course_code)->first()->lec;
                    $lab = \App\Curriculum::where('course_code', $request->course_code)->first()->lab;
                    $add_new->course_name = $course_name;
                    $add_new->lec = $lec;
                    $add_new->lab = $lab;
                    $add_new->finals = $request->finals;
                    $add_new->save();
                } else if ($request->period == "2nd Semester" || $request->period == "Summer") {
                    $add_new = new \App\GradeCollege;
                    $add_new->idno = $request->idno;
                    $add_new->school_year = $request->school_year;
                    $add_new->period = $request->period;
                    $add_new->level = "";
                    $add_new->srf = "0";
                    $add_new->percent_tuition = "100";
                    $add_new->finals_status = 3;
                    $add_new->is_lock = 3;
                    $add_new->course_code = $request->course_code;
                    $course_name = \App\Curriculum::where('course_code', $request->course_code)->first()->course_name;
                    $lec = \App\Curriculum::where('course_code', $request->course_code)->first()->lec;
                    $lab = \App\Curriculum::where('course_code', $request->course_code)->first()->lab;
                    $add_new->course_name = $course_name;
                    $add_new->lec = $lec;
                    $add_new->lab = $lab;
                    $add_new->finals = $request->finals;
                    $add_new->save();
                }
            } else if ($request->school_year < 2017) {
                $add_new = new \App\CollegeGrades2018;
                $add_new->idno = $request->idno;
                $add_new->school_year = $request->school_year;
                $add_new->period = $request->period;
                $add_new->course_code = $request->course_code;
                $course_name = \App\Curriculum::where('course_code', $request->course_code)->first()->course_name;
                $lec = \App\Curriculum::where('course_code', $request->course_code)->first()->lec;
                $lab = \App\Curriculum::where('course_code', $request->course_code)->first()->lab;
                $add_new->course_name = $course_name;
                $add_new->lec = $lec;
                $add_new->lab = $lab;
                $add_new->finals = $request->finals;
                $add_new->save();
            } else {
                $add_new = new \App\GradeCollege;
                $add_new->idno = $request->idno;
                $add_new->school_year = $request->school_year;
                $add_new->period = $request->period;
                $add_new->course_code = $request->course_code;
                $add_new->level = "";
                $add_new->srf = "0";
                $add_new->percent_tuition = "100";
                $add_new->finals_status = 3;
                $add_new->is_lock = 3;
                $course_name = \App\Curriculum::where('course_code', $request->course_code)->first()->course_name;
                $lec = \App\Curriculum::where('course_code', $request->course_code)->first()->lec;
                $lab = \App\Curriculum::where('course_code', $request->course_code)->first()->lab;
                $add_new->course_name = $course_name;
                $add_new->lec = $lec;
                $add_new->lab = $lab;
                $add_new->finals = $request->finals;
                $add_new->save();
            }

            
            \App\Http\Controllers\Admin\Logs::log("Add a course Transcript Record of student $request->idno with subject $course_name");
            
            return redirect(url('/registrar_college/view_transcript/' . $request->idno));
        }
    }

    function credit_course($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $user = \App\User::where('idno', $idno)->first();
            $student_info = \App\StudentInfo::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            return view('reg_college.view_record.credit_course', compact('idno', 'user', 'student_info', 'status'));
        }
    }

    function add_now_credit_course(Request $request) {
//        return $request;
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $idno = $request->idno;
            $school_year = $request->credit_sy;
            $period = $request->credit_period;
            $school_name = $request->credit_school_name;
            $course_code = $request->credit_course_code;
            $course_name = $request->credit_course_name;
            $unit = $request->credit_unit;
            $finals = $request->credit_finals;
            $completion = $request->credit_completion;
            $credit_code = $request->credit_credit_code;

            $updates = \App\CollegeCredit::where('idno', $idno)->get();

            foreach ($updates as $update) {
                $update->delete();
            }

            for ($i = 0; $i < 50; $i++) {
                if (isset($school_year[$i])) {

                    $add = new \App\CollegeCredit;
                    $add->idno = $idno;
                    $add->school_year = $school_year[$i];
                    $add->period = $period[$i];
                    $add->school_name = $school_name[$i];
                    $add->course_code = $course_code[$i];
                    $add->course_name = $course_name[$i];
                    $add->lec = $unit[$i];
                    $add->finals = $finals[$i];
                    $add->completion = $completion[$i];
                    $add->credit_code = $credit_code[$i];
                    if ($credit_code[$i] == "") {
                        $add->credit_name == "";
                    } else {
                        $credit_name = \App\Curriculum::where('course_code', $credit_code[$i])->first();
                        $add->credit_name = $credit_name->course_name;
                    }
                    $add->save();
                }
            }
            
            
            \App\Http\Controllers\Admin\Logs::log("Add a credited course Transcript Record of student $idno");

            return redirect('/registrar_college/view_transcript/' . $idno);
        }
    }

    function set_as_credit_now_college_grades2018($id) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            DB::beginTransaction();
            $grade = \App\CollegeGrades2018::where('id', $id)->first();
            
            $new_credit = new \App\CollegeCredit;
            $new_credit->idno = $grade->idno;
            $new_credit->course_code = $grade->course_code;
            $new_credit->course_name = $grade->course_name;
            $new_credit->lec = $grade->lec;
            $new_credit->credit_code = $grade->course_code;
            $new_credit->credit_name = $grade->course_name;
            $new_credit->finals = $grade->finals;
            $new_credit->completion = $grade->completion;
            $new_credit->school_year = $grade->school_year;
            $new_credit->period = $grade->period;
            $new_credit->school_name = "NO ASSIGNED SCHOOL YET";
            $new_credit->save();
            
            $grade->deleted_at = date("Y-m-d H:i:s");
            $grade->save();
            DB::Commit();

            \App\Http\Controllers\Admin\Logs::log("Move Transcript Record to Credit Subject $grade->course_code, $grade->course_name, SY: $grade->school_year $grade->period, Grade: $grade->finals");
            return redirect('/registrar_college/credit_course/' . $grade->idno);
        }
    }

    function print_credited_courses($idno) {

        $user = \App\User::where('idno', $idno)->first();
        $status = \App\Status::where('idno', $idno)->first();
        $info = \App\StudentInfo::where('idno', $idno)->first();

        \App\Http\Controllers\Admin\Logs::log("Print credited courses record of student: $idno");

        $pdf = PDF::loadView('reg_college.view_record.print_credited_courses', compact('idno', 'user', 'info', 'status'));
        $pdf->setPaper(array(0, 0, 612, 936));
//            return $request;
        return $pdf->stream("credited_courses_" . $idno . ".pdf");
    }

}
