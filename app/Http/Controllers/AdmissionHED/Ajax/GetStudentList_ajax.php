<?php

namespace App\Http\Controllers\AdmissionHED\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class GetStudentList_ajax extends Controller {

    //
    function getstudentlist() {
        if (Request::ajax()) {
            $search = Input::get("search");
            $lists = \App\User::where('academic_type', 'College')
                            ->where(function ($query) use ($search) {
                                $query->where("lastname", "like", "%$search%")
                                ->orWhere("firstname", "like", "%$search%")
                                ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"), "like", "%$search%")
                                ->orWhere("idno", $search);
                            })->get();
            return view('admission-hed.ajax.getstudentlist', compact('lists'));
        }
    }

    function updateSched() {
        if (Request::ajax()) {
//            if (Auth::user()->accesslevel == env("ADMISSION_HED")) {
            $idno = Input::get('idno');
            $testing_id = Input::get('testing_id');

            if ($testing_id == "Select Schedule") {
                $update = \App\HedTestingStudent::where('idno', $idno)->first();
                $update->schedule_id = NULL;
                $update->save();
            } else {
                $update = \App\HedTestingStudent::where('idno', $idno)->first();
                $update->schedule_id = $testing_id;
                $update->save();
            }
        }
//        }
    }

}
