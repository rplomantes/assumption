<?php

namespace App\Http\Controllers\Bookstore;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class Order extends Controller
{
    //
    
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function view_order($idno){
        $user=\App\User::where('idno',$idno)->first();
        $status=  \App\Status::where('idno',$idno)->first();
        $material_details="";
        $other_material_details="";
        $level = \App\Status::where('idno',$idno)->first();
        if(count($level)>0){
            $material_details = \App\CtrMaterial::where('level',$level->level)->where('category','Materials')->get();
            $other_material_details = \App\CtrMaterial::where('level',$level->level)->where('category','Other Materials')->get();
        }
        $books = \App\Ledger::where('category_switch','5')->where('idno',$idno)->where('category','Books')->get();
        $materials = \App\Ledger::where('category_switch','5')->where('idno',$idno)->where('category','Materials')->get();
        $other_materials = \App\Ledger::where('category_switch','5')->where('idno',$idno)->where('category','Other Materials')->get();
        $pe_uniforms = \App\Ledger::where('category_switch','5')->where('idno',$idno)->where('category','PE Uniforms/others')->get();
        return view('bookstore.view_order',compact('idno','books','materials','other_materials','pe_uniforms','level','material_details','other_material_details','user','status'));
    }
    
  function print_order($idno){
      
      $user=\App\User::where('idno',$idno)->first();
        $status=  \App\Status::where('idno',$idno)->first();
        $material_details="";
        $other_material_details="";
        $level = \App\Status::where('idno',$idno)->first();
        if(count($level)>0){
            $material_details = \App\CtrMaterial::where('level',$level->level)->where('category','Materials')->get();
            $other_material_details = \App\CtrMaterial::where('level',$level->level)->where('category','Other Materials')->get();
        }
        $books = \App\Ledger::where('category_switch','5')->where('idno',$idno)->where('category','Books')->get();
        $materials = \App\Ledger::where('category_switch','5')->where('idno',$idno)->where('category','Materials')->get();
        $other_materials = \App\Ledger::where('category_switch','5')->where('idno',$idno)->where('category','Other Materials')->get();
        $pe_uniforms = \App\Ledger::where('category_switch','5')->where('idno',$idno)->where('category','PE Uniforms/others')->get();

        $pdf = PDF::loadView('bookstore.print_order', compact('idno','books','materials','other_materials','pe_uniforms','level','material_details','other_material_details','user','status'));
        $pdf->setPaper(array(0,0,540,612));
        return $pdf->stream();
  }
    
}
