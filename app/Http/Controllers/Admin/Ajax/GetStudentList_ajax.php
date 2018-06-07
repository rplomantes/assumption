<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Request;

class GetStudentList_ajax extends Controller
{
    //
    
    function getstudentlist(){
        if(Request::ajax()){
            $search = Input::get('search');
            $lists = \App\User::
                    where(function ($query) use ($search){
                        $query->where("lastname","like","%$search%")
                              ->orWhere("firstname","like","%$search%")
                              ->orWhere("idno",$search);
                    })->orderBy('accesslevel', 'asc')->get();
            return view('admin.ajax.getstudentlist',compact('lists'));
        }
    }
}
