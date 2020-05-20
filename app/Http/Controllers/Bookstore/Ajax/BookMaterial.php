<?php

namespace App\Http\Controllers\Bookstore\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Request;

class BookMaterial extends Controller
{
    //
    function change_remarks(){
        $value = Input::get("value");
        $id = Input::get('id');
        $remark = \App\Ledger::where('id',$id)->first();
        if($value != NULL){
            $remark->date_served = null;
        }
        $remark->supply_remarks = $value;
        $remark->update();
    }
}
