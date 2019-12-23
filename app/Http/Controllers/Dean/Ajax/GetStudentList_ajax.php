<?php

namespace App\Http\Controllers\Dean\Ajax;

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
            $lists = \App\User::where('academic_type', 'College')
                    ->where(function ($query) use ($search){
                        $query->where("lastname","like","%$search%")
                              ->orWhere("firstname","like","%$search%")
                              ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"),"like","%$search%")
                              ->orWhere("idno",$search);
                    })->get();
            return view('dean.ajax.getstudentlist',compact('lists'));
        }
    }
    
    function update_status(){
        if(Request::ajax()){
            $idno = Input::get('idno');
            $status = Input::get('status');
            
            $update_status = \App\User::where('idno', $idno)->first();
            $update_status->status = $status;
            $update_status->save();
        }
    }
}
