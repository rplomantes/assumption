<?php

namespace App\Http\Controllers\Dean\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Request;

class GetStudentList extends Controller
{
    //
    
    function index(){
        if(Request::ajax()){
            $search = Input::get('search');
            $lists = \App\User::Where("lastname","like","%$search%")
                    ->orWhere("firstname","like","%$search%")->orWhere("idno",$search)->get();
            return view('dean.ajax.getstudentlist',compact('lists'));
        }
    }
}
