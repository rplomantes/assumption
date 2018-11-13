<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use PDF;
use DB;
use Excel;

class StudentList extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function search() {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $school_years = \App\CourseOffering::distinct()->get(['school_year']);
            return view('accounting.student_list', compact('school_years'));
        }
    }

    function print_search($school_years, $levels, $periods) {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {

            $school_year = $school_years;
            $level = $levels;
            $period = $periods;
            
            if ($school_year == "all") {
                $school_year = "";
            } else {
                $school_year = "and school_year = '" . $school_year . "'";
            }

            if ($level == "all") {
                $level = "";
            } else {
                $level = "and level = '" . $level . "'";
            }
            
            if ($period == "all") {
                $period = "";
            } else {
                $period = "and period = '" . $period . "'";
            }
            
            $lists = DB::Select("select statuses.id, statuses.idno, statuses.type_of_plan from statuses join users on users.idno = statuses.idno where statuses.status='3' $school_year $level $period order by users.lastname");
            
            $pdf = PDF::loadView('accounting.print_search', compact('lists', 'school_years', 'levels', 'periods'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("student_list_.pdf");
        }
    }

    function print_search_EXCEL($school_years, $levels, $periods){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            $school_year = $school_years;
            $level = $levels;
            $period = $periods;
            
            if ($school_year == "all") {
                $school_year = "";
            } else {
                $school_year = "and school_year = '" . $school_year . "'";
            }

            if ($level == "all") {
                $level = "";
            } else {
                $level = "and level = '" . $level . "'";
            }
            
            if ($period == "all") {
                $period = "";
            } else {
                $period = "and period = '" . $period . "'";
            }
            
            $lists = DB::Select("select statuses.id, statuses.idno, statuses.type_of_plan from statuses join users on users.idno = statuses.idno where statuses.status='3' $school_year $level $period order by users.lastname");
            
            ob_end_clean();
            Excel::create('Student List - '.$levels, 
                function($excel) use ($lists,$school_years,$levels,$periods) { $excel->setTitle($school_years);
                    $excel->sheet($school_years, function ($sheet) use ($lists,$school_years,$levels,$periods) {
                    $sheet->loadView('accounting.print_search_excel', compact('lists', 'school_years', 'levels', 'periods'));
                    });
                })->download('xlsx');
        }  
    }
}
