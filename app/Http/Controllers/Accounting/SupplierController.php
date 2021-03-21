<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function __construct() {
        $this->middleware("auth");
    }
    
    function index(){
        $suppliers = \App\CtrSupplier::get();
        return view("accounting.suppliers.index", compact("suppliers"));
    }
    
    function save(Request $request){
        $request->validate([
            "supplier_name" => "required",
            "address" => "required",
            "due_date" => "required",
            "tin" => "required"
        ]);
        
        $addsupplier = new \App\CtrSupplier;
        $addsupplier->supplier_name = $request->supplier_name;
        $addsupplier->address = $request->address;
        $addsupplier->due_date = $request->due_date;
        $addsupplier->tin = $request->tin;
        $addsupplier->save();
        \App\Http\Controllers\Admin\Logs::log("Add Supplier for $request->supplier_name");
        
        return back()->withSuccess("Supplier Saved!");
    }
    
    function delete($id){
        $deletesupplier = \App\CtrSupplier::find($id);
        $deletesupplier->delete();
        
        return back()->withErrors("Supplier Deleted!");
    }
    
    function update(Request $request){
        $request->validate([
            "supplier_name" => "required",
            "address" => "required",
            "due_date" => "required",
            "tin" => "required"
        ]);
        
        $editsupplier = \App\CtrSupplier::find($request->supplier_id);
        $editsupplier->supplier_name = $request->supplier_name;
        $editsupplier->address = $request->address;
        $editsupplier->due_date = $request->due_date;
        $editsupplier->tin = $request->tin;
        $editsupplier->update();
        
        return back()->withSuccess("Supplier Updated!");
    }
}
