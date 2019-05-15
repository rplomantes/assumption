<?php

namespace App\Http\Controllers\RegistrarCollege\Graduates\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use PDF;

class AjaxBatchRanking extends Controller {

    //
    function get_students() {
        if (Request::ajax()) {
            $date_of_grad = Input::get("date_of_grad");
            $list = \App\StudentInfo::where('date_of_grad', $date_of_grad)->get();
            
            $lists = collect();
            foreach ($list as $lists2) {
                $lists->push($this->getLists($lists2->idno));
            }
            $lists3 = $lists->sortBy('gpa');
            return view('reg_college.graduates.ajax.get_batch_students', compact('lists3', 'date_of_grad'));
        }
    }
    function get_students2($date_of_grad) {
                   
            $list = \App\StudentInfo::where('date_of_grad', $date_of_grad)->get();
            
            $lists = collect();
            foreach ($list as $lists2) {
                $lists->push($this->getLists($lists2->idno));
            }
            $lists3 = $lists->sortBy('gpa');
            
            $pdf = PDF::loadView('reg_college.graduates.print_batch_students', compact('lists3', 'date_of_grad'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("batch_ranking.pdf");            
        
    }

    function getLists($idno) {
        $user = \App\User::where('idno', $idno)->first();
        $array2 = array();
        $array2['idno'] = $idno;
        $array2['lastname'] = $user->lastname;
        $array2['firstname'] = $user->firstname;
        $array2['middlename'] = $user->middlename;
        $array2['gpa'] = $this->get_gpa($idno);

        return $array2;
    }

    function get_gpa($idno) {
        $gpa = 0;
        $count = 0;
        $college_credits = \App\CollegeCredit::where('idno', $idno)->get();
        if (count($college_credits) > 0) {
            foreach ($college_credits as $grade) {
                if (stripos($grade->course_code, "NSTP") !== FALSE) {
                    $gpa = $gpa;
                    $count = $count;
                } else {
                    if ($grade->finals == "" || $grade->finals == "AUDIT" || $grade->finals == "NA" || $grade->finals == "NG" || $grade->finals == "W" || $grade->finals == "PASSED") {
                        $gpa = $gpa;
                        $count = $count;
                    } else if ($grade->finals == "INC") {
                        if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "PASSED") {
                            $gpa = $gpa;
                            $count = $count;
                        } else {
                            if ($grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
                                $grade->completion = "4.00";
                                $is_x = 1;
                            }
                            $gpa = $gpa + ($grade->completion * ($grade->lec + $grade->lab));
                            $count = $count + $grade->lec + $grade->lab;
                        }
                    } else {
                        if ($grade->finals == "FA" || $grade->finals == "UD" || $grade->finals == "FAILED" || $grade->finals == "4.00") {
                            $grade->finals = "4.00";
                            $is_x = 1;
                        }
                        $gpa = $gpa + ($grade->finals * ($grade->lec + $grade->lab));
                        $count = $count + $grade->lec + $grade->lab;
                    }
                }
            }
        }

        $pinnacle_grades = \App\CollegeGrades2018::where('idno', $idno)->get();
        if (count($pinnacle_grades) > 0) {
            foreach ($pinnacle_grades as $grade) {
                if (stripos($grade->course_code, "NSTP") !== FALSE) {
                    $gpa = $gpa;
                    $count = $count;
                } else {
                    if ($grade->finals == "" || $grade->finals == "AUDIT" || $grade->finals == "NA" || $grade->finals == "NG" || $grade->finals == "W" || $grade->finals == "PASSED") {
                        $gpa = $gpa;
                        $count = $count;
                    } else if ($grade->finals == "INC") {
                        if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "PASSED") {
                            $gpa = $gpa;
                            $count = $count;
                        } else {
                            if ($grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
                                $grade->completion = "4.00";
                                $is_x = 1;
                            }
                            $gpa = $gpa + ($grade->completion * ($grade->lec + $grade->lab));
                            $count = $count + $grade->lec + $grade->lab;
                        }
                    } else {
                        if ($grade->finals == "FA" || $grade->finals == "UD" || $grade->finals == "FAILED" || $grade->finals == "4.00") {
                            $grade->finals = "4.00";
                            $is_x = 1;
                        }
                        $gpa = $gpa + ($grade->finals * ($grade->lec + $grade->lab));
                        $count = $count + $grade->lec + $grade->lab;
                    }
                }
            }
        }

        $gradecolleges = \App\GradeCollege::where('idno', $idno)->get();
        if (count($gradecolleges) > 0) {
            foreach ($gradecolleges as $grade) {
                if (stripos($grade->course_code, "NSTP") !== FALSE) {
                    $gpa = $gpa;
                    $count = $count;
                } else {
                    if ($grade->finals == "" || $grade->finals == "AUDIT" || $grade->finals == "NA" || $grade->finals == "NG" || $grade->finals == "W" || $grade->finals == "PASSED") {
                        $gpa = $gpa;
                        $count = $count;
                    } else if ($grade->finals == "INC") {
                        if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "PASSED") {
                            $gpa = $gpa;
                            $count = $count;
                        } else {
                            if ($grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
                                $grade->completion = "4.00";
                                $is_x = 1;
                            }
                            $gpa = $gpa + ($grade->completion * ($grade->lec + $grade->lab));
                            $count = $count + $grade->lec + $grade->lab;
                        }
                    } else {
                        if ($grade->finals == "FA" || $grade->finals == "UD" || $grade->finals == "FAILED" || $grade->finals == "4.00") {
                            $grade->finals = "4.00";
                            $is_x = 1;
                        }
                        $gpa = $gpa + ($grade->finals * ($grade->lec + $grade->lab));
                        $count = $count + $grade->lec + $grade->lab;
                    }
                }
            }
        }
        $computed_gpa = ($gpa / $count);
        return number_format($computed_gpa, 4);
    }

}
