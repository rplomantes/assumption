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
                        $fees = \App\CtrCollegePracticumFee::all();
                    } elseif ($type == 6) {
                        $title = "Practicum Foreign Fee";
                        $fees = \App\CtrCollegePracticumForeignFee::all();
                    } elseif ($type == 7) {
                        $title = "Late Payment Fees";
                        $fees = \App\CtrCollegeLatePayment::all();
                    } elseif ($type == 8) {
                        $title = "Foreign Fees";
                        $fees = \App\CtrCollegeForeignFee::all();
                    }
                    return view('accounting.ajax.display_fees', compact('fees', 'type','title'));
                }
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
                    $fees = \App\CtrCollegeOtherFee::where('program_code', $program)->where('level', $level)->where('period', $period)->get();
                } elseif ($type == 2) {
                    $title = "Non Discounted Other Fees";
                    $fees = \App\CtrCollegeNonDiscountedOtherFee::where('program_code', $program)->where('level', $level)->where('period', $period)->get();
                } elseif ($type == 3) {
                    $title = "Other Fees (New Student)";
                    $fees = \App\CtrCollegeNewOtherFee::where('program_code', $program)->where('level', $level)->where('period', $period)->get();
                } elseif ($type == 4) {
                    $title = "Non Discounted Other Fees (New Student)";
                    $fees = \App\CtrCollegeNewNonDiscountOtherFee::where('program_code', $program)->where('level', $level)->where('period', $period)->get();
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

    function newFees() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $type = Input::get("fee_type");
                return view('accounting.ajax.display_fee_form_new', compact('type'));
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
                if ($type == 6) {
                    $data = new \App\CtrCollegePracticumForeignFee;
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
                if ($type == 7) {
                    $data = new \App\CtrCollegeLatePayment;
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
                if ($type == 8) {
                    $data = new \App\CtrCollegeForeignFee;
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
            }
        }
    }

    function getSwitch($category) {
        if ($category == "Miscellaneous Fees") {
            return env('MISC_FEE');
        } elseif ($category == "Other Fees") {
            return env('OTHER_FEE');
        } elseif ($category == "Depository Fees") {
            return env('DEPOSITORY_FEE');
        } elseif ($category == "Foreign Fee") {
            return env('SRF_FEE');
        } elseif ($category == "Other Miscellaneous") {
            return env('OTHER_MISC');
        }
    }

}
