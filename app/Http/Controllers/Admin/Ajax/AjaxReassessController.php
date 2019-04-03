<?php

namespace App\Http\Controllers\Admin\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class AjaxReassessController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function getreassess_list() {
        if (Request::ajax()) {
            $department = Input::get('department');

            if ($department == "Senior High School") {
                $enrollment_sy = \App\CtrEnrollmentSchoolYear::where('academic_type', 'SHS')->first();
                $list = \App\Status::where('department', $department)->where('statuses.status', env('ASSESSED'))->where('school_year', $enrollment_sy->school_year)->where('school_year', $enrollment_sy->period)->join('users', 'users.idno','=','statuses.idno')->orderBy('users.lastname','asc')->get();
            }else{
                $enrollment_sy = \App\CtrEnrollmentSchoolYear::where('academic_type', 'BED')->first();
                $list = \App\Status::where('department', $department)->where('statuses.status', env('ASSESSED'))->where('school_year', $enrollment_sy->school_year)->join('users', 'users.idno','=','statuses.idno')->orderBy('users.lastname','asc')->get();
            }
            return view('admin.ajax.getreassess_list', compact('list','enrollment_sy', 'department'));
        }
    }

}
