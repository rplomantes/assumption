<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use App\Http\Controllers\Cashier\MainPayment;
use PDF;

class Assess extends Controller {

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
                    } else if($status->status >= env("PRE_REGISTERED")){
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

    function post_assess(Request $request) {
        $validation = $this->validate($request, [
            'plan' => 'required',
        ]);
        if ($validation) {
            if (Auth::user()->accesslevel == env("REG_BE")) {
                $idno = $request->idno;
                $this->updateAcademicType($request);
                $user = \App\User::where("idno", $request->idno)->first();
                if ($user->academic_type == "BED" || $user->academic_type == "SHS") {
                    $status = \App\Status::where('idno', $request->idno)->first();

                    if ($status->status == 0) {

                        $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type', $user->academic_type)->first();
                        DB::beginTransaction();
                        $this->addGrades($request, $schoolyear->school_year, $schoolyear->period);
                        $this->addLedger($request, $schoolyear->school_year, $schoolyear->period);
                        $this->addOtherCollection($request, $schoolyear->school_year, $schoolyear->period);
                        $this->addOptionalFee($request);
                        $this->addSRF($request, $schoolyear->school_year, $schoolyear->period);
                        $this->addDueDates($request, $schoolyear->school_year, $schoolyear->period);
                        $this->modifyStatus($request, $schoolyear->school_year, $schoolyear->period);
                        $this->checkReservations($request, $schoolyear->school_year, $schoolyear->period);
                        \App\Http\Controllers\Admin\Logs::log("Assess for Enrollment S.Y. $schoolyear->school_year - $idno.");

                        $cut_off = \App\CtrEnrollmentCutOff::where('academic_type', $user->academic_type)->first();
                        if (date('Y-m-d') > $cut_off->cut_off) {
                            $this->addLatePayment($request, $schoolyear->school_year, $schoolyear->period);
                        }


                        //$this->addBooks($request,$schoolyear);
                        DB::commit();
                        return redirect(url('/bedregistrar', array('assess', $idno)));
                        //return view(url('begregistrar',array('viewregistration',$request->idno)));
                        //return $request;
                    } else if ($status->status >= env('ASSESSED')) {
                        return view(url('begregistrar', array('viewregistration', $request->idno)));
                    } else {
                        view('unauthorized');
                    }
                }
            }
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
    }

    function updateAcademicType($request) {
        $user = \App\User::where('idno', $request->idno)->first();
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $user->academic_type = "SHS";
            $user->update();
        } else {
            $user->academic_type = "BED";
            $user->update();
        }
    }

    function changeStatus($id) {
        
    }

    function addGrades($request, $schoolyear, $period) {
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $subjects = \App\BedCurriculum::where('level', $request->level)->where('strand', $request->strand)->get();
        } else {
            $subjects = \App\BedCurriculum::where('level', $request->level)->get();
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

    function addLedger($request, $schoolyear, $period) {
        $discount_code = 0;
        $discount_description = "";
        $discount_tuition = 0;
        $discount_other = 0;
        $discount_depository = 0;
        $discount_misc = 0;
        $discount_srf = 0;
        $discount = \App\CtrDiscount::where('discount_code', $request->discount)->first();
        if (count($discount) > 0) {
            $discount_code = $discount->discount_code;
            $discount_description = $discount->discount_description;
            $discount_tuition = $discount->tuition_fee;
            $discount_other = $discount->other_fee;
            $discount_depository = $discount->depository_fee;
            $discount_misc = $discount->misc_fee;
        }
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
                $addledger->accounting_name = $this->getAccountingName($fee->accounting_code);
                $addledger->category_switch = $fee->category_switch;

                $amount = $fee->amount;
                $discount_amount = 0;
                switch ($fee->category_switch) {
                    case env("MISC_FEE"):
                        $amount = $this->roundOff($fee->amount);
                        $discount_amount = $fee->amount * $discount_misc / 100;
                        break;
                    case env("OTHER_FEE"):
                        $amount = $this->roundOff($fee->amount);
                        $discount_amount = $fee->amount * $discount_other / 100;
                        break;
                    case env("DEPOSITORY_FEE"):
                        $amount = $this->roundOff($fee->amount);
                        $discount_amount = $fee->amount * $discount_depository / 100;
                        break;
                    case env("TUITION_FEE"):
                        $addpercent = $this->addPercentage($request->plan);
                        $amount = $this->roundOff(($fee->amount + ($fee->amount * $addpercent / 100)));
                        $discount_amount = $amount * $discount_tuition / 100;
                }

                $addledger->amount = $amount;
                $addledger->discount_code = $discount_code;
                $addledger->discount = $discount_amount;
                $addledger->save();
            }
            $status = \App\Status::where('idno', $request->idno)->first();
            if (count($status) > 0) {
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
            }
            $is_foreign = \App\User::where('idno', $request->idno)->first();
            if (count($is_foreign) > 0) {
                if ($is_foreign->is_foreign == '1') {
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
                        $addledger->accounting_name = $this->getAccountingName($fee->accounting_code);
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
                        ->whereRaw("school_year=$school_year AND level='Kinder'")->groupBy('level', 'section');

        $statistics = \App\BedLevel::selectRaw("level, section, count(*) as count")
                        ->whereRaw("school_year=$school_year AND status='3'")->groupBy('level', 'section')
                        ->orderBy('level', 'section')->get();

        $abm = \App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'ABM' AND status='3'")->groupBy('sort_by', 'strand', 'section', 'strand')
                ->get();

        $humms = \App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'HUMMS' AND status='3'")->groupBy('sort_by', 'strand', 'section', 'strand')
                ->get();

        $stem = \App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'STEM' AND status='3'")->groupBy('sort_by', 'strand', 'section', 'strand')
                ->get();

        return view('reg_be.enrollment_statistics', compact('statistics', 'abm', 'humms', 'stem', 'school_year', 'kinder'));
    }

    function addSRF($request, $schoolyear, $period) {
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $department = \App\CtrAcademicProgram::where('level', $request->level)->first();
            $srf = \App\CtrBedSrf::where('level', $request->level)->where('strand', $request->strand)->first();
            if (count($srf) > 0) {
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
                $add->save();
            }
        }
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

    function addPercentage($plan) {
        switch ($plan) {
            case "Plan A":
                return 0;
                break;
            case "Plan B":
                return 1;
                break;
            case "Plan C":
                return 2;
                break;
            case "Plan D":
                return 3;
                break;
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
                    $addledger->strand = $request->strand;
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
                    $add->strand = $request->strand;
                    $add->period = $period;
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

    function addDueDates($request, $schoolyear, $period) {
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

            $this->update_due_dates($request, $dueamount, $total_decimal, $dueothers->total);
        }
    }

    function update_due_dates($request, $dueamount, $total_decimal, $dueothers) {
        $update = \App\LedgerDueDate::where('idno', $request->idno)->where('due_switch', 0)->where('due_date', Date('Y-m-d'))->first();
        $update->amount = $update->amount + $dueamount + $total_decimal;
        $update->save();
    }

    function modifyStatus($request, $schoolyear, $period) {
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
                \App\Http\Controllers\Accounting\SetReceiptController::log("Re-assess $idno for S.Y. $schoolyear->school_year.");
                DB::commit();
            }
        }

        return redirect(url('/bedregistrar', array('assess', $idno)));
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
                \App\Http\Controllers\Accounting\SetReceiptController::log("Re-assess $idno for S.Y. $schoolyear->school_year and reversed reservations.");
                DB::commit();
            }
        }

        return redirect(url('/bedregistrar', array('assess', $idno)));
    }

    function reverse_reservations($idno, $levels_reference_id) {
        $reverses = \App\Reservation::where('idno', $idno)->where('levels_reference_id', $levels_reference_id)->get();
        foreach ($reverses as $reverse) {
            $reverse->levels_reference_id = "";
            $reverse->is_consumed = 0;
            $reverse->consume_sy = "";
            $reverse->save();
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
        } else {
            \App\Ledger::where('idno', $idno)->where('category_switch', '<=', env("TUITION_FEE"))->where('school_year', $schoolyear)->delete();
        }
    }

    function removeLedgerDueDate($idno, $schoolyear, $period, $academic_type) {
        if ($academic_type == "BED") {
            \App\LedgerDueDate::where('idno', $idno)->where('school_year', $schoolyear)->delete();
        } else {
            \App\LedgerDueDate::where('idno', $idno)->where('school_year', $schoolyear)->delete();
        }
    }

    function removeGrades($idno, $schoolyear, $period, $academic_type) {
        if ($academic_type == "BED") {
            \App\GradeBasicEd::where('idno', $idno)->where('school_year', $schoolyear)->delete();
        } else {
            \App\GradeBasicEd::where('idno', $idno)->where('school_year', $schoolyear)->delete();
        }
    }

    function returnStatus($idno, $schoolyear, $period, $academic_type) {
        $status = \App\Status::where('idno', $idno)->first();
        $user = \App\User::where('idno', $idno)->first();
        $assignlevel = $status->level;
        switch ($status->level) {
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
        $status->level = $assignlevel;
        $status->status = 0;
        $status->academic_type = $academic_type;
        $status->update();

        $user->academic_type = $academic_type;
        $user->update();
    }

    function checkReservations($request, $school_year, $period) {
        $levels_reference_id = uniqid();
        $checkreservations = \App\Reservation::where('idno', $request->idno)->where('is_consumed', 0)->where('is_reverse', 0)->selectRaw('sum(amount) as amount')->first();
        if ($checkreservations->amount > 0) {
            $totalpayment = $checkreservations->amount;
            $reference_id = uniqid();
            $ledgers = \App\Ledger::where('idno', $request->idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('category_switch', '<=', env("TUITION_FEE"))->get();

            MainPayment::addUnrealizedEntry($request, $reference_id);
            MainPayment::processAccounting($request, $reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
            $this->postDebit($request, $reference_id, $totalpayment, $levels_reference_id);

//            $changestatus = \App\Status::where('idno', $request->idno)->first();
//            $changestatus->status = env("ENROLLED");
//            $changestatus->update();
            $changereservation = \App\Reservation::where('idno', $request->idno)->get();
            if (count($changereservation) > 0) {
                foreach ($changereservation as $change) {
                    $change->levels_reference_id = $levels_reference_id;
                    $change->is_consumed = '1';
                    $change->consume_sy = $school_year;
                    $change->update();
                }
            }
//            MainPayment::addLevels($request->idno, $levels_reference_id);
        }
        $change = \App\Status::where('idno', $request->idno)->first();
        $change->levels_reference_id = $levels_reference_id;
        $change->update();
//        return MainPayment::changeStatus($request->idno, $levels_reference_id);
    }

    function postDebit($request, $reference_id, $totalpayment, $levels_reference_id) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $reservations = \App\Reservation::where('idno', $request->idno)->where('is_consumed', 0)->where('is_reverse', 0)->get();
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
                $addacct->posted_by = Auth::user()->idno;
                $addacct->save();
                $ledger->is_consumed = 1;
                $totalReserved = $totalReserved + $ledger->amount;
            }
            $this->postDebitMemo($request->idno, $reference_id, $totalReserved, $levels_reference_id);
        }
    }

    function postDebitMemo($idno, $reference_id, $totalReserved, $levels_reference_id) {
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'BED')->first();
        $debit_memo = new \App\DebitMemo;
        $debit_memo->idno = $idno;
        $debit_memo->levels_reference_id = $levels_reference_id;
        $debit_memo->transaction_date = date("Y-m-d");
        $debit_memo->reference_id = $reference_id;
        $debit_memo->dm_no = $this->getDMNumber();
        $debit_memo->explanation = "Reversal of Reservation/Student Deposit";
        $debit_memo->amount = $totalReserved;
        $debit_memo->reservation_sy = $school_year->school_year;
        $debit_memo->posted_by = Auth::user()->idno;
        $debit_memo->save();
    }

    function getDMNumber() {
        $id = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->id;
        $number = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->dm_no;
        $receipt = "";
        for ($i = strlen($number); $i <= 6; $i++) {
            $receipt = $receipt . "0";
        }

        $update = \App\ReferenceId::where('idno', Auth::user()->idno)->first();
        $update->dm_no = $update->dm_no + 1;
        $update->update();

        return $id . $receipt . $number;
    }

    function addOtherCollection($request, $schoolyear, $period) {
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $adds = \App\ShsOtherCollection::get();
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
                $disc_other = $this->getOtherDiscount($request->idno, $add->subsidiary);
                $addledger->amount = $add->amount;
                $addledger->discount = $disc_other;
                $addledger->discount_code = $add->subsidiary;
                $addledger->save();
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

    function roundOff($amount) {
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
            $this->log("Change strand of ". $request->idno." to ". $request->strand);
            DB::commit();
        }
        return redirect(url('/bedregistrar/assess/'.$request->idno));
    }
    function changeStatusStrand($request){
        $changeStatus = \App\Status::where('idno', $request->idno)->first();
        $changeStatus->strand = $request->strand;
        $school_year =  $changeStatus->school_year;
        $period = $changeStatus->period;
        $changeStatus->save();
        
        $changeBedLevels = \App\BedLevel::where('idno', $request->idno)->where('school_year', $school_year)->where('period',$period)->first();
        $changeBedLevels->strand = $request->strand;
        $changeBedLevels->save();
    }
    function updateLedgerStrand($request){
        $level = \App\Status::where('idno', $request->idno)->first()->level;
        $request->level = $level;
        $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'SHS')->first()->school_year;
        $period = \App\CtrAcademicSchoolYear::where('academic_type', 'SHS')->first()->period;
        $ledger = \App\Ledger::where('idno', $request->idno)->where('school_year', $school_year)->where('period', $period)->where('category', 'SRF')->first();
        if(count($ledger)>0){
            $srf = \App\CtrBedSrf::where('level', $level)->where('strand', $request->strand)->first();
            $ledger->amount = $srf->amount;
            $ledger->save();
        }else{
            $this->addSRF($request, $school_year, $period);
        }
    }
    function change_due_date($request){
        $stat = \App\Status::where('idno',$request->idno)->first();
        $schoolyear=$stat->school_year;
        $period=$stat->period;
        $request->level=$stat->level;
        $request->plan=$stat->plan;
        
        $deletedue=\App\LedgerDueDate::where('idno',$request->idno)->where('school_year',$schoolyear)->where('period',$period)->delete();
        $this->addDueDates($request, $schoolyear, $period);
    }
    
    public static function log($action){
        $log = new \App\Log();
        $log->action = "$action";
        $log->idno = Auth::user()->idno;
        $log->datetime = date("Y-m-d H:i:s");
        $log->save();
    }

}
