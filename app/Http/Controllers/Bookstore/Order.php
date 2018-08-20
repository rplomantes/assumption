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
        $books = \App\Ledger::where('idno',$idno)->where('category','Books')->get();
        $materials = \App\Ledger::where('idno',$idno)->where('category','Materials')->get();
        $other_materials = \App\Ledger::where('idno',$idno)->where('category','Other Materials')->get();
        $pe_uniforms = \App\Ledger::where('idno',$idno)->where('category','PE Uniforms/others')->get();
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
        $books = \App\Ledger::where('idno',$idno)->where('category','Books')->get();
        $materials = \App\Ledger::where('idno',$idno)->where('category','Materials')->get();
        $other_materials = \App\Ledger::where('idno',$idno)->where('category','Other Materials')->get();
        $pe_uniforms = \App\Ledger::where('idno',$idno)->where('category','PE Uniforms/others')->get();

        $pdf = PDF::loadView('bookstore.print_order', compact('idno','books','materials','other_materials','pe_uniforms','level','material_details','other_material_details','user','status'));
        $pdf->setPaper(array(0, 0, 612.00, 792.0));
        return $pdf->stream();
  }
    
    function place_order($idno){
        $user=\App\User::where('idno',$idno)->first();
        $status= \App\Status::where('idno',$idno)->first();

        return view('bookstore.place_order',compact('idno','user','status'));
    }
    
    function place_order_now(Request $request){
        //return $request;
        DB::beginTransaction();
        $this->addOptionalFee($request);
        \App\Http\Controllers\Admin\Logs::log("Place order in bookstore for - $request->idno.");
        DB::Commit();
        
        $pdf = PDF::loadView('bookstore.print_place_order', compact('idno','request'));
        $pdf->setPaper(array(0, 0, 306.00, 396.0));
        return $pdf->stream();
    }
    
    function addOptionalFee($request) {
        if (count($request->qty_books) > 0) {
            $this->processOptional($request->qty_books, $request, 'books');
        }
        if (count($request->qty_materials) > 0) {
            $this->processOptional($request->qty_materials, $request, 'materials');
        }
        if (count($request->qty_other_materials) > 0) {
            $this->processOptional($request->qty_other_materials, $request, 'other_materials');
        }
        if (count($request->qty_pe_uniforms) > 0) {
            $this->processOptional($request->qty_pe_uniforms, $request, 'pe_uniform');
        }
        $this->processUniform($request, $request->tshirt_qty, $request->tshirt_size);
        $this->processUniform($request, $request->jogging_qty, $request->jogging_size);
        $this->processUniform($request, $request->socks_qty, $request->socks_size);
        $this->processUniform($request, $request->dengue_qty, $request->dengue_size);
    }

    function processUniform($request, $qty, $size) {
        $department = \App\CtrAcademicProgram::where('level', $request->level)->first();
        $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type', 'BED')->first();
        if ($size != "") {
            $tshirt = \App\CtrUniformSize::find($size);
            $amount = $qty * $tshirt->amount;
            if ($amount > 0) {
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
               // $addledger->department = $department->department;
                $addledger->level = $request->level;
                $addledger->school_year = $schoolyear->school_year;
                $addledger->category = $tshirt->category;
                $addledger->subsidiary = $tshirt->subsidiary . " [" . $tshirt->size . "]";
                $addledger->receipt_details = $tshirt->subsidiary . " [" . $tshirt->size . "]";
                $addledger->accounting_code = $tshirt->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($tshirt->accounting_code);
                $addledger->category_switch = env("OTHER_MISC");
                $addledger->amount = $amount;
                $addledger->qty = $qty;
                $addledger->save();
            }
        }
    }
    function processOptional($optional, $request, $material) {
        $department = \App\CtrAcademicProgram::where('level', $request->level)->first();
        $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type', 'BED')->first();
        foreach ($optional as $key => $value) {
            if ($value > 0) {
                $item = \App\CtrOptionalFee::find($key);
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
//                $addledger->department = $department->department;
                $addledger->level = $request->level;
                $addledger->school_year = $schoolyear->school_year;
                $addledger->category = $item->category;
                $addledger->subsidiary = $item->subsidiary;
                $addledger->receipt_details = $item->subsidiary;
                $addledger->accounting_code = $item->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($item->accounting_code);
                $addledger->category_switch = env("OTHER_MISC");
                $addledger->amount = $item->amount * $value;
                $addledger->qty = $value;
                $addledger->save();
            }
        }
    }
    function getAccountingName($accounting_code) {
        $accounting_name = \App\ChartOfAccount::where('accounting_code', $accounting_code)->first();
        if (count($accounting_name) > 0) {
            return $accounting_name->accounting_name;
        } else {
            return "Not Found in Chart of Account";
        }
    }
    
}
