<?php

namespace App\Http\Controllers\BedRegistrar\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Request;

class BookMaterials extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index($current_level){
        return view('reg_be.ajax.books_material',compact('current_level'));
    }
    
    function peuniforms($current_level){
        return view('reg_be.ajax.peuniforms',  compact('current_level'));
    }
}
