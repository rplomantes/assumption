<?php

namespace App\Http\Controllers\Bookstore\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Request;

class GetStudentList extends Controller
{
    //
      public function __construct()
    {
        $this->middleware('auth');
    }
     function index(){
        if(Request::ajax()){
            if(Auth::user()->accesslevel==env("BOOKSTORE")){
            $search = Input::get('search');
            $lists = \App\User::where(function ($query) use ($search){
                        $query->where("lastname","like","%$search%")
                              ->orWhere("firstname","like","%$search%")
                              ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"),"like","%$search%")
                              ->orWhere("idno",$search);
                    })->get();
//            $lists = \App\User::Where("lastname","like","%$search%")
//                    ->orWhere("firstname","like","%$search%")->orWhere("idno",$search)->get();
            return view('bookstore.ajax.getstudentlist',compact('lists'));
        }
    }   
 }
}
