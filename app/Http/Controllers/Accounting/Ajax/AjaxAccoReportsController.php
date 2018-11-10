<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class AjaxAccoReportsController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    function getoustanding_balance() {
        if (Request::ajax()) {
            $dep = "";
            $department = Input::get('department');

            if ($department == "College Department") {
                $dep = '%Department';
            } else {
                $dep = $department;
            }
            $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section, l.balance FROM users u, statuses s, (SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY school_year,idno) l WHERE l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.department LIKE '$dep' ORDER BY s.program_code,s.level,s.section");
            return view('accounting.ajax.getoutstanding_balance', compact('department', 'lists'));
        }
    }
    
    function get_student_per_account(){
        if(Request::ajax()){
            $account = Input::get('account');
            
            $info = \App\ChartOfAccount::where('accounting_code',$account)->first();
            $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.academic_type FROM users u, statuses s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' GROUP BY accounting_code,idno) ORDER BY s.program_code,s.level,s.section");
            return view('accounting.ajax.get_student_per_account', compact('info', 'lists','account'));
        
        }
    }
    
    function get_sibling_discount_list(){
        if(Request::ajax()){
            $dep = "";
            $department = Input::get('department');
            
            if($department == "College Department"){
                $dep = '%Department';
            }
            else{
                $dep = $department;
            }
            
            $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section FROM users u, statuses s WHERE u.idno = s.idno and s.department = '$dep' and u.idno IN (SELECT idno FROM debit_memos WHERE explanation LIKE '%Sibling%') ORDER BY s.program_code,s.level,s.section");
            return view('accounting.ajax.get_sibling_discount', compact('department', 'lists'));
        
        }
    }

}
