<?php

namespace App\Http\Controllers\Bookstore\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AjaxBooksPricing extends Controller {

    function getGroupType() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('BOOKSTORE')) {
                $type = Input::get("group_type");
                $title = "";
                $lists = "";
                if ($type <= 4) {
                    if ($type == 1) {
                        $title = "Books/Materials Prices";
                    } elseif ($type == 2) {
                        $title = "Required/Other Required Materials Listing";
                    }
                    return view('bookstore.ajax.display_materials_dropdown', compact('title'));
                } else {
                    if ($type == 5) {
                        $title = "Uniform Sizes and Prices";
                        $fees = \App\CtrUniformSize::all();
                    }
                    return view('bookstore.ajax.display_materials', compact('fees', 'type','title'));
                }
            }
        }
    }

    function getFees() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('BOOKSTORE')) {
                $type = Input::get("group_type");
                $level = Input::get("level");
                if ($type == 1) {
                    $title = "Books/Materials Prices";
                    $fees = \App\CtrOptionalFee::where('level', $level)->get();
                } elseif ($type == 2) {
                    $title = "Required/Other Required Materials Listing";
                    $fees = \App\CtrMaterial::where('level', $level)->get();
                }
                return view('bookstore.ajax.display_materials', compact('fees', 'type','title'));
            }
        }
    }

    function updateFees($id) {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('BOOKSTORE')) {
                $type = Input::get("group_type");
                if ($type == 1) {
                    $data = \App\CtrOptionalFee::where('id', $id)->first();
                }
                if ($type == 2) {
                    $data = \App\CtrMaterial::where('id', $id)->first();
                }
                if ($type == 5) {
                    $data = \App\CtrUniformSize::where('id', $id)->first();
                }
                return view('bookstore.ajax.display_materials_form', compact('data', 'type', 'id'));
            }
        }
    }

    function updateSaveFees() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('BOOKSTORE')) {
                $type = Input::get("type");
                $id = Input::get("record_id");
                $particular = Input::get("particular");
                $subsidiary = Input::get("particular");
                $subsidiary2 = Input::get("subsidiary2");
                $category = Input::get("category");
                $amount = Input::get("amount");
                $size = Input::get("size");
                
                if ($type == 1) {
                    $data = \App\CtrOptionalFee::where('id', $id)->first();
                    $data->category = $category;
                    $data->amount = $amount;
                    if($category == "Books"){
                        $data->subsidiary = $subsidiary2;
                    }else if($category == "Materials"){
                        $data->subsidiary = "Materials";
                    }else if($category == "Other Materials"){
                        $data->subsidiary = "Other Materials";
                    }
                    $data->save();
                }
                if ($type == 2) {
                    $data = \App\CtrMaterial::where('id', $id)->first();
                    $data->category = $category;
                    $data->particular = $subsidiary2;
                    $data->save();
                }
                if ($type == 5) {
                    $data = \App\CtrUniformSize::where('id', $id)->first();
                    $data->particular = $particular;
                    $data->size = $size;
                    $data->amount = $amount;
                    $data->category = "PE Uniforms/others";
                    $data->subsidiary = $subsidiary;
                    $data->save();
                }
            }
        }
    }

    function removeFees($id) {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('BOOKSTORE')) {
                $type = Input::get("group_type");
                if ($type == 1) {
                    $data = \App\CtrOptionalFee::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 2) {
                    $data = \App\CtrMaterial::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 5) {
                    $data = \App\CtrUniformSize::where('id', $id)->first();
                    $data->delete();
                }
                return "Removed successfully";
            }
        }
    }

    function newFees() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('BOOKSTORE')) {
                $type = Input::get("group_type");
                return view('bookstore.ajax.display_materials_form_new', compact('type'));
            }
        }
    }

    function newSaveFees() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('BOOKSTORE')) {
                $type = Input::get("type");
                $id = Input::get("record_id");
                $particular = Input::get("particular");
                $subsidiary = Input::get("particular");
                $subsidiary2 = Input::get("subsidiary2");
                $category = Input::get("category");
                $amount = Input::get("amount");
                $level = Input::get("level");
                $size = Input::get("size");
                if ($type == 1) {
                    $data = new \App\CtrOptionalFee;
                    $data->category = $category;
                    $data->level = $level;
                    $data->amount = $amount;
                    $data->receipt_details = "Bookstore";
                    $data->category_switch = env("OPTIONAL_FEE");
                    $data->accounting_code = env("BOOKSTORE_CODE");
                    $data->default_qty = 1;
                    if($category == "Books"){
                        $data->subsidiary = $subsidiary2;
                    }else if($category == "Materials"){
                        $data->subsidiary = "Materials";
                    }else if($category == "Other Materials"){
                        $data->subsidiary = "Other Materials";
                    }
                    $data->save();
                }
                if ($type == 2) {
                    $data = new \App\CtrMaterial;
                    $data->level = $level;
                    $data->category = $category;
                    $data->particular = $subsidiary2;
                    $data->save();
                }
                if ($type == 5) {
                    $data = new \App\CtrUniformSize;
                    $data->particular = $particular;
                    $data->size = $size;
                    $data->amount = $amount;
                    $data->category = "PE Uniforms/others";
                    $data->receipt_details = "Bookstore";
                    $data->subsidiary = $subsidiary;
                    $data->category_switch = env("OPTIONAL_FEE");
                    $data->accounting_code = env("BOOKSTORE_CODE");
                    $data->save();
                }
            }
        }
    }

    function getSwitch($category) {
        if ($category == "Miscellaneous Fees") {
            return env('MISC_FEE');
        } elseif ($category == "Other Fees") {
            return env('OTHER_FEE');
        } elseif ($category == "Depository Fees") {
            return env('DEPOSITORY_FEE');
        } elseif ($category == "Foreign Fee") {
            return env('SRF_FEE');
        } elseif ($category == "SRF") {
            return env('SRF_FEE');
        } elseif ($category == "Other Miscellaneous") {
            return env('OTHER_MISC');
        }
    }

}
