<?php

namespace App\Http\Controllers\RegistrarCollege\Graduates;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class GraduatesController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function tagging($school_year) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $graduates = \App\StudentInfo::distinct()->join('users', 'users.idno','=','student_infos.idno')->join('college_levels', 'college_levels.idno', '=', 'student_infos.idno')->where('college_levels.level','4th Year')->where('college_levels.school_year',$school_year)->orderBy('student_infos.program_code','asc')->orderBy('users.lastname','asc')->get(array('users.lastname','users.firstname','users.middlename','student_infos.program_code','student_infos.idno', 'student_infos.date_of_grad'));
            return view('reg_college.graduates.view_tagging', compact('school_year','graduates'));
        }
    }

    function save_tagging(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            foreach($request->post as $idno){
                $student_info = \App\StudentInfo::where('idno', $idno)->first();
                $student_info->date_of_grad = $request->date_of_grad;
                $student_info->save();
            }
            return redirect("/registrar_college/graduates/tagging/$request->school_year");
        }
    }
}
