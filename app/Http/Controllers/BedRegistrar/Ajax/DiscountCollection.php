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
            $discount_type = Input::get("subsidiary");
            $is_sibling = Input::get("discount_amount");
            $level = Input::get("level");
            
            if($discount_type == "Benefit Discount"){
                if($is_sibling == "on"){
                $add_discount_collection = new \App\DiscountCollection();
                $add_discount_collection->idno = $idno;
                $add_discount_collection->subsidiary = "Family Council";
                $add_discount_collection->discount_amount = 150;
                $add_discount_collection->save();
                }
                
                if($level == "Grade 12" || $level == "Grade 11"){
                $add_discount_collection = new \App\DiscountCollection();
                $add_discount_collection->idno = $idno;
                $add_discount_collection->subsidiary = "Student Development Fee";
                $add_discount_collection->discount_amount = 1250;
                $add_discount_collection->save();
                }else{
                $add_discount_collection = new \App\DiscountCollection();
                $add_discount_collection->idno = $idno;
                $add_discount_collection->subsidiary = "Student Development Fee";
                $add_discount_collection->discount_amount = 2500;
                $add_discount_collection->save();
                }
                
            }else if($discount_type == "Sibling Discount"){
                $add_discount_collection = new \App\DiscountCollection();
                $add_discount_collection->idno = $idno;
                $add_discount_collection->subsidiary = "Family Council";
                $add_discount_collection->discount_amount = 150;
                $add_discount_collection->save();
                
                if($level == "Grade 12" || $level == "Grade 11"){
                $add_discount_collection = new \App\DiscountCollection();
                $add_discount_collection->idno = $idno;
                $add_discount_collection->subsidiary = "Student Development Fee";
                $add_discount_collection->discount_amount = 625;
                $add_discount_collection->save();
                }else{
                $add_discount_collection = new \App\DiscountCollection();
                $add_discount_collection->idno = $idno;
                $add_discount_collection->subsidiary = "Student Development Fee";
                $add_discount_collection->discount_amount = 1250;
                $add_discount_collection->save();
                }
            }
        }
    }
    
    function remove_discount_collection(){
        if (Request::ajax()) {
            $id = Input::get("id");
            
            $remove_discount_collection = \App\DiscountCollection::where('id',$id)->delete();
        }
    }
}
