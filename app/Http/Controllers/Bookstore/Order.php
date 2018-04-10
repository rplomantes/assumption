<?php

namespace App\Http\Controllers\Bookstore;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;


class Order extends Controller
{
    //
    
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function view_order($idno){
        $books = \App\Ledger::where('category_switch','5')->where('idno',$idno)->where('category','Books')->get();
        $materials = \App\Ledger::where('category_switch','5')->where('idno',$idno)->where('category','Materials')->get();
        $other_materials = \App\Ledger::where('category_switch','5')->where('idno',$idno)->where('category','Other Materials')->get();
        $pe_uniforms = \App\Ledger::where('category_switch','5')->where('idno',$idno)->where('category','PE Uniforms/others')->get();
        return view('bookstore.view_order',compact('idno','books','materials','other_materials','pe_uniforms'));
    }
    
}
