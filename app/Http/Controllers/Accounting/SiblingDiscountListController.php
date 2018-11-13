<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class SiblingDiscountListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function sibling_discount(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            return view('accounting.sibling_discount_list');
        }
    }
    
    function print_sibling_discountPDF(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            $dep = "";
            $department = $request->department;
            
            if($department == "College Department"){
                $dep = '%Department';
            }
            else{
                $dep = $department;
            }
            
            $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section FROM users u, statuses s WHERE u.idno = s.idno and s.department = '$dep' and u.idno IN (SELECT idno FROM debit_memos WHERE explanation LIKE '%Sibling%') ORDER BY s.program_code,s.level,s.section");
            return view('accounting.print_sibling_discount',compact('department','lists'));
//            $pdf = PDF::loadView('accounting.print_sibling_discount',compact('department','lists'));
//            $pdf->setPaper('letter', 'portrait');
//            return $pdf->stream("sibling_discounts_list.pdf");
        }
        
    }
    
    function print_sibling_discountEXCEL(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            $dep = "";
            $department = $request->department;
            
            if($department == "College Department"){
                $dep = '%Department';
            }
            else{
                $dep = $department;
            }
            
            $lists = "";
            
            ob_end_clean();
            Excel::create('Sibling Discount', 
                function($excel) use ($department,$lists) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($department,$lists) {
                    $sheet->loadView('accounting.print_sibling_discount_excel', compact('department','lists'));
                    });
                })->download('xlsx');
            
        }
        
    }
}
