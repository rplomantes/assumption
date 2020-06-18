<?php

namespace App\Http\Controllers\BedRegistrar\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class DiscountCollection extends Controller {

    //
    function pop_discount_collection() {
        if (Request::ajax()) {
            $idno = Input::get("idno");

            $get_discount_collections = \App\DiscountCollection::where('idno', $idno)->get();

            return view('reg_be.ajax.get_discount_collections', compact('get_discount_collections', 'idno'));
        }
    }

    function add_discount_collection() {
        if (Request::ajax()) {
            $idno = Input::get("idno");
            $discount_type = Input::get("subsidiary");
            $is_sibling = Input::get("discount_amount");
            $tf_discount = Input::get("tf_discount");
            $level = Input::get("level");
            $delete_collection = \App\DiscountCollection::where('idno', $idno)->get();
            if (count($delete_collection) > 0) {
                foreach ($delete_collection as $delete) {
                    $delete->delete();
                }
            }
            
            if ($discount_type == "Benefit Discount") {
                
                $delete_partial_student_discount = \App\PartialStudentDiscount::where('idno', $idno)->get();
                if (count($delete_partial_student_discount) > 0) {
                    foreach ($delete_partial_student_discount as $delete) {
                        $delete->delete();
                    }
                }
                $discount_description = \App\CtrDiscount::where('discount_code',$tf_discount)->first();
                $add_partial_student_discount = new \App\PartialStudentDiscount();
                $add_partial_student_discount->discount_description = $discount_description['discount_description'];
                $add_partial_student_discount->idno = $idno;
                $add_partial_student_discount->discount = $tf_discount;
                $add_partial_student_discount->save();
                
                if ($is_sibling == "on") {
                    $add_discount_collection = new \App\DiscountCollection();
                    $add_discount_collection->idno = $idno;
                    $add_discount_collection->subsidiary = "Family Council";
                    $add_discount_collection->discount_amount = 100;
                    $add_discount_collection->discount_type = $discount_type;
                    $add_discount_collection->save();
                }

                if ($level == "Grade 12" || $level == "Grade 11") {
                    $fee = \App\ShsOtherCollection::where('subsidiary', "Student Development Fee")->first();
                    $add_discount_collection = new \App\DiscountCollection();
                    $add_discount_collection->idno = $idno;
                    $add_discount_collection->subsidiary = "Student Development Fee";
                    $add_discount_collection->discount_amount = $fee->amount;
                    $add_discount_collection->discount_type = $discount_type;
                    $add_discount_collection->save();
                } else {
                    $fee = \App\CtrBedFee::where('subsidiary', "Student Development Fee")->where('level',$level)->first();
                    $add_discount_collection = new \App\DiscountCollection();
                    $add_discount_collection->idno = $idno;
                    $add_discount_collection->subsidiary = "Student Development Fee";
                    $add_discount_collection->discount_amount = $fee->amount;
                    $add_discount_collection->discount_type = $discount_type;
                    $add_discount_collection->save();
                }
            } else if ($discount_type == "Sibling Discount") {
                $add_discount_collection = new \App\DiscountCollection();
                $add_discount_collection->idno = $idno;
                $add_discount_collection->subsidiary = "Family Council";
                $add_discount_collection->discount_amount = 100;
                $add_discount_collection->discount_type = $discount_type;
                $add_discount_collection->save();

                if ($level == "Grade 12" || $level == "Grade 11") {
                    $fee = \App\ShsOtherCollection::where('subsidiary', "Student Development Fee")->first();
                    $add_discount_collection = new \App\DiscountCollection();
                    $add_discount_collection->idno = $idno;
                    $add_discount_collection->subsidiary = "Student Development Fee";
                    $add_discount_collection->discount_amount = $fee->amount / 2;
                    $add_discount_collection->discount_type = $discount_type;
                    $add_discount_collection->save();
                } else {
                    $fee = \App\CtrBedFee::where('subsidiary', "Student Development Fee")->where('level',$level)->first();
                    $add_discount_collection = new \App\DiscountCollection();
                    $add_discount_collection->idno = $idno;
                    $add_discount_collection->subsidiary = "Student Development Fee";
                    $add_discount_collection->discount_amount = $fee->amount / 2;
                    $add_discount_collection->discount_type = $discount_type;
                    $add_discount_collection->save();
                }
            }
        }
    }

    function remove_discount_collection() {
        if (Request::ajax()) {
            $id = Input::get("id");

            $remove_discount_collection = \App\DiscountCollection::where('id', $id)->delete();
        }
    }

}
