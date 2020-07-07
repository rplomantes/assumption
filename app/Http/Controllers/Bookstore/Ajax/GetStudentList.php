<?php

namespace App\Http\Controllers\Bookstore\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Request;
use PDF;

class GetStudentList extends Controller
{
    //
      public function __construct()
    {
        $this->middleware('auth');
    }
     function index(){
        if(Request::ajax()){
            if(Auth::user()->accesslevel==env("BOOKSTORE")){
            $search = Input::get('search');
            $lists = \App\User::where(function ($query) use ($search){
                        $query->where("lastname","like","%$search%")
                              ->orWhere("firstname","like","%$search%")
                              ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"),"like","%$search%")
                              ->orWhere("idno",$search);
                    })->get();
//            $lists = \App\User::Where("lastname","like","%$search%")
//                    ->orWhere("firstname","like","%$search%")->orWhere("idno",$search)->get();
            return view('bookstore.ajax.getstudentlist',compact('lists'));
        }
    }   
 }

    function view_list() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("BOOKSTORE")) {
                $schoolyear = Input::get('school_year');
                $level = Input::get('level');
                $section = Input::get('section');
                $period = Input::get('period');

                $strand = Input::get("strand");
                if ($level == "Grade 11" || $level == "Grade 12") {
                    if ($section == "All") {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' by users.lastname, users.firstname, users.middlename");
                    } else {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                        . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
                        //list of not yet enrolled
			$students = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                        . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' order by users.lastname, users.firstname, users.middlename");
                    }
                } else {
                    if ($section == "All") {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
                    } else {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                        . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
                        //list of not yet enrolled
                        $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  statuses.idno as idno, "
                                    . " promotions.level as level, promotions.strand as strand, promotions.section as section from users, statuses, promotions where promotions.idno = users.idno and users.idno = statuses.idno "
                                    . " and promotions.level = '$level' and statuses.status <= 3 and promotions.section = '$section' order by lastname, firstname, middlename");
                    }
                }
                return view("bookstore.ajax.view_list", compact("status", "level", "section", 'strand', 'schoolyear', 'period','students'));
            }
        }
    }

    function print_student_list($level, $strand, $section, $schoolyear, $period) {
        if ($level == "Grade 11" || $level == "Grade 12") {
            if ($section == "All") {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                . " and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            } else {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            }
        } else {
            if ($section == "All") {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            } else {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            }
        }$value="w";
        $pdf = PDF::loadView("bookstore.view_list", compact("status", "level", "section", 'strand','schoolyear', 'period','value'));
        $pdf->setPaper(array(0, 0, 612, 936));
        return $pdf->stream();
    }
}
