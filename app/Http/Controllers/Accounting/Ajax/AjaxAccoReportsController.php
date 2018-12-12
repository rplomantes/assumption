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
                $school_year = \App\CtrAcademicSchoolYear::where('academic_type','College')->first();
                $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section, l.balance FROM users u, statuses s, (SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY idno) l WHERE l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.department LIKE '$dep' AND s.status = '".env('ENROLLED')."' AND s.school_year = '".$school_year->school_year."' AND s.period = '".$school_year->period."' ORDER BY u.lastname,s.program_code,s.level,s.section");
                $heads = DB::select("SELECT s.level,sum(l.balance) as 'total' FROM statuses s, (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '".$school_year->school_year."' AND period = '".$school_year->period."' GROUP BY idno) l,(SELECT DISTINCT level,sort_by FROM ctr_academic_programs) ctr WHERE l.balance != 0.00 and s.idno = l.idno and s.department LIKE '$dep' and ctr.level = s.level GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
            } 
            else {
                $dep = $department;
                if($dep = 'Senior High School'){
                    $school_year = \App\CtrAcademicSchoolYear::where('academic_type','SHS')->first();
                    $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section, l.balance FROM users u, statuses s, (SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY idno) l WHERE l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.department LIKE '$dep' AND s.status = '".env('ENROLLED')."' AND s.school_year = '".$school_year->school_year."' AND s.period = '".$school_year->period."' ORDER BY u.lastname,s.program_code,s.level,s.section");
                    $heads = DB::select("SELECT s.level,sum(l.balance) as 'total' FROM statuses s, (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '".$school_year->school_year."' AND period = '".$school_year->period."' GROUP BY idno) l,(SELECT DISTINCT level,sort_by FROM ctr_academic_programs) ctr WHERE l.balance != 0.00 and s.idno = l.idno and s.department LIKE '$dep' and ctr.level = s.level GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
                }
                else{
                    $school_year = \App\CtrAcademicSchoolYear::where('academic_type','SHS')->first();
                    $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section, l.balance FROM users u, statuses s, (SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY idno) l WHERE l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.department LIKE '$dep' AND s.status = '".env('ENROLLED')."' AND s.school_year = '".$school_year->school_year."' ORDER BY u.lastname,s.program_code,s.level,s.section");
                    $heads = DB::select("SELECT s.level,sum(l.balance) as 'total' FROM statuses s, (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '".$school_year->school_year."' GROUP BY idno) l,(SELECT DISTINCT level,sort_by FROM ctr_academic_programs) ctr WHERE l.balance != 0.00 and s.idno = l.idno and s.department LIKE '$dep' and ctr.level = s.level GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
                }
            }
            return view('accounting.ajax.getoutstanding_balance', compact('department', 'lists','heads','school_year'));
        }
    }
    
    function get_student_per_account(){
        if(Request::ajax()){
            $account = Input::get('account');
            $school_year = Input::get('school_year');
            $department = Input::get('department');
            $period = Input::get('period');
            
            if ($department == "College Department") {
                $dep = '%Department';
                $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.academic_type FROM users u, statuses s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' and school_year = '$school_year' and period = '$period' GROUP BY accounting_code,idno) and s.department LIKE '$dep' ORDER BY u.lastname,s.program_code,s.level,s.section");
            } else {
                $dep = $department;
                $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.academic_type FROM users u, statuses s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' and school_year = '$school_year' GROUP BY accounting_code,idno) and s.department LIKE '$dep' ORDER BY u.lastname,s.program_code,s.level,s.section");
            }
            $info = \App\ChartOfAccount::where('accounting_code',$account)->first();
            
//            $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.academic_type FROM users u, statuses s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' GROUP BY accounting_code,idno) ORDER BY s.program_code,s.level,s.section");
            return view('accounting.ajax.get_student_per_account', compact('info', 'lists','account','department','school_year','period'));
        
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
