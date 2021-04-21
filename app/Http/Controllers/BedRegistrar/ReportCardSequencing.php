<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ReportCardSequencing extends Controller {

    //

    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            return view('reg_be.report_card.report_card_sequencing.index');
        }
    }

    function update(Request $request) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $school_year = $request->school_year;
            $period = $request->period;
            $level = $request->level;
            $strand = $request->strand;

            if ($level == "Grade 11" || $level == "Grade 12") {

                for ($i = 0; $i < 50; $i++) {
                    if (isset($request->subject_code[$i])) {
                        $updates = \App\GradeBasicEd::where('subject_code', $request->subject_code[$i])->where('strand', $strand)->where('school_year', $school_year)->where('period', $period)->where('level', $level)->get();
                        if (count($updates) > 0) {
                            foreach ($updates as $update) {
                                $update->report_card_grouping = $request->grouping[$i];
                                $update->sort_to = $request->sort_to[$i];
                                $update->save();
                            }
                        }
                    }
                }
            } else {
                for ($i = 0; $i < 50; $i++) {
                    if (isset($request->subject_code[$i])) {
                        $updates = \App\GradeBasicEd::where('subject_code', $request->subject_code[$i])->where('school_year', $school_year)->where('level', $level)->get();
                        if (count($updates) > 0) {
                            foreach ($updates as $update) {
                                $update->report_card_grouping = $request->grouping[$i];
                                $update->sort_to = $request->sort_to[$i];
                                $update->save();
                            }
                        }
                    }
                }
            }
        }
        return redirect('/bedregistrar/report_card_sequencing');
    }

//    AJAX
    function getSubjects() {
        if (\Illuminate\Support\Facades\Request::ajax()) {
            $level = \Illuminate\Support\Facades\Input::get("level");
            $strand = \Illuminate\Support\Facades\Input::get("strand");
            $school_year = \Illuminate\Support\Facades\Input::get("school_year");
            $period = \Illuminate\Support\Facades\Input::get("period");

            if ($level == "Grade 11" || $level == "Grade 12") {
                $subjects = \App\GradeBasicEd::distinct()->where('level', $level)->where('school_year', $school_year)
                        ->where('strand', $strand)->where('period', $period)->orderBy('report_card_grouping')->orderBy('sort_to')
                        ->get(['subject_code', 'sort_to', 'report_card_grouping']);
            } else {
                $subjects = \App\GradeBasicEd::distinct()->where('level', $level)->where('school_year', $school_year)
                        ->orderBy('report_card_grouping')->orderBy('sort_to')
                        ->get(['subject_code', 'sort_to', 'report_card_grouping']);
            }
            return view('reg_be.report_card.report_card_sequencing.get_subjects', compact('subjects', 'level', 'strand', 'school_year', 'period'));
        }
    }

}
