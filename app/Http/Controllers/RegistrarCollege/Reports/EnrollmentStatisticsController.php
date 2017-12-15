<?php

namespace App\Http\Controllers\RegistrarCollege\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use PDF;
use DB;

class EnrollmentStatisticsController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
            $period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
            $academic_programs = \App\CtrAcademicProgram::where('academic_type', 'College')->get();
            $departments = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['department']);
            
            return view('reg_college.reports.enrollment_statistics', compact('school_year','period','academic_programs','departments'));
        }
    }
}
