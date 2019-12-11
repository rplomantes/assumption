<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AjaxScheduleOfFees extends Controller {

    function getFeeType() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("fee_type");
                $title = "";
                $lists = "";
                if ($type <= 4 || $type == 9) {
                    if ($type == 1) {
                        $title = "Other Fees";
                    } elseif ($type == 2) {
                        $title = "Non Discounted Other Fees";
                    } elseif ($type == 3) {
                        $title = "Other Fees (New Student)";
                    } elseif ($type == 4) {
                        $title = "Non Discounted Other Fees (New Student)";
                    } else {
                        $title = "Tuition Fee";
                    }
                    return view('accounting.ajax.display_fees_dropdown', compact('title'));
                } else {
                    if ($type == 5) {
                        $title = "Practicum Fee";
                        $fees = \App\CtrCollegePracticumFee::orderBy('category','asc')->get();
                    } elseif ($type == 6) {
                        $title = "Practicum Foreign Fee";
                        $fees = \App\CtrCollegePracticumForeignFee::orderBy('category','asc')->get();
                    } elseif ($type == 7) {
                        $title = "Late Payment Fees";
                        $fees = \App\CtrCollegeLatePayment::orderBy('category','asc')->get();
                    } elseif ($type == 8) {
                        $title = "Foreign Fees";
                        $fees = \App\CtrCollegeForeignFee::orderBy('category','asc')->get();
                    }
                    return view('accounting.ajax.display_fees', compact('fees', 'type','title'));
                }
            }
        }
    }
    function getFeeType_bed() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("fee_type");
                $title = "";
                $lists = "";
                if ($type <= 4) {
                    if ($type == 1) {
                        $title = "School Fees";
                    }
                    if ($type == 2) {
                        $title = "SHS Subject Related Fees";
                    }
                    return view('accounting.ajax.display_fees_dropdown_bed', compact('title','type'));
                } else {
                    if ($type == 5) {
                        $title = "SHS New Student Additional Fees";
                        $fees = \App\CtrNewShsStudentFee::orderBy('category','asc')->get();
                    } elseif ($type == 6) {
                        $title = "BED New Student Additional Fees";
                        $fees = \App\CtrNewStudentFee::orderBy('category','asc')->get();
                    } elseif ($type == 7) {
                        $title = "Late Payment Fees";
                        $fees = \App\CtrBedLatePayment::orderBy('category','asc')->get();
                    } elseif ($type == 8) {
                        $title = "Foreign Fees";
                        $fees = \App\CtrForiegnFee::orderBy('category','asc')->get();
                    } elseif ($type == 11) {
                        $title = "Other Collections (BED)";
                        $fees = \App\OtherCollection::orderBy('category','asc')->get();
                    } elseif ($type == 10) {
                        $title = "Other Collections (SHS)";
                        $fees = \App\ShsOtherCollection::orderBy('category','asc')->get();
                    }
                    return view('accounting.ajax.display_fees_bed', compact('fees', 'type','title'));
                }
            }
        }
    }

    function getFees_bed() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("fee_type");
                $strand = Input::get("strand");
                $period = Input::get("period");
                $level = Input::get("level");
                if ($type == 1) {
                    $title = "School Fees";
                    $fees = \App\CtrBedFee::where('level', $level)->orderBy('category','asc')->get();
                } elseif ($type == 2) {
                    $title = "SHS Subject Related Fees";
                    $fees = \App\CtrBedSrf::where('level', $level)->where('strand', $strand)->orderBy('category','asc')->get();
                }
                return view('accounting.ajax.display_fees_bed', compact('fees', 'type','title'));
            }
        }
    }

    function getFees() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("fee_type");
                $program = Input::get("program_code");
                $period = Input::get("period");
                $level = Input::get("level");
                if ($type == 1) {
                    $title = "Other Fees";
                    $fees = \App\CtrCollegeOtherFee::where('program_code', $program)->where('level', $level)->where('period', $period)->orderBy('category','asc')->get();
                } elseif ($type == 2) {
                    $title = "Non Discounted Other Fees";
                    $fees = \App\CtrCollegeNonDiscountedOtherFee::where('program_code', $program)->where('level', $level)->where('period', $period)->orderBy('category','asc')->get();
                } elseif ($type == 3) {
                    $title = "Other Fees (New Student)";
                    $fees = \App\CtrCollegeNewOtherFee::where('program_code', $program)->where('level', $level)->where('period', $period)->orderBy('category','asc')->get();
                } elseif ($type == 4) {
                    $title = "Non Discounted Other Fees (New Student)";
                    $fees = \App\CtrCollegeNewNonDiscountOtherFee::where('program_code', $program)->where('level', $level)->where('period', $period)->orderBy('category','asc')->get();
                } elseif ($type == 9) {
                    $title = "Tuition Fee";
                    $fees = \App\CtrCollegeTuitionFee::where('program_code', $program)->where('level', $level)->where('period', $period)->get();
                }
                return view('accounting.ajax.display_fees', compact('fees', 'type','title'));
            }
        }
    }

    function updateFees($id) {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("fee_type");
                if ($type == 1) {
                    $data = \App\CtrCollegeOtherFee::where('id', $id)->first();
                }
                if ($type == 2) {
                    $data = \App\CtrCollegeNonDiscountedOtherFee::where('id', $id)->first();
                }
                if ($type == 3) {
                    $data = \App\CtrCollegeNewOtherFee::where('id', $id)->first();
                }
                if ($type == 4) {
                    $data = \App\CtrCollegeNewNonDiscountOtherFee::where('id', $id)->first();
                }
                if ($type == 5) {
                    $data = \App\CtrCollegePracticumFee::where('id', $id)->first();
                }
                if ($type == 6) {
                    $data = \App\CtrCollegePracticumForeignFee::where('id', $id)->first();
                }
                if ($type == 7) {
                    $data = \App\CtrCollegeLatePayment::where('id', $id)->first();
                }
                if ($type == 8) {
                    $data = \App\CtrCollegeForeignFee::where('id', $id)->first();
                }
                if ($type == 9) {
                    $data = \App\CtrCollegeTuitionFee::where('id', $id)->first();
                }
                return view('accounting.ajax.display_fee_form', compact('data', 'type', 'id'));
            }
        }
    }

    function updateFees_bed($id) {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("fee_type");
                if ($type == 1) {
                    $data = \App\CtrBedFee::where('id', $id)->first();
                }
                if ($type == 2) {
                    $data = \App\CtrBedSrf::where('id', $id)->first();
                }
                if ($type == 5) {
                    $data = \App\CtrNewShsStudentFee::where('id', $id)->first();
                }
                if ($type == 6) {
                    $data = \App\CtrNewStudentFee::where('id', $id)->first();
                }
                if ($type == 7) {
                    $data = \App\CtrBedLatePayment::where('id', $id)->first();
                }
                if ($type == 8) {
                    $data = \App\CtrForiegnFee::where('id', $id)->first();
                }
                if ($type == 11) {
                    $data = \App\OtherCollection::where('id', $id)->first();
                }
                if ($type == 10) {
                    $data = \App\ShsOtherCollection::where('id', $id)->first();
                }
                return view('accounting.ajax.display_fee_form', compact('data', 'type', 'id'));
            }
        }
    }

    function updateSaveFees() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("type");
                $id = Input::get("record_id");
                $account = Input::get("account");
                $category = Input::get("category");
                $subsidiary = Input::get("subsidiary");
                $amount = Input::get("amount");
                $switch = $this->getSwitch($category);
                if ($type == 1) {
                    $data = \App\CtrCollegeOtherFee::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 2) {
                    $data = \App\CtrCollegeNonDiscountedOtherFee::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 3) {
                    $data = \App\CtrCollegeNewOtherFee::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 4) {
                    $data = \App\CtrCollegeNewNonDiscountOtherFee::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 5) {
                    $data = \App\CtrCollegePracticumFee::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 6) {
                    $data = \App\CtrCollegePracticumForeignFee::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 7) {
                    $data = \App\CtrCollegeLatePayment::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 8) {
                    $data = \App\CtrCollegeForeignFee::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 9) {
                    $data = \App\CtrCollegeTuitionFee::where('id', $id)->first();
                    $data->per_unit = $amount;
                    $data->save();
                }
            }
        }
    }

    function updateSaveFees_bed() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("type");
                $id = Input::get("record_id");
                $account = Input::get("account");
                $category = Input::get("category");
                $subsidiary = Input::get("subsidiary");
                $amount = Input::get("amount");
                $switch = $this->getSwitch($category);
                if ($type == 1) {
                    $data = \App\CtrBedFee::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }if ($type == 2) {
                    $data = \App\CtrBedSrf::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 5) {
                    $data = \App\CtrNewShsStudentFee::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 6) {
                    $data = \App\CtrNewStudentFee::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 7) {
                    $data = \App\CtrBedLatePayment::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 8) {
                    $data = \App\CtrForiegnFee::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 11) {
                    $data = \App\OtherCollection::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 10) {
                    $data = \App\ShsOtherCollection::where('id', $id)->first();
                    $data->accounting_code = $account;
                    $data->category_switch = $switch;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
            }
        }
    }

    function removeFees($id) {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("fee_type");
                if ($type == 1) {
                    $data = \App\CtrCollegeOtherFee::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 2) {
                    $data = \App\CtrCollegeNonDiscountedOtherFee::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 3) {
                    $data = \App\CtrCollegeNewOtherFee::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 4) {
                    $data = \App\CtrCollegeNewNonDiscountOtherFee::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 5) {
                    $data = \App\CtrCollegePracticumFee::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 6) {
                    $data = \App\CtrCollegePracticumForeignFee::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 7) {
                    $data = \App\CtrCollegeLatePayment::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 8) {
                    $data = \App\CtrCollegeForeignFee::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 9) {
                    $data = \App\CtrCollegeTuitionFee::where('id', $id)->first();
                    $data->delete();
                }
                return "Removed successfully";
            }
        }
    }

    function removeFees_bed($id) {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("fee_type");
                if ($type == 1) {
                    $data = \App\CtrBedFee::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 2) {
                    $data = \App\CtrBedSrf::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 5) {
                    $data = \App\CtrNewShsStudentFee::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 6) {
                    $data = \App\CtrNewStudentFee::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 7) {
                    $data = \App\CtrBedLatePayment::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 8) {
                    $data = \App\CtrForiegnFee::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 11) {
                    $data = \App\OtherCollection::where('id', $id)->first();
                    $data->delete();
                }
                if ($type == 10) {
                    $data = \App\ShsOtherCollection::where('id', $id)->first();
                    $data->delete();
                }
                return "Removed successfully";
            }
        }
    }

    function newFees() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("fee_type");
                return view('accounting.ajax.display_fee_form_new', compact('type'));
            }
        }
    }

    function newFees_bed() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("fee_type");
                return view('accounting.ajax.display_fee_form_new_bed', compact('type'));
            }
        }
    }

    function newSaveFees() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("type");
                $account = Input::get("account");
                $category = Input::get("category");
                $subsidiary = Input::get("subsidiary");
                $amount = Input::get("amount");
                $program = Input::get("program_code");
                $period = Input::get("period");
                $level = Input::get("level");
                $switch = $this->getSwitch($category);
                if ($type == 1) {
                    $data = new \App\CtrCollegeOtherFee;
                    $data->level = $level;
                    $data->program_code = $program;
                    $data->period = $period;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 2) {
                    $data = new \App\CtrCollegeNonDiscountedOtherFee;
                    $data->level = $level;
                    $data->program_code = $program;
                    $data->period = $period;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 3) {
                    $data = new \App\CtrCollegeNewOtherFee;
                    $data->level = $level;
                    $data->program_code = $program;
                    $data->period = $period;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 4) {
                    $data = new \App\CtrCollegeNewNonDiscountOtherFee;
                    $data->level = $level;
                    $data->program_code = $program;
                    $data->period = $period;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 5) {
                    $data = new \App\CtrCollegePracticumFee;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 6) {
                    $data = new \App\CtrCollegePracticumForeignFee;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 7) {
                    $data = new \App\CtrCollegeLatePayment;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 8) {
                    $data = new \App\CtrCollegeForeignFee;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
            }
        }
    }

    function newSaveFees_bed() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("type");
                $account = Input::get("account");
                $category = Input::get("category");
                $subsidiary = Input::get("subsidiary");
                $amount = Input::get("amount");
                $strand = Input::get("strand");
                $period = Input::get("period");
                $level = Input::get("level");
                $switch = $this->getSwitch($category);
                if ($type == 1) {
                    $data = new \App\CtrBedFee;
                    $data->level = $level;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 2) {
                    $data = new \App\CtrBedSrf;
                    $data->level = $level;
                    $data->strand = $strand;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 5) {
                    $data = new \App\CtrNewShsStudentFee;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 6) {
                    $data = new \App\CtrNewStudentFee;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 7) {
                    $data = new \App\CtrBedLatePayment;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 8) {
                    $data = new \App\CtrForiegnFee;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 11) {
                    $data = new \App\OtherCollection;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
                if ($type == 10) {
                    $data = new \App\ShsOtherCollection;
                    $data->category_switch = $switch;
                    $data->accounting_code = $account;
                    $data->category = $category;
                    $data->subsidiary = $subsidiary;
                    $data->receipt_details = $category;
                    $data->amount = $amount;
                    $data->save();
                }
            }
        }
    }

    function getSwitch($category) {
        if ($category == "Tuition Fee") {
            return env('TUITION_FEE');
        } elseif ($category == "Miscellaneous Fees") {
            return env('MISC_FEE');
        } elseif ($category == "Other Fees") {
            return env('OTHER_FEE');
        } elseif ($category == "Depository Fees") {
            return env('DEPOSITORY_FEE');
        } elseif ($category == "Foreign Fee") {
            return env('SRF_FEE');
        } elseif ($category == "SRF") {
            return env('SRF_FEE');
        } elseif ($category == "Other Miscellaneous") {
            return env('OTHER_MISC');
        } elseif ($category == "Family Council") {
            return env('FAMILY_COUNCIL');
        } elseif ($category == "Parent Partnership") {
            return env('PARENT_PARTNERSHIP');
        } elseif ($category == "Acceptance Fee") {
            return env('ACCEPTANCE_FEE');
        } elseif ($category == "Additional Fee") {
            return env('ADDITIONAL_FEE');
        }
    }

}
