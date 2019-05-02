<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Excel;

class StudentRelatedFeesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function view(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            return view('accounting.srf.srf_report');
        }
    }
    
    function print_student_related_feesPDF(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            $dep = "";
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            
            if ($department == "College Department") {
                $dep = '%Department';
                    $levels = array('1st Year', '2nd Year', '3rd Year', '4th Year');
                    $groups = array('General Education','Laboratory','Thesis', 'Business Department', 'Communication Department', 'Performing Department', 'Education Department', 'Psychology Department');
            } 
            else {
                $dep = $department;
                if($dep == 'Senior High School'){
                    $levels = array("Grade 11", 'Grade 12');
                }
            }
            
             \App\Http\Controllers\Admin\Logs::log("Print Student Related Fees Report PDF");
            $pdf = PDF::loadView('accounting.srf.print_student_related_fees', compact('department','school_year','period', 'levels', 'groups'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("Student Related Fees Report.pdf");
        }
        
    }
    
    function print_student_related_feesEXCEL(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $dep = "";
            $date = date("F d, Y");
            
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            
            if ($department == "College Department") {
                $dep = '%Department';
                    $levels = array('1st Year', '2nd Year', '3rd Year', '4th Year');
                    $groups = array('General Education','Laboratory','Thesis', 'Business Department', 'Communication Department', 'Performing Department', 'Education Department', 'Psychology Department');
            } 
            else {
                $dep = $department;
                if($dep == 'Senior High School'){
                    $levels = array("Grade 11", 'Grade 12');
                }
            }
            
             \App\Http\Controllers\Admin\Logs::log("Download Student Related Fees Report Excel");
            
            ob_end_clean();
            Excel::create('Student Related Fees', 
                function($excel) use ($department,$school_year,$period, $levels, $groups) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($department,$school_year,$period, $levels, $groups) {
                    $sheet->loadView('accounting.srf.print_student_related_fees_excel', compact('department','school_year','period', 'levels','groups'));
                    });
                })->download('xlsx');
            
        }
        
    }
}
