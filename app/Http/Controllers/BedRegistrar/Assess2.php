<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use App\Http\Controllers\Cashier\MainPayment;
use PDF;
use Excel;

class Assess2 extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function assess($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $user = \App\User::where('idno', $idno)->first();
            if ($user->academic_type == "BED") {
                $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'BED')->first()->school_year;
                $status = \App\Status::where('idno', $idno)->first();
                $ledgers = \App\Ledger::where('idno', $idno)->where('category_switch', '<=', env("TUITION_FEE"))->get();
                $level = \App\BedLevel::where('idno', $idno)->where('school_year', $school_year)->first();
                if (count($status) > 0) {
                    if ($status->status < env("ASSESSED")) {
                        return view('reg_be.assess', compact('user', 'status', 'ledgers', 'level'));
                    } else if ($status->status >= env("PRE_REGISTERED")) {
                        return redirect('/');
                    } else {
                        return view('reg_be.assessed_enrolled', compact('idno'));
                    }
                }
            } elseif ($user->academic_type == "SHS") {
                $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'SHS')->first()->school_year;
                $period = \App\CtrAcademicSchoolYear::where('academic_type', 'SHS')->first()->period;
                $status = \App\Status::where('idno', $idno)->first();
                $ledgers = \App\Ledger::where('idno', $idno)->where('category_switch', '<=', env("TUITION_FEE"))->get();
                $level = \App\BedLevel::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->first();
                if (count($status) > 0) {
                    if ($status->status < env("ASSESSED")) {
                        return view('reg_be.assess', compact('user', 'status', 'ledgers', 'level'));
                    } else {
                        return view('reg_be.assessed_enrolled', compact('idno'));
                    }
                }
            }
        }
    }

    public static function post_assess($request) {
//        dd($request);
        $idno = $request->idno;
        self::updateAcademicType($request);
        $user = \App\User::where("idno", $request->idno)->first();
        if ($user->academic_type == "BED" || $user->academic_type == "SHS") {
            $status = \App\Status::where('idno', $request->idno)->first();


            $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type', $user->academic_type)->first();
            DB::beginTransaction();
//                        $this->addGrades($request, $schoolyear->school_year, $schoolyear->period);
            self::addLedger($request, $schoolyear->school_year, $schoolyear->period);
            self::addOtherCollection($request, $schoolyear->school_year, $schoolyear->period);
//                self::addOptionalFee($request);
            self::addSRF($request, $schoolyear->school_year, $schoolyear->period);
            self::addDueDates($request, $schoolyear->school_year, $schoolyear->period);
            self::modifyStatus($request, $schoolyear->school_year, $schoolyear->period);
            self::checkReservations($request, $schoolyear->school_year, $schoolyear->period);

            DB::Commit();
        }
    }

    function addLatePayment($request, $schoolyear, $period) {
        $latefees = \App\CtrBedLatePayment::get();
        $department = \App\CtrAcademicProgram::where('level', $request->level)->first();
        if (count($latefees) > 0) {
            foreach ($latefees as $fee) {
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
                $addledger->department = $department->department;
                $addledger->level = $request->level;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $addledger->strand = $request->strand;
                    $addledger->period = $period;
                }
                $addledger->school_year = $schoolyear;
                $addledger->category = $fee->category;
                $addledger->subsidiary = $fee->subsidiary;
                $addledger->receipt_details = $fee->receipt_details;
                $addledger->accounting_code = $fee->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($fee->accounting_code);
                $addledger->category_switch = $fee->category_switch;
                $addledger->amount = $fee->amount;
                $addledger->save();
            }
        }
    }

    static function updateAcademicType($request) {
        $user = \App\User::where('idno', $request->idno)->first();
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $user->academic_type = "SHS";
            $user->update();
        } else {
            $user->academic_type = "BED";
            $user->update();
        }
    }

    function addGrades($request, $schoolyear, $period) {
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $subjects = \App\BedCurriculum::where('level', $request->level)->where('strand', $request->strand)->where('subject_type', '<', 2)->get();
        } else {
            $subjects = \App\BedCurriculum::where('level', $request->level)->where('subject_type', '<', 2)->get();
        }
        if (count($subjects) > 0) {
            foreach ($subjects as $subject) {
                $addsubject = new \App\GradeBasicEd;
                $addsubject->idno = $request->idno;
                $addsubject->school_year = $schoolyear;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $addsubject->strand = $request->strand;
                    $addsubject->period = $period;
                }
                $addsubject->level = $request->level;
                $addsubject->subject_code = $subject->subject_code;
                $addsubject->subject_name = $subject->subject_name;
                $addsubject->group_name = $subject->group_name;
                $addsubject->units = $subject->units;
                $addsubject->display_subject_code = $subject->display_subject_code;
                $addsubject->weighted = $subject->weighted;
                $addsubject->encoded_by = Auth::user()->idno;
                $addsubject->save();
            }
        }
    }

    function addDiscountList($request, $school_year, $period, $discount) {
        $add_discount = new \App\DiscountList;
        $add_discount->idno = $request->idno;
        $add_discount->level = $request->level;
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $add_discount->strand = $request->strand;
            $add_discount->period = $period;
        }
        $add_discount->school_year = $school_year;
        $add_discount->discount_code = $discount->discount_code;
        $add_discount->discount_description = $discount->discount_description;
        $add_discount->accounting_code = $discount->accounting_code;
        $add_discount->tuition_fee = $discount->tuition_fee;
        $add_discount->other_fee = $discount->other_fee;
        $add_discount->misc_fee = $discount->misc_fee;
        $add_discount->depository_fee = $discount->depository_fee;
        $add_discount->discount_type = $discount->discount_type;
        $add_discount->amount = $discount->amount;
        $add_discount->save();
    }

    static function addLedger($request, $schoolyear, $period) {
        $discount_code = 0;
        $discount_description = "";
        $discount_tuition = 0;
        $discount_other = 0;
        $discount_depository = 0;
        $discount_misc = 0;
        $discount_srf = 0;

        $department = \App\CtrAcademicProgram::where('level', $request->level)->first();
        $fees = \App\CtrBedFee::where('level', $request->level)->get();
        if (count($fees) > 0) {
            foreach ($fees as $fee) {
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
                $addledger->department = $department->department;
                $addledger->level = $request->level;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $addledger->strand = $request->strand;
                    $addledger->period = $period;
                }
                $addledger->school_year = $schoolyear;
                $addledger->category = $fee->category;
                $addledger->subsidiary = $fee->subsidiary;
                $addledger->receipt_details = $fee->receipt_details;
                $addledger->accounting_code = $fee->accounting_code;
                $addledger->accounting_name = self::getAccountingName($fee->accounting_code);
                $addledger->category_switch = $fee->category_switch;

                $amount = $fee->amount;
                $discount_amount = 0;
                switch ($fee->category_switch) {
                    case env("MISC_FEE"):
                        $amount = self::roundOff($fee->amount);
                        $discount_amount = $fee->amount * $discount_misc / 100;
                        break;
                    case env("OTHER_FEE"):
                        $amount = self::roundOff($fee->amount);
                        $discount_amount = $fee->amount * $discount_other / 100;
                        break;
                    case env("DEPOSITORY_FEE"):
                        $amount = self::roundOff($fee->amount);
                        $discount_amount = $fee->amount * $discount_depository / 100;
                        break;
                    case env("TUITION_FEE"):
                        $addpercent = self::addPercentage($request->plan);
                        $amount = self::roundOff(($fee->amount + ($fee->amount * $addpercent / 100)));
                        $discount_amount = $amount * $discount_tuition / 100;
                }

                $addledger->amount = $amount;
                $addledger->discount_code = $discount_code;
                $addledger->discount = $discount_amount;
                $addledger->save();
            }
            $status = \App\Status::where('idno', $request->idno)->first();
            if ($status->is_new == "1") {

                if ($request->level == "Grade 11") {
                    $addfee = \App\CtrNewShsStudentFee::get();
                    if (count($addfee) > 0) {
                        foreach ($addfee as $fee) {
                            $addledger = new \App\Ledger;
                            $addledger->idno = $request->idno;
                            $addledger->department = $department->department;
                            $addledger->level = $request->level;
                            $addledger->strand = $request->strand;
                            $addledger->period = $period;
                            $addledger->school_year = $schoolyear;
                            $addledger->category = $fee->category;
                            $addledger->subsidiary = $fee->subsidiary;
                            $addledger->receipt_details = $fee->receipt_details;
                            $addledger->accounting_code = $fee->accounting_code;
                            $addledger->accounting_name = $this->getAccountingName($fee->accounting_code);
                            $addledger->category_switch = $fee->category_switch;
                            $addledger->amount = $fee->amount;
                            $addledger->save();
                        }
                    }
                } else {
                    $addfee = \App\CtrNewStudentFee::get();
                    if (count($addfee) > 0) {
                        foreach ($addfee as $fee) {
                            $addledger = new \App\Ledger;
                            $addledger->idno = $request->idno;
                            $addledger->department = $department->department;
                            $addledger->level = $request->level;
                            if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                                $addledger->strand = $request->strand;
                                $addledger->period = $period;
                            }
                            $addledger->school_year = $schoolyear;
                            $addledger->category = $fee->category;
                            $addledger->subsidiary = $fee->subsidiary;
                            $addledger->receipt_details = $fee->receipt_details;
                            $addledger->accounting_code = $fee->accounting_code;
                            $addledger->accounting_name = $this->getAccountingName($fee->accounting_code);
                            $addledger->category_switch = $fee->category_switch;
                            $addledger->amount = $fee->amount;
                            $addledger->save();
                        }
                    }
                }
            }
            $is_foreign = \App\User::where('idno', $request->idno)->first();
            if ($is_foreign->is_foreign == '1') {
                $reg_amount = \App\CtrForiegnFee::where('subsidiary', "Registration")->first()->amount;
                $checkforeign = \App\Ledger::where('idno', $request->idno)->where('school_year', $schoolyear)->where('subsidiary', 'Registration')->where('amount', $reg_amount)->get();
                if (!isset($checkforeign) == 0) {
                    $addfee = \App\CtrForiegnFee::get();
                    foreach ($addfee as $fee) {
                        $addledger = new \App\Ledger;
                        $addledger->idno = $request->idno;
                        $addledger->department = $department->department;
                        $addledger->level = $request->level;
                        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                            $addledger->strand = $request->strand;
                            $addledger->period = $period;
                        }
                        $addledger->school_year = $schoolyear;
                        $addledger->category = $fee->category;
                        $addledger->subsidiary = $fee->subsidiary;
                        $addledger->receipt_details = $fee->receipt_details;
                        $addledger->accounting_code = $fee->accounting_code;
                        $addledger->accounting_name = self::getAccountingName($fee->accounting_code);
                        $addledger->category_switch = $fee->category_switch;
                        $addledger->amount = $fee->amount;
                        $addledger->save();
                    }
                }
            }
        }
    }

    function enrollment_statistics($school_year) {
        $kinder = \App\BedLevel::selectRaw("level,section,count(*)as count")
                        ->whereRaw("school_year='$school_year' AND level='Kinder'")->groupBy('level', 'section');

        $statistics = \App\BedLevel::selectRaw("level, section, count(*) as count")
                        ->whereRaw("school_year='$school_year' AND status='3'")->groupBy('level', 'section')
                        ->orderBy('level', 'section')->get();

        $abm = \App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year='$school_year' AND strand = 'ABM' AND status='3'")->groupBy('sort_by', 'strand', 'section', 'strand')
                ->get();

        $humms = \App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year='$school_year' AND strand = 'HUMMS' AND status='3'")->groupBy('sort_by', 'strand', 'section', 'strand')
                ->get();

        $stem = \App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year='$school_year' AND strand = 'STEM' AND status='3'")->groupBy('sort_by', 'strand', 'section', 'strand')
                ->get();

        return view('reg_be.enrollment_statistics', compact('statistics', 'abm', 'humms', 'stem', 'school_year', 'kinder'));
    }

    function enrollment_statistics_excel($school_year) {
        $kinder = \App\BedLevel::selectRaw("level,section,count(*)as count")
                        ->whereRaw("school_year='$school_year' AND level='Kinder'")->groupBy('level', 'section');

        $statistics = \App\BedLevel::selectRaw("level, section, count(*) as count")
                        ->whereRaw("school_year='$school_year' AND status='3'")->groupBy('level', 'section')
                        ->orderBy('level', 'section')->get();

        $abm = \App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year='$school_year' AND strand = 'ABM' AND status='3'")->groupBy('sort_by', 'strand', 'section', 'strand')
                ->get();

        $humms = \App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year='$school_year' AND strand = 'HUMMS' AND status='3'")->groupBy('sort_by', 'strand', 'section', 'strand')
                ->get();

        $stem = \App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year='$school_year' AND strand = 'STEM' AND status='3'")->groupBy('sort_by', 'strand', 'section', 'strand')
                ->get();

        ob_end_clean();
        Excel::create('Enrollment for SY ' . $school_year, function($excel) use ($statistics, $abm, $humms, $stem, $school_year, $kinder) {
            $excel->setTitle("Enrollment" . $school_year);

            $excel->sheet($school_year, function ($sheet) use ($statistics, $abm, $humms, $stem, $school_year, $kinder) {
                $sheet->loadView('reg_be.enrollment_statistics_excel', compact('statistics', 'abm', 'humms', 'stem', 'school_year', 'kinder'));
            });
        })->download('xlsx');
    }

    static function addSRF($request, $schoolyear, $period) {
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $department = \App\CtrAcademicProgram::where('level', $request->level)->first();
            $srf = \App\CtrBedSrf::where('level', $request->level)->where('strand', $request->strand)->first();
            if (count($srf) > 0) {
                $check_grant = \App\BedScholarship::where('idno', $request->idno)->value('srf');
                if ($check_grant > 0) {
                    $disc_srf = $check_grant / 100 * $srf->amount;
                } else {
                    $disc_srf = 0;
                }


                $add = new \App\Ledger;
                $add->idno = $request->idno;
                $add->department = $department->department;
                $add->level = $request->level;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $add->strand = $request->strand;
                    $add->period = $period;
                }
                $add->school_year = $schoolyear;
                $add->category = $srf->category;
                $add->subsidiary = $srf->subsidiary;
                $add->receipt_details = $srf->receipt_details;
                $add->accounting_code = $srf->accounting_code;
                $add->category_switch = $srf->category_switch;
                $add->accounting_name = $this->getAccountingName($srf->accounting_code);
                $add->amount = $srf->amount;
                $add->discount = $disc_srf;
                $add->save();
            }
        }
    }

    static function addOptionalFee($request) {
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
        $this->processUniform($request, $request->colored_qty, $request->colored_size);
    }

    static function addPercentage($plan) {
        $interest = \App\CtrBedPlan::where('plan', $plan)->first()->interest;
        return $interest;
    }

    static function getAccountingName($accounting_code) {
        $accounting_name = \App\ChartOfAccount::where('accounting_code', $accounting_code)->first();
        return $accounting_name->accounting_name;
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
                $addledger->department = $department->department;
                $addledger->level = $request->level;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type', 'SHS')->first();
                    $period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'SHS')->first();
                    $addledger->strand = $request->strand;
                    $addledger->period = $period->period;
                }
                $addledger->school_year = $schoolyear->school_year;
                $addledger->category = $tshirt->category;
                $addledger->subsidiary = $tshirt->subsidiary . " [" . $tshirt->size . "]";
                $addledger->receipt_details = $tshirt->receipt_details;
                $addledger->accounting_code = $tshirt->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($tshirt->accounting_code);
                $addledger->category_switch = $tshirt->category_switch;
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
                $addledger->department = $department->department;
                $addledger->level = $request->level;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type', 'SHS')->first();
                    $period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'SHS')->first();
                    $addledger->strand = $request->strand;
                    $addledger->period = $period->period;
                }
                $addledger->school_year = $schoolyear->school_year;
                $addledger->category = $item->category;
                $addledger->subsidiary = $item->subsidiary;
                $addledger->receipt_details = $item->receipt_details;
                $addledger->accounting_code = $item->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($item->accounting_code);
                $addledger->category_switch = $item->category_switch;
                $addledger->amount = $item->amount * $value;
                $addledger->qty = $value;
                $addledger->save();
            }
        }
    }

    static function addDueDates($request, $schoolyear, $period) {
        $total_decimal = 0;
        if ($request->plan == "Annual") {
            $total = \App\Ledger::selectRaw('idno, sum(amount)-sum(discount) as total')->where('idno', $request->idno)
                            ->where('category_switch', '<=', env("TUITION_FEE"))->groupBy('idno')->first();
            $addduedate = new \App\LedgerDueDate;
            $addduedate->idno = $request->idno;
            $addduedate->school_year = $schoolyear;
            if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                $addduedate->period = $period;
            }
            $addduedate->due_date = Date('Y-m-d');
            $addduedate->due_switch = 0;
            $addduedate->amount = $total->total;
            $addduedate->save();
        } else {
            if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                $duedates = \App\CtrDueDateBed::where('plan', $request->plan)->where('academic_type', 'SHS')->get();
            } else {
                $duedates = \App\CtrDueDateBed::where('plan', $request->plan)->where('academic_type', 'BED')->get();
            }
            $count = count($duedates) + 1;
            $duetuition = \App\Ledger::selectRaw('idno, sum(amount)-sum(discount) as total')->where('idno', $request->idno)
                            ->where('category_switch', env('TUITION_FEE'))->groupBy('idno')->first();
            $dueamount = $duetuition->total / $count;

            $dueothers = \App\Ledger::selectRaw('idno, sum(amount)-sum(discount) as total')->where('idno', $request->idno)
                            ->where('category_switch', '<', env("TUITION_FEE"))->groupBy('idno')->first();
            $addduedate = new \App\LedgerDueDate;
            $addduedate->idno = $request->idno;
            $addduedate->school_year = $schoolyear;
            if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                $addduedate->period = $period;
            }

            $addduedate->due_date = Date('Y-m-d');
            $addduedate->due_switch = 0;
            $addduedate->amount = $dueothers->total;
            $addduedate->save();

            foreach ($duedates as $duedate) {
                $addduedate = new \App\LedgerDueDate;
                $addduedate->idno = $request->idno;
                $addduedate->school_year = $schoolyear;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $addduedate->period = $period;
                }
                $addduedate->due_date = $duedate->due_date;
                $addduedate->due_switch = 1;
                $plan_amount = floor($dueamount);
                $addduedate->amount = $plan_amount;
                $addduedate->save();
                $total_decimal = $total_decimal + ($dueamount - $plan_amount);
            }

            self::update_due_dates($request, $dueamount, $total_decimal, $dueothers->total);
        }
    }

    static function update_due_dates($request, $dueamount, $total_decimal, $dueothers) {
        $update = \App\LedgerDueDate::where('idno', $request->idno)->where('due_switch', 0)->where('due_date', Date('Y-m-d'))->first();
        $update->amount = $update->amount + $dueamount + $total_decimal;
        $update->save();
    }

    static function modifyStatus($request, $schoolyear, $period) {
        $department = \App\CtrAcademicProgram::where('level', $request->level)->first();
        $status = \App\Status::where('idno', $request->idno)->first();
        $status->status = env("ASSESSED");
        $status->level = $request->level;
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $status->strand = $request->strand;
            $status->period = $period;
            $status->academic_type = "SHS";
        } else {
            $status->academic_type = "BED";
        }
        $status->school_year = $schoolyear;
        $status->section = $request->section;
        $status->department = $department->department;
        $status->date_registered = date('Y-m-d');
        $status->type_of_plan = $request->plan;
        $status->update();

        $promotion = \App\Promotion::where('idno', $request->idno)->first();

        self::checkStatus($request);

        switch ($request->level) {
            case "Pre-Kinder":
                $current_level = "Kinder";
                break;
            case "Kinder":
                $current_level = "Grade 1";
                break;
            case "Grade 1":
                $current_level = "Grade 2";
                break;
            case "Grade 2":
                $current_level = "Grade 3";
                break;
            case "Grade 3":
                $current_level = "Grade 4";
                break;
            case "Grade 4":
                $current_level = "Grade 5";
                break;
            case "Grade 5":
                $current_level = "Grade 6";
                break;
            case "Grade 6":
                $current_level = "Grade 7";
                break;
            case "Grade 7":
                $current_level = "Grade 8";
                break;
            case "Grade 8":
                $current_level = "Grade 9";
                break;
            case "Grade 9":
                $current_level = "Grade 10";
                break;
            case "Grade 10":
                $current_level = "Grade 11";
                break;
            case "Grade 11":
                $current_level = "Grade 12";
                break;
            case "Grade 12":
                $current_level = "Grade 12";
                break;
        }
        if ($period == "2nd Semester") {
            switch ($status->level) {
                case "Grade 11":
                    $current_level = "Grade 11";
                    break;
                case "Grade 12":
                    $current_level = "Grade 12";
                    break;
            }
        }
        if (!$promotion) {
            $new = new \App\Promotion();
            $new->idno = $request->idno;
            $new->level = $request->level;
            $new->strand = $request->strand;
            $new->section = $request->section;
            $new->save();
        } else {
            $promotion->level = $current_level;
            $promotion->strand = $request->strand;
            $promotion->section = $request->section;
//            $promotion->save();
        }
    }

    static function checkStatus($request) {
        $status = \App\Status::where('idno', $request->idno)->first();
        if ($status->status == env("ASSESSED")) {
            $idno = self::changeStatus($request->idno);
            self::addLevels($idno);
        }
    }

    static function changeStatus($idno) {
        $change = \App\Status::where('idno', $idno)->first();
        $change->status = env("ENROLLED");
        $change->date_enrolled = date('Y-m-d');
        $change->update();
        $no = $idno;
        if (strlen($idno) > 10) {
            $user = \App\User::where('idno', $idno)->first();
            $no = \App\Http\Controllers\Cashier\MainPayment::getIdno($idno);
            $user->idno = $no;
            $user->save();
        } else {
            $status = \App\Status::where('idno', $idno)->first();
            $status->is_new = 0;
            $status->update();
        }
        return $no;
    }

    static function addLevels($idno) {
        $status = \App\Status::where('idno', $idno)->first();
        if ($status->academic_type == "BED" || $status->academic_type == "SHS") {
            $addbed = new \App\BedLevel;
            $addbed->idno = $status->idno;
            $addbed->levels_reference_id = $status->levels_reference_id;
            $addbed->date_registered = $status->date_registered;
            $addbed->date_enrolled = date('Y-m-d');
            $addbed->department = $status->department;
            $addbed->strand = $status->strand;
            $addbed->track = $status->track;
            $addbed->level = $status->level;
            $addbed->section = $status->section;
            $addbed->status = $status->status;
            if ($status->academic_type == 'SHS') {
                $addbed->period = $status->period;
            }
            $addbed->school_year = $status->school_year;
            $addbed->type_of_plan = $status->type_of_plan;
            $addbed->type_of_account = $status->type_of_account;
            $addbed->type_of_discount = $status->type_of_discount;
            $addbed->esc = $status->esc;
            $addbed->registration_no = $status->registration_no;
            $addbed->remarks = $status->remarks;
            $addbed->is_new = $status->is_new;
            $addbed->save();
        } else {
            $addbed = new \App\CollegeLevel;
            $addbed->idno = $status->idno;
            $addbed->date_advised = $status->date_advised;
            $addbed->date_registered = $status->date_registered;
            $addbed->date_enrolled = date('Y-m-d');
            $addbed->department = $status->department;
            $addbed->level = $status->level;
            $addbed->program_code = $status->program_code;
            $addbed->program_name = $status->program_name;
            $addbed->status = $status->status;
            $addbed->school_year = $status->school_year;
            $addbed->period = $status->period;
            $addbed->type_of_plan = $status->type_of_plan;
            $addbed->type_of_account = $status->type_of_account;
            $addbed->type_of_discount = $status->type_of_discount;
            $addbed->esc = $status->esc;
            $addbed->registration_no = $status->registration_no;
            $addbed->remarks = $status->remarks;
            $addbed->is_new = $status->is_new;
            $addbed->is_audit = $status->is_audit;
            $addbed->advised_by = $status->advised_by;
            $addbed->save();
        }
    }

    function reassess($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $status = \App\Status::where('idno', $idno)->first();
            $user = \App\User::where('idno', $idno)->first();
            $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type', $user->academic_type)->first();
            if ($status->status == env("ASSESSED")) {
                DB::beginTransaction();
                $this->removeLedger($idno, $schoolyear->school_year, $schoolyear->period, $user->academic_type);
                $this->removeLedgerDueDate($idno, $schoolyear->school_year, $schoolyear->period, $user->academic_type);
                $this->removeGrades($idno, $schoolyear->school_year, $schoolyear->period, $user->academic_type);
                $this->returnStatus($idno, $schoolyear->school_year, $schoolyear->period, $user->academic_type);
                $this->remove_discountList($idno, $schoolyear->school_year, $schoolyear->period, $user->academic_type);
                \App\Http\Controllers\Admin\Logs::log("Re-assess $idno for S.Y. $schoolyear->school_year.");
                DB::commit();
            }
        }

        return redirect(url('/bedregistrar', array('assess', $idno)));
    }

    function back_to_assess($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $status = \App\Status::where('idno', $idno)->first();
            $user = \App\User::where('idno', $idno)->first();
            $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type', $user->academic_type)->first();
            if ($status->status == env("ENROLLED")) {
                DB::beginTransaction();
                $this->back_to_assess_status($idno);
                $this->back_to_assess_bed_levels($idno, $schoolyear);
                \App\Http\Controllers\Admin\Logs::log("Back to assess $idno for S.Y. $schoolyear->school_year.");
                DB::commit();
            }
        }

        return redirect(url('/bedregistrar', array('assess', $idno)));
    }

    function back_to_assess_status($idno) {
        $status = \App\Status::where('idno', $idno)->first();
        $status->status = 2;
        $status->save();
    }

    function back_to_assess_bed_levels($idno, $schoolyear) {
        if ($schoolyear->period == "Yearly") {
            $period = NULL;
        } else {
            $period = $schoolyear->period;
        }
        $status = \App\BedLevel::where('idno', $idno)->where('school_year', $schoolyear->school_year)->where('period', $period)->first();
        $status->delete();
    }

    function remove_discountlist($idno, $schoolyear, $period, $academic_type) {
        if ($academic_type == "BED") {
            \App\DiscountList::where('idno', $idno)->where('school_year', $schoolyear)->delete();
        } else {
            \App\DiscountList::where('idno', $idno)->where('school_year', $schoolyear)->where('period', $period)->delete();
        }
    }

    function reassess_reservations($idno, $levels_reference_id) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $status = \App\Status::where('idno', $idno)->first();
            $user = \App\User::where('idno', $idno)->first();
            $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type', $user->academic_type)->first();
            if ($status->status == env("ASSESSED")) {
                DB::beginTransaction();
                $this->reverse_reservations($idno, $levels_reference_id);
                $this->removeDM($idno, $levels_reference_id);
                //$this->remove_bed_levels($idno, $levels_reference_id);
                //$this->update_status($idno, $levels_reference_id);
                $this->removeLedger($idno, $schoolyear->school_year, $schoolyear->period, $user->academic_type);
                $this->removeLedgerDueDate($idno, $schoolyear->school_year, $schoolyear->period, $user->academic_type);
                $this->removeGrades($idno, $schoolyear->school_year, $schoolyear->period, $user->academic_type);
                $this->returnStatus($idno, $schoolyear->school_year, $schoolyear->period, $user->academic_type);
                $this->remove_discountList($idno, $schoolyear->school_year, $schoolyear->period, $user->academic_type);
                \App\Http\Controllers\Admin\Logs::log("Re-assess $idno for S.Y. $schoolyear->school_year and reversed reservations.");
                DB::commit();
            }
        }

        return redirect(url('/bedregistrar', array('assess', $idno)));
    }

    function reverse_reservations($idno, $levels_reference_id) {
        $reverses = \App\Reservation::where('idno', $idno)->where('levels_reference_id', $levels_reference_id)->get();
        foreach ($reverses as $reverse) {
            $old_reservations_amount = \App\Reservation::where('idno', $idno)->where('levels_reference_id', NULL)->where('reference_id', $reverse->reference_id)->first();
            if (count($old_reservations_amount) > 0) {
                $reverse->amount = $reverse->amount + $old_reservations_amount->amount;
            }
            $reverse->levels_reference_id = NULL;
            $reverse->is_consumed = 0;
            $reverse->consume_sy = "";
            $reverse->save();
            if (count($old_reservations_amount) > 0) {
                $old_reservations_amount->delete();
            }
        }
    }

    function removeDM($idno, $levels_reference_id) {
        $removeDMs = \App\DebitMemo::where('idno', $idno)->where('levels_reference_id', $levels_reference_id)->get();
        foreach ($removeDMs as $removeDM) {
            $reference_id = $removeDM->reference_id;
            $removeDM->delete();
            $this->remove_accountings($reference_id);
        }
    }

    function remove_bed_levels($idno, $levels_reference_id) {
        $remove_bed_levels = \App\BedLevel::where('levels_reference_id', $levels_reference_id)->first();
        $remove_bed_levels->delete();
    }

    function remove_accountings($reference_id) {
        $remove_accountings = \App\Accounting::where('reference_id', $reference_id)->get();
        foreach ($remove_accountings as $accounting) {
            $accounting->delete();
        }
    }

    function update_status($idno, $levels_reference_id) {
        $update_status = \App\Status::where('idno', $idno)->where('levels_reference_id', $levels_reference_id)->first();
        $update_status->status = env("ASSESSED");
        $update_status->save();
    }

    function removeLedger($idno, $schoolyear, $period, $academic_type) {
        if ($academic_type == "BED") {
            \App\Ledger::where('idno', $idno)->where('category_switch', '<=', env("TUITION_FEE"))->where('school_year', $schoolyear)->delete();
            \App\Ledger::where('idno', $idno)->where('category_switch', env("OTHER_MISC"))->where('subsidiary', "Late Payment")->where('school_year', $schoolyear)->delete();
        } else {
            \App\Ledger::where('idno', $idno)->where('category_switch', '<=', env("TUITION_FEE"))->where('school_year', $schoolyear)->where('period', $period)->delete();
            \App\Ledger::where('idno', $idno)->where('category_switch', env("OTHER_MISC"))->where('subsidiary', "Late Payment")->where('school_year', $schoolyear)->where('period', $period)->delete();
        }
    }

    function removeLedgerDueDate($idno, $schoolyear, $period, $academic_type) {
        if ($academic_type == "BED") {
            \App\LedgerDueDate::where('idno', $idno)->where('school_year', $schoolyear)->delete();
        } else {
            \App\LedgerDueDate::where('idno', $idno)->where('school_year', $schoolyear)->where('period', $period)->delete();
        }
    }

    function removeGrades($idno, $schoolyear, $period, $academic_type) {
        if ($academic_type == "BED") {
            \App\GradeBasicEd::where('idno', $idno)->where('school_year', $schoolyear)->delete();
        } else {
            \App\GradeBasicEd::where('idno', $idno)->where('school_year', $schoolyear)->where('period', $period)->delete();
        }
    }

    function returnStatus($idno, $schoolyear, $period, $academic_type) {
        $status = \App\Status::where('idno', $idno)->first();
        $user = \App\User::where('idno', $idno)->first();
        $assignlevel = $status->level;
        switch ($status->level) {
            case "Pre-Kinder":
                $assignlevel = "Pre-Kinder";
                $academic_type = "BED";
                break;
            case "Kinder":
                $assignlevel = "Pre-Kinder";
                $academic_type = "BED";
                break;
            case "Grade 1":
                $assignlevel = "Kinder";
                $academic_type = "BED";
                break;
            case "Grade 2":
                $assignlevel = "Grade 1";
                $academic_type = "BED";
                break;
            case "Grade 3":
                $assignlevel = "Grade 2";
                $academic_type = "BED";
                break;
            case "Grade 4":
                $assignlevel = "Grade 3";
                $academic_type = "BED";
                break;
            case "Grade 5":
                $assignlevel = "Grade 4";
                $academic_type = "BED";
                break;
            case "Grade 6":
                $assignlevel = "Grade 5";
                $academic_type = "BED";
                break;
            case "Grade 7":
                $assignlevel = "Grade 6";
                $academic_type = "BED";
                break;
            case "Grade 8":
                $assignlevel = "Grade 7";
                $academic_type = "BED";
                break;
            case "Grade 9":
                $assignlevel = "Grade 8";
                $academic_type = "BED";
                break;
            case "Grade 10":
                $assignlevel = "Grade 9";
                $academic_type = "BED";
                break;
            case "Grade 11":
                $assignlevel = "Grade 10";
                $academic_type = "BED";
                break;
            case "Grade 12":
                $assignlevel = "Grade 11";
                $academic_type = "SHS";
                break;
        }
        if ($status->period == "2nd Semester") {
            switch ($status->level) {
                case "Grade 11":
                    $assignlevel = "Grade 11";
                    $academic_type = "SHS";
                    break;
                case "Grade 12":
                    $assignlevel = "Grade 12";
                    $academic_type = "SHS";
                    break;
            }
        }
        $status->level = $assignlevel;
        $status->status = 0;
        $status->academic_type = $academic_type;
        $status->update();

        $user->academic_type = $academic_type;
        $user->update();

        $promotion = \App\Promotion::where('idno', $idno)->first();


        switch ($status->level) {
            case "Pre-Kinder":
                $current_level = "Kinder";
                break;
            case "Kinder":
                $current_level = "Grade 1";
                break;
            case "Grade 1":
                $current_level = "Grade 2";
                break;
            case "Grade 2":
                $current_level = "Grade 3";
                break;
            case "Grade 3":
                $current_level = "Grade 4";
                break;
            case "Grade 4":
                $current_level = "Grade 5";
                break;
            case "Grade 5":
                $current_level = "Grade 6";
                break;
            case "Grade 6":
                $current_level = "Grade 7";
                break;
            case "Grade 7":
                $current_level = "Grade 8";
                break;
            case "Grade 8":
                $current_level = "Grade 9";
                break;
            case "Grade 9":
                $current_level = "Grade 10";
                break;
            case "Grade 10":
                $current_level = "Grade 11";
                break;
            case "Grade 11":
                $current_level = "Grade 12";
                break;
            case "Grade 12":
                $current_level = "Grade 12";
                break;
        }
        if ($period == "2nd Semester") {
            switch ($status->level) {
                case "Grade 11":
                    $current_level = "Grade 11";
                    break;
                case "Grade 12":
                    $current_level = "Grade 12";
                    break;
            }
        }

        $promotion->level = $current_level;
//        $promotion->save();
    }

    static function checkReservations($request, $school_year, $period) {
        $levels_reference_id = uniqid();
        $checkreservations = \App\Reservation::where('idno', $request->idno)->where('is_consumed', 0)->where('is_reverse', 0)->selectRaw('sum(amount) as amount')->first();
        if ($checkreservations->amount > 0) {
            $totalpayment = $checkreservations->amount;
            $totalamount = 0;
            $firsttotalamount = 0;
            $reference_id = uniqid();
            $ledgers = \App\Ledger::where('idno', $request->idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('category_switch', '<=', env("TUITION_FEE"))->get();

            $request->date = date('Y-m-d');
//            MainPayment::addUnrealizedEntry($request, $reference_id);
            $totalamount = self::processAccounting($request, $reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
            $firsttotalamount = $totalamount;

//            $changestatus = \App\Status::where('idno', $request->idno)->first();
//            $changestatus->status = env("ENROLLED");
//            $changestatus->update();

            $changereservation = \App\Reservation::where('idno', $request->idno)->where('is_consumed', 0)->where('is_reverse', 0)->get();
            if (count($changereservation) > 0) {
                foreach ($changereservation as $change) {
                    if ($change->amount == $totalamount) {
                        $change->levels_reference_id = $levels_reference_id;
                        $change->is_consumed = '1';
                        $change->consume_sy = $school_year;
                        $change->update();
                    } else if ($change->amount >= $totalamount) {
                        $change->levels_reference_id = $levels_reference_id;
                        $change->is_consumed = '1';
                        $change->consume_sy = $school_year;
                        $lessreservation = $change->amount - $totalamount;
                        if ($totalamount > 0) {
                            $change->amount = $totalamount;
                            $change->update();

                            //add remaining reservations
                            $addreservation = new \App\Reservation;
                            $addreservation->idno = $change->idno;
                            $addreservation->reference_id = $change->reference_id;
                            $addreservation->transaction_date = $change->transaction_date;
                            $addreservation->amount = $lessreservation;
                            $addreservation->reservation_type = $change->reservation_type;
                            $addreservation->posted_by = $change->posted_by;
                            $addreservation->save();
                        }
                    } else {
                        $change->levels_reference_id = $levels_reference_id;
                        $change->is_consumed = '1';
                        $change->consume_sy = $school_year;
                        $change->update();
                    }
                    $totalamount = $totalamount - $change->amount;
                }
            }
            self::postDebit($request, $reference_id, $totalpayment, $levels_reference_id, $school_year, $period, $firsttotalamount);
        }
        $change = \App\Status::where('idno', $request->idno)->first();
        $change->levels_reference_id = $levels_reference_id;
        $change->update();
    }

    static function processAccounting($request, $reference_id, $totalpayment, $ledgers, $accounting_type) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $totalamount = 0;
        if (count($ledgers) > 0) {
            foreach ($ledgers as $ledger) {
                if ($totalpayment > 0) {
                    //process if there is discount
                    if ($ledger->debit_memo == 0 && $ledger->payment == 0) {
                        if ($ledger->discount > 0) {

                            MainPayment::processDiscount($request, $reference_id, $ledger->discount, $ledger->discount_code, $accounting_type);
                            $addacct = new \App\Accounting;
                            $addacct->transaction_date = $request->date;
                            $addacct->reference_id = $reference_id;
                            $addacct->accounting_type = $accounting_type;
                            $addacct->category = $ledger->category;
                            $addacct->subsidiary = $ledger->subsidiary;
                            $addacct->receipt_details = $ledger->receipt_details;
                            $addacct->particular = $ledger->receipt_details;
                            $addacct->accounting_code = $ledger->accounting_code;
                            $addacct->accounting_name = $ledger->accounting_name;
                            $addacct->department = $ledger->department;
                            $addacct->fiscal_year = $fiscal_year;
                            $addacct->credit = $ledger->discount;
                            $addacct->posted_by = Auth::user()->idno;
                            $addacct->save();
//                            $totalamount = $totalamount + $ledger->discount;
                        }
                    }
                    if ($totalpayment >= $ledger->amount - $ledger->discount - $ledger->debit_memo - $ledger->payment) {
                        $amount = $ledger->amount - $ledger->discount - $ledger->debit_memo - $ledger->payment;
                        if ($accounting_type == env("DEBIT_MEMO")) {
                            $ledger->debit_memo = $ledger->debit_memo + $amount;
                        } else {
                            $ledger->payment = $ledger->payment + $amount;
                        }
                        $ledger->update();

                        $addacct = new \App\Accounting;
                        $addacct->transaction_date = $request->date;
                        $addacct->reference_id = $reference_id;
                        $addacct->reference_number = $ledger->id;
                        $addacct->accounting_type = $accounting_type;
                        $addacct->category = $ledger->category;
                        $addacct->subsidiary = $ledger->subsidiary;
                        $addacct->receipt_details = $ledger->receipt_details;
                        $addacct->particular = $ledger->receipt_details;
                        $addacct->accounting_code = $ledger->accounting_code;
                        $addacct->accounting_name = $ledger->accounting_name;
                        $addacct->department = $ledger->department;
                        $addacct->fiscal_year = $fiscal_year;
                        $addacct->credit = $amount;
                        $addacct->posted_by = 999999;
                        $addacct->save();
                        $totalamount = $totalamount + $amount;
                        $totalpayment = $totalpayment - $amount;
                    } else {
                        if ($totalpayment > 0) {
                            if ($accounting_type == env("DEBIT_MEMO")) {
                                $ledger->debit_memo = $ledger->debit_memo + $totalpayment;
                            } else {
                                $ledger->payment = $ledger->payment + $totalpayment;
                            }
                            $ledger->update();
                            $addacct = new \App\Accounting;
                            $addacct->transaction_date = $request->date;
                            $addacct->reference_id = $reference_id;
                            $addacct->reference_number = $ledger->id;
                            $addacct->accounting_type = $accounting_type;
                            $addacct->category = $ledger->category;
                            $addacct->subsidiary = $ledger->subsidiary;
                            $addacct->receipt_details = $ledger->receipt_details;
                            $addacct->particular = $ledger->receipt_details;
                            $addacct->accounting_code = $ledger->accounting_code;
                            $addacct->accounting_name = $ledger->accounting_name;
                            $addacct->fiscal_year = $fiscal_year;
                            $addacct->credit = $totalpayment;
                            $addacct->posted_by = 999999;
                            $addacct->save();
                            $totalamount = $totalamount + $totalpayment;
                            $totalpayment = 0;
                        }
                    }
                }
            }
        }
        return $totalamount;
    }

    static function postDebit($request, $reference_id, $totalpayment, $levels_reference_id, $school_year, $period, $totalamount) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $reservations = \App\Reservation::where('idno', $request->idno)->where('is_consumed', 1)->where('is_reverse', 0)->where('levels_reference_id', $levels_reference_id)->get();
        $dept = \App\CtrAcademicProgram::where('level', $request->level)->first();
        $department = $dept->department; //\App\Status::where('idno',$idno)->first()->department;
        $totalReserved = 0;
        if (count($reservations) > 0) {
            foreach ($reservations as $ledger) {
                $addacct = new \App\Accounting;
                $addacct->transaction_date = date('Y-m-d');
                $addacct->reference_id = $reference_id;
                //$addacct->reference_number=$ledger->id;
                $addacct->accounting_type = env("DEBIT_MEMO");
                $addacct->subsidiary = $ledger->idno;
                $department = $dept->department;
                $addacct->department = $department;
                if ($ledger->reservation_type == 1) {
                    $category = "Reservation";
                    $accounting_code = env("RESERVATION_CODE");
                    $accounting_name = env("RESERVATION_NAME");
                } else if ($ledger->reservation_type == 2) {
                    $category = "Student Deposit";
                    $accounting_code = env("STUDENT_DEPOSIT_CODE");
                    $accounting_name = env("STUDENT_DEPOSIT_NAME");
                }
                $addacct->category = $category;
                $addacct->receipt_details = $category;
                $addacct->particular = $category;
                $addacct->accounting_code = $accounting_code;
                $addacct->accounting_name = $accounting_name;
                $addacct->department = $department;
                $addacct->fiscal_year = $fiscal_year;
                $addacct->debit = $ledger->amount;
                $addacct->posted_by = 999999;
                $addacct->save();
                $ledger->is_consumed = 1;
                $totalReserved = $totalReserved + $ledger->amount;
            }
            self::postDebitMemo($request, $reference_id, $totalReserved, $levels_reference_id, $school_year, $period, $totalamount);
        }
    }

    static function postDebitMemo($request, $reference_id, $totalReserved, $levels_reference_id, $school_year, $period, $totalamount) {
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'BED')->first();
        $debit_memo = new \App\DebitMemo;
        $debit_memo->idno = $request->idno;
        $debit_memo->levels_reference_id = $levels_reference_id;
        $debit_memo->transaction_date = date("Y-m-d");
        $debit_memo->reference_id = $reference_id;
        $debit_memo->dm_no = self::getDMNumber();
        $debit_memo->explanation = "Reversal of Reservation/Student Deposit";
        $debit_memo->amount = $totalamount;
        $debit_memo->reservation_sy = $school_year->school_year;
        $debit_memo->posted_by = 999999;
        $status = \App\Status::where('idno', $request->idno)->first();
        $debit_memo->school_year = $status->school_year;
        if ($status->level == "Grade 11" || $status->level == "Grade 12") {
            $debit_memo->period = $status->period;
        } else {
            $debit_memo->period = "";
        }
        $debit_memo->save();
    }

    static function getDMNumber() {
        $id = \App\ReferenceId::where('idno', 999999)->first()->id;
        $number = \App\ReferenceId::where('idno', 999999)->first()->dm_no;
        $receipt = "";
        for ($i = strlen($number); $i <= 6; $i++) {
            $receipt = $receipt . "0";
        }

        $update = \App\ReferenceId::where('idno', 999999)->first();
        $update->dm_no = $update->dm_no + 1;
        $update->update();

        return $id . $receipt . $number;
    }

    static function addOtherCollection($request, $schoolyear, $period) {
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $adds = \App\ShsOtherCollection::get();
        }else if($request->level == "Grade 7" || $request->level == "Grade 8" || $request->level == "Grade 9" || $request->level == "Grade 10"){
            $adds = \App\JhsOtherCollection::get();
        } else {
            $adds = \App\OtherCollection::get();
        }
        $dept = \App\CtrAcademicProgram::where('level', $request->level)->first();
        if (count($adds) > 0) {
            foreach ($adds as $add) {
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
                $addledger->department = $dept->department;
                $addledger->level = $request->level;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $addledger->strand = $request->strand;
                    $addledger->period = $period;
                }
                $addledger->school_year = $schoolyear;
                $addledger->category = $add->category;
                $addledger->subsidiary = $add->subsidiary;
                $addledger->receipt_details = $add->receipt_details;
                $addledger->accounting_code = $add->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($add->accounting_code);
                $addledger->category_switch = $add->category_switch;
                $addledger->amount = $add->amount;

//                ///please fix this
//                if ($request->level == "Grade 7" || $request->level == "Grade 8" || $request->level == "Grade 9" || $request->level == "Grade 10") {
//                    if($add->subsidiary == "Student Development Fee"){
//                $addledger->amount = $add->amount+250;
//                    }else{
//                $addledger->amount = $add->amount;
//                    }
//                }else{
//                $addledger->amount = $add->amount;
//                }

                $disc_other = $this->getOtherDiscount($request->idno, $add->subsidiary);
                if ($add->subsidiary == "Student Development Fee") {
                    $check_grant = \App\BedScholarship::where('idno', $request->idno)->value('non_discounted');
                    if ($check_grant > 0) {
                        $disc_other = $check_grant / 100 * $addledger->amount;
                    }
                }

                $addledger->discount = $disc_other;
                $addledger->discount_code = $add->subsidiary;
                $addledger->save();

                if ($disc_other > 0) {

                    $discount = new \App\DiscountList;
                    $discount->discount_code = $add->subsidiary;
                    $discount->discount_description = $add->subsidiary;
                    $discount->accounting_code = NULL;
                    $discount->tuition_fee = 0;
                    $discount->other_fee = 0;
                    $discount->misc_fee = 0;
                    $discount->depository_fee = 0;
                    $discount->discount_type = 1;
                    $discount->amount = $disc_other;

                    $this->addDiscountList($request, $schoolyear, $period, $discount);
                }
            }
        }
    }

    function getOtherDiscount($idno, $subsidiary) {
        $disc = \App\DiscountCollection::where('idno', $idno)->where('subsidiary', $subsidiary)->first();
        if (count($disc) > 0) {
            return $disc->discount_amount;
        } else {
            return 0;
        }
    }

    static function roundOff($amount) {
        return round($amount);
    }

    function print_assessment($idno) {
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('reg_be.assessment_form', compact('idno'));
        return $pdf->stream();
    }

    //change strand after enrollment
    function change_strand(Request $request) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            DB::beginTransaction();
            $this->changeStatusStrand($request);
            $this->updateLedgerStrand($request);
            $this->change_due_date($request);
            \App\Http\Controllers\Admin\Logs::log("Change strand of " . $request->idno . " to " . $request->strand);
            DB::commit();
        }
        return redirect(url('/bedregistrar/assess/' . $request->idno));
    }

    function changeStatusStrand($request) {
        $changeStatus = \App\Status::where('idno', $request->idno)->first();
        $changeStatus->strand = $request->strand;
        $school_year = $changeStatus->school_year;
        $period = $changeStatus->period;
        $changeStatus->save();

        $changeBedLevels = \App\BedLevel::where('idno', $request->idno)->where('school_year', $school_year)->where('period', $period)->first();
        $changeBedLevels->strand = $request->strand;
        $changeBedLevels->save();

        $changePromotions = \App\Promotion::where('idno', $request->idno)->first();
        $changePromotions->strand = $request->strand;
        $changePromotions->save();
    }

    function updateLedgerStrand($request) {
        $level = \App\Status::where('idno', $request->idno)->first()->level;
        $request->level = $level;
        $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'SHS')->first()->school_year;
        $period = \App\CtrAcademicSchoolYear::where('academic_type', 'SHS')->first()->period;
        $ledger = \App\Ledger::where('idno', $request->idno)->where('school_year', $school_year)->where('period', $period)->where('category', 'SRF')->first();
        if (count($ledger) > 0) {
            $srf = \App\CtrBedSrf::where('level', $level)->where('strand', $request->strand)->first();
            $ledger->amount = $srf->amount;
            $ledger->save();
        } else {
            $this->addSRF($request, $school_year, $period);
        }
    }

    function change_due_date($request) {
        $stat = \App\Status::where('idno', $request->idno)->first();
        $schoolyear = $stat->school_year;
        $period = $stat->period;
        $request->level = $stat->level;
        $request->plan = $stat->plan;

        $deletedue = \App\LedgerDueDate::where('idno', $request->idno)->where('school_year', $schoolyear)->where('period', $period)->delete();
        $this->addDueDates($request, $schoolyear, $period);
    }

//    public static function log($action) {
//        $log = new \App\Log();
//        $log->action = "$action";
//        $log->idno = Auth::user()->idno;
//        $log->datetime = date("Y-m-d H:i:s");
//        $log->local_ip = $_SERVER['REMOTE_ADDR'];
//        $log->public_ip = $_SERVER['REMOTE_ADDR'];
//        $log->save();
//    }
}
