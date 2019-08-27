<?php

namespace App\Http\Controllers\Cashier\Ajax;

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
            $lists = \App\User::where(function ($query) use ($search){
                        $query->where("lastname","like","%$search%")
                              ->orWhere("firstname","like","%$search%")
                              ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"),"like","%$search%")
                              ->orWhere("idno",$search);
                    })->get();
//            $lists = \App\User::Where("lastname","like","%$search%")
//                    ->orWhere("firstname","like","%$search%")->orWhere("idno",$search)->get();
            return view('cashier.ajax.getstudentlist',compact('lists'));
        }
    }
    function getreceipt(){
        if(Request::ajax()){
            $idno = Input::get('idno');
            $receipt_no =  \App\ReferenceId::where('idno',$idno)->first()->receipt_no;
            return $receipt_no;
        }
    }
    function setreceipt(){
        if(Request::ajax()){
            $idno = Input::get('idno');
            $receipt_no =  \App\ReferenceId::where('idno',$idno)->first();
            $receipt_no->receipt_no =  Input::get('new_no');
            $receipt_no->update();
            return true;
        }
    }
}
