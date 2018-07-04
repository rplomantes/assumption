<?php

namespace App\Http\Controllers\BedRegistrar\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class DiscountCollection extends Controller
{
    //
    function pop_discount_collection(){
        if (Request::ajax()) {
            $idno = Input::get("idno");
            
            $get_discount_collections = \App\DiscountCollection::where('idno', $idno)->get();
            
            return view('reg_be.ajax.get_discount_collections', compact('get_discount_collections', 'idno'));
        }
    }
    
    function add_discount_collection(){
        if (Request::ajax()) {
            $idno = Input::get("idno");
            $subsidiary = Input::get("subsidiary");
            $discount_amount = Input::get("discount_amount");
            
            $add_discount_collection = new \App\DiscountCollection();
            $add_discount_collection->idno = $idno;
            $add_discount_collection->subsidiary = $subsidiary;
            $add_discount_collection->discount_amount = $discount_amount;
            $add_discount_collection->save();
        }
    }
    
    function remove_discount_collection(){
        if (Request::ajax()) {
            $id = Input::get("id");
            
            $remove_discount_collection = \App\DiscountCollection::where('id',$id)->delete();
        }
    }
}
