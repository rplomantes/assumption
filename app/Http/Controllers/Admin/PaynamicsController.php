<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaynamicsController extends Controller
{
    //
    function settings(){
        return view('admin.paynamics_settings');
    }
}
