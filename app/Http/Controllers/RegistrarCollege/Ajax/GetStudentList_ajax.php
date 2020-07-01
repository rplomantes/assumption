<?php

namespace App\Http\Controllers\RegistrarCollege\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class GetStudentList_ajax extends Controller
{
    //
    function getstudentlist(){
        if (Request::ajax()) {
            $search = Input::get("search");
            $is_search = Input::get("is_search");
            $lists = \App\User::where('users.academic_type', 'College')
                    ->join('statuses', 'statuses.idno', 'users.idno')
                    ->where('statuses.status', '<=', env('WITHDRAWN'))
                    ->where(function ($query) use ($search){
                        $query->where("users.lastname","like","%$search%")
                              ->orWhere("users.firstname","like","%$search%")
                              ->orWhere("users.idno",$search);
                    })->get();
            return view('reg_college.ajax.getstudentlist', compact('lists', 'is_search'));
        }
    }
}
