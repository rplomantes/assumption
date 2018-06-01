<?php

namespace App\Http\Controllers\AdmissionHED\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class GetStudentList_ajax extends Controller
{
    //
    function getstudentlist(){
        if (Request::ajax()) {
            $search = Input::get("search");
            $lists = \App\User::Where("lastname","like","%$search%")
                    ->orWhere("firstname","like","%$search%")->orWhere("idno",$search)->get();
            return view('admission-hed.ajax.getstudentlist', compact('lists'));
        }
    }
    
}
