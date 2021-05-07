<?php

namespace App\Http\Controllers\BedRegistrar\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;

class GradeSummaryAjax extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function grade_summary_view_list() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("REG_BE")) {
                $school_year = Input::get('school_year');
                $level = Input::get('level');
                $section = Input::get('section');
                $period = Input::get('period');
                $strand = Input::get("strand");
                $quarter = Input::get("quarter");

                $lists = self::getListSubjectHeads($school_year, $level, $section, $period, $strand, $quarter, 'lists');
                $subject_heads = self::getListSubjectHeads($school_year, $level, $section, $period, $strand, $quarter, 'subject_heads');

                return view("reg_be.ajax.grade_summary_view_list", compact('school_year', 'level', 'section', 'period', 'strand', 'lists', 'subject_heads', 'quarter'));
            }
        }
    }

    public static function getListSubjectHeads($school_year, $level, $section, $period, $strand, $quarter, $display) {
        if ($level == "Grade 11" || $level == "Grade 12") {
            $lists = \App\BedLevel::where('school_year', $school_year)->where('period', $period)->where('strand', $strand)->where('level', $level)->where('section', $section)->where('status', env('ENROLLED'))->get();
            $subject_heads = \App\GradeBasicEd::distinct()->where('school_year', $school_year)->where('period', $period)->where('strand', $strand)->where('level', $level)->get(['group_code']);
        } elseif ($level == "Pre-Kinder" || $level == "Kinder") {
            dd('not yet started...');
        } else {
            $lists = \App\BedLevel::where('school_year', $school_year)->where('level', $level)->where('section', $section)->where('status', env('ENROLLED'))->get();
            $subject_heads = \App\GradeBasicEd::distinct()->where('school_year', $school_year)->where('level', $level)->get(['group_code']);
        }

        if (!$lists->isEmpty()) {
            foreach ($lists as $list) {
                $list->firstname = \App\User::where('idno', $list->idno)->first()->firstname;
                $list->lastname = \App\User::where('idno', $list->idno)->first()->lastname;
                $grades = \App\GradeBasicEd::distinct()->where('school_year', $school_year)->where('level', $level)->where('idno', $list->idno)->get();

                if (!$grades->isEmpty()) {
                    $list->grades = $grades;
                } else {
                    $list->grades = null;
                }
            }
        }
        if ($display == "lists") {
            return $lists;
        } elseif ($display == "subject_heads") {
            return $subject_heads;
        }
    }

}
