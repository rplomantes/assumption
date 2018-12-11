<?php

function get_plan($level, $category) {
    $amount = \App\CtrBedFee::selectRaw('sum(amount) as amount, level, category')->whereRaw("level = '$level' and category = '$category'")->groupBy('level', 'category')->first();
    if ($level != "Grade 11" && $level != "Grade 12") {
        $other_amount = \App\OtherCollection::selectRaw('sum(amount) as amount, category')->whereRaw("category = '$category'")->groupBy('category')->first();
        if (count($other_amount) > 0) {
            $amount = $amount->amount + $other_amount->amount;
        } else {
            $amount = $amount->amount;
        }
    } else {
        $other_amount = \App\ShsOtherCollection::selectRaw('sum(amount) as amount, category')->whereRaw("category = '$category'")->groupBy('category')->first();
        if (count($other_amount) > 0) {
            $amount = $amount->amount + $other_amount->amount;
        } else {
            if (count($amount) > 0) {
                $amount = $amount->amount;
            }
        }
    }
    return $amount;
}

function get_srf($level, $strand) {
    $srf = \App\CtrBedSrf::where('level', $level)->where('strand', $strand)->first();
    if (count($srf) > 0) {
        echo number_format($srf->amount);
    } else {
        echo "0.00";
    }
}

function get_total($level) {
    $srf = \App\CtrBedSrf::selectRaw("sum(amount) as amount")->where('level', $level)->first();
    if (count($srf) > 0) {
        echo number_format($srf->amount);
    } else {
        echo "0.00";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Assumption College - Accounting</title>
        <style>

            #footer{font-size:8pt;}
        </style>
    </head>
    <body>

        <span id="school_name">ASSUMPTION COLLEGE</span><br>
        <span> School Year : 2018-2019 <br>
            @if($department == "Pre School")
            BEPS
            @elseif($department == "Elementary")
            BEGS
            @elseif($department == "Junior High School")
            BEHS
            @elseif($department == "Senior High School")
            Period : 2nd Semester<br>
            SHS
            @endif
            <br>
            Enrollment Form</span>
        <table cellspacing="0" cellpadding="0" border="1" width="100%">
            <tr><td>Name:<br>&nbsp;</td><td>Grade:<br>&nbsp;</td><td>Plan:<br>&nbsp;</td></tr>
        </table>  
        &nbsp;
        @if($department == "Pre School")
        <?php
        $totalprekinder = 0;
        $prekindertuition = get_plan('Pre-Kinder', 'Tuition Fee');
        $prekindermisc = get_plan('Pre-Kinder', 'Miscellaneous Fees');
        $prekinderothers = get_plan('Pre-Kinder', 'Other Fees');
        $prekinderdep = get_plan('Pre-Kinder', 'Depository Fees');
        $totalprekinder = $prekindertuition + $prekindermisc + $prekinderothers + $prekinderdep;
        $totalkinder = 0;
        $kindertuition = get_plan('Kinder', 'Tuition Fee');
        $kindermisc = get_plan('Kinder', 'Miscellaneous Fees');
        $kinderothers = get_plan('Kinder', 'Other Fees');
        $kinderdep = get_plan('Kinder', 'Depository Fees');
        $totalkinder = $kindertuition + $kindermisc + $kinderothers + $kinderdep;
        ?>
        <style>
            table tr td {font-size: 12pt;}
        </style> 
        <div class="col-md-6">
            <table border="1" cellspacing="0" cellpadding="0" width="50%">
                <tr><td>Particular</td><td>Pre-Kinder</td><td>Kinder</td></tr>
                <tr><td>Tuition Fee</td><td align="right">{{number_format($prekindertuition,2)}}</td><td align="right">{{number_format($kindertuition,2)}}</td></tr>
                <tr><td>Miscellaneous Fees</td><td align="right">{{number_format($prekindermisc,2)}}</td><td align="right">{{number_format($kindermisc,2)}}</td></tr>
                <tr><td>Other Fees</td><td align="right">{{number_format($prekinderothers,2)}}</td><td align="right">{{number_format($kinderothers,2)}}</td></tr>
                <tr><td>Depository Fees</td><td align="right">{{number_format($prekinderdep,2)}}</td><td align="right">{{number_format($kinderdep,2)}}</td></tr>
                <tr><td>Total</td><td align="right">{{number_format($totalprekinder,2)}}</td><td align="right">{{number_format($totalkinder,2)}}</td></tr>
            </table>    
        </div>    

        <table border ="1" border="1" cellspacing="0" cellpadding="0" width="100%">
            <tr><td>Mode of Payment</td><td>Upon Enrollment</td><td>Sept</td><td>Oct</td><td>Nov</td><td>Dec</td><td>Jan</td><td>Feb</td><td>Mar</td><td>Apr</td><td>May</td><td>Total</td></tr>
            <tr><td colspan="12"><b>Pre Kinder</b></td></tr>
            <tr><td>Annual</td><td>{{number_format($totalprekinder,2)}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{number_format($totalprekinder,2)}}</td></tr>
            <tr><td>Semestral</td><td>{{number_format(($prekindertuition*1.01/2)+($totalprekinder-$prekindertuition),2)}}</td><td></td><td></td><td></td><td>{{number_format(($prekindertuition*1.01/2),2)}}</td><td></td><td></td><td></td><td></td><td></td><td>{{number_format(($prekindertuition*1.01)+($totalprekinder-$prekindertuition),2)}}</td></tr>
            <tr><td>Quarterly</td><td>{{number_format(($prekindertuition*1.02/4)+($totalprekinder-$prekindertuition),2)}}</td><td></td><td>{{number_format($prekindertuition*1.02/4,2)}}</td><td></td><td>{{number_format($prekindertuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format($prekindertuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format(($prekindertuition*1.02)+($totalprekinder-$prekindertuition),2)}}</td></tr>
            <tr><td>Monthly</td><td>{{number_format(($prekindertuition*1.03/10)+($totalprekinder-$prekindertuition),2)}}</td><td>{{number_format($prekindertuition*1.03/10,2)}}</td><td>{{number_format($prekindertuition*1.03/10,2)}}</td><td>{{number_format($prekindertuition*1.03/10,2)}}</td><td>{{number_format($prekindertuition*1.03/10,2)}}</td><td>{{number_format($prekindertuition*1.03/10,2)}}</td><td>{{number_format($prekindertuition*1.03/10,2)}}</td><td>{{number_format($prekindertuition*1.03/10,2)}}</td><td>{{number_format($prekindertuition*1.03/10,2)}}</td><td>{{number_format($prekindertuition*1.03/10,2)}}</td><td>{{number_format(($prekindertuition*1.03)+($totalprekinder-$prekindertuition),2)}}</td></tr>
            <tr><td colspan="12"><b>Kinder</b></td></tr>
            <tr><td>Annual</td><td>{{number_format($totalkinder,2)}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{number_format($totalkinder,2)}}</td></tr>
            <tr><td>Semestral</td><td>{{number_format(($kindertuition*1.01/2)+($totalkinder-$kindertuition),2)}}</td><td></td><td></td><td></td><td>{{number_format(($kindertuition*1.01/2),2)}}</td><td></td><td></td><td></td><td></td><td></td><td>{{number_format(($kindertuition*1.01)+($totalkinder-$prekindertuition),2)}}</td></tr>
            <tr><td>Quarterly</td><td>{{number_format(($kindertuition*1.02/4)+($totalkinder-$kindertuition),2)}}</td><td></td><td>{{number_format($kindertuition*1.02/4,2)}}</td><td></td><td>{{number_format($kindertuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format($kindertuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format(($kindertuition*1.02)+($totalkinder-$kindertuition),2)}}</td></tr>
            <tr><td>Monthly</td><td>{{number_format(($kindertuition*1.03/10)+($totalkinder-$prekindertuition),2)}}</td><td>{{number_format($kindertuition*1.03/10,2)}}</td><td>{{number_format($kindertuition*1.03/10,2)}}</td><td>{{number_format($kindertuition*1.03/10,2)}}</td><td>{{number_format($kindertuition*1.03/10,2)}}</td><td>{{number_format($kindertuition*1.03/10,2)}}</td><td>{{number_format($kindertuition*1.03/10,2)}}</td><td>{{number_format($kindertuition*1.03/10,2)}}</td><td>{{number_format($kindertuition*1.03/10,2)}}</td><td>{{number_format($kindertuition*1.03/10,2)}}</td><td>{{number_format(($kindertuition*1.03)+($totalkinder-$kindertuition),2)}}</td></tr>
        </table>    
        @endif

        @if($department == "Elementary")
        <style>
            table tr td {font-size: 8pt;}
        </style> 
        <?php
        $grade1total = 0;
        $grade1tuition = get_plan('Grade 1', 'Tuition Fee');
        $grade1misc = get_plan('Grade 1', 'Miscellaneous Fees');
        $grade1others = get_plan('Grade 1', 'Other Fees');
        $grade1dep = get_plan('Grade 1', 'Depository Fees');
        $grade1total = $grade1tuition + $grade1misc + $grade1others + $grade1dep;

        $grade2total = 0;
        $grade2tuition = get_plan('Grade 2', 'Tuition Fee');
        $grade2misc = get_plan('Grade 2', 'Miscellaneous Fees');
        $grade2others = get_plan('Grade 2', 'Other Fees');
        $grade2dep = get_plan('Grade 2', 'Depository Fees');
        $grade2total = $grade2tuition + $grade2misc + $grade2others + $grade2dep;

        $grade3total = 0;
        $grade3tuition = get_plan('Grade 3', 'Tuition Fee');
        $grade3misc = get_plan('Grade 3', 'Miscellaneous Fees');
        $grade3others = get_plan('Grade 3', 'Other Fees');
        $grade3dep = get_plan('Grade 3', 'Depository Fees');
        $grade3total = $grade3tuition + $grade3misc + $grade3others + $grade3dep;

        $grade4total = 0;
        $grade4tuition = get_plan('Grade 4', 'Tuition Fee');
        $grade4misc = get_plan('Grade 4', 'Miscellaneous Fees');
        $grade4others = get_plan('Grade 4', 'Other Fees');
        $grade4dep = get_plan('Grade 4', 'Depository Fees');
        $grade4total = $grade4tuition + $grade4misc + $grade4others + $grade4dep;

        $grade5total = 0;
        $grade5tuition = get_plan('Grade 5', 'Tuition Fee');
        $grade5misc = get_plan('Grade 5', 'Miscellaneous Fees');
        $grade5others = get_plan('Grade 5', 'Other Fees');
        $grade5dep = get_plan('Grade 5', 'Depository Fees');
        $grade5total = $grade5tuition + $grade5misc + $grade5others + $grade5dep;

        $grade6total = 0;
        $grade6tuition = get_plan('Grade 6', 'Tuition Fee');
        $grade6misc = get_plan('Grade 6', 'Miscellaneous Fees');
        $grade6others = get_plan('Grade 6', 'Other Fees');
        $grade6dep = get_plan('Grade 6', 'Depository Fees');
        $grade6total = $grade6tuition + $grade6misc + $grade6others + $grade6dep;
        ?>
        <div class="col-md-12">
            <table border="1" cellspacing="0" cellpadding="0" width="100%">
                <tr><td>Particular</td><td>Grade 1</td><td>Grade 2</td><td>Grade 3</td><td>Grade 4</td><td>Grade 5</td><td>Grade 6</td></tr>
                <tr><td>Tuition Fee</td><td align="right">{{number_format($grade1tuition,2)}}</td><td align="right">{{number_format($grade2tuition,2)}}</td><td align="right">{{number_format($grade3tuition,2)}}</td><td align="right">{{number_format($grade4tuition,2)}}</td><td align="right">{{number_format($grade5tuition,2)}}</td><td align="right">{{number_format($grade6tuition,2)}}</td></tr>
                <tr><td>Miscellaneous Fees</td><td align="right">{{number_format($grade1misc,2)}}</td><td align="right">{{number_format($grade2misc,2)}}</td><td align="right">{{number_format($grade3misc,2)}}</td><td align="right">{{number_format($grade4misc,2)}}</td><td align="right">{{number_format($grade5misc,2)}}</td><td align="right">{{number_format($grade6misc,2)}}</td></tr>
                <tr><td>Other Fees</td><td align="right">{{number_format($grade1others,2)}}</td><td align="right">{{number_format($grade2others,2)}}</td><td align="right">{{number_format($grade3others,2)}}</td><td align="right">{{number_format($grade4others,2)}}</td><td align="right">{{number_format($grade5others,2)}}</td><td align="right">{{number_format($grade6others,2)}}</td></tr>
                <tr><td>Depository Fees</td><td align="right">{{number_format($grade1dep,2)}}</td><td align="right">{{number_format($grade2dep,2)}}</td><td align="right">{{number_format($grade3dep,2)}}</td><td align="right">{{number_format($grade4dep,2)}}</td><td align="right">{{number_format($grade5dep,2)}}</td><td align="right">{{number_format($grade6dep,2)}}</td></tr>
                <tr><td>Total</td><td align="right">{{number_format($grade1total,2)}}</td><td align="right">{{number_format($grade2total,2)}}</td><td align="right">{{number_format($grade3total,2)}}</td><td align="right">{{number_format($grade4total,2)}}</td><td align="right">{{number_format($grade5total,2)}}</td><td align="right">{{number_format($grade6total,2)}}</td></tr>
            </table>    
        </div>    

        <table border ="1" border="1" cellspacing="0" cellpadding="0" width="100%">
            <tr><td>Mode of Payment</td><td>Upon Enrollment</td><td>Sept</td><td>Oct</td><td>Nov</td><td>Dec</td><td>Jan</td><td>Feb</td><td>Mar</td><td>Apr</td><td>May</td><td>Total</td></tr>
            <tr><td colspan="12"><b>Grade 1</b></td></tr>
            <tr><td>Annual</td><td>{{number_format($grade1total,2)}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{number_format($grade1total,2)}}</td></tr>
            <tr><td>Semestral</td><td>{{number_format(($grade1tuition*1.01/2)+($grade1total-$grade1tuition),2)}}</td><td></td><td></td><td></td><td>{{number_format(($grade1tuition*1.01/2),2)}}</td><td></td><td></td><td></td><td></td><td></td><td>{{number_format(($grade1tuition*1.01)+($grade1total-$grade1tuition),2)}}</td></tr>
            <tr><td>Quarterly</td><td>{{number_format(($grade1tuition*1.02/4)+($grade1total-$grade1tuition),2)}}</td><td></td><td>{{number_format($grade1tuition*1.02/4,2)}}</td><td></td><td>{{number_format($grade1tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format($grade1tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format(($grade1tuition*1.02)+($grade1total-$grade1tuition),2)}}</td></tr>
            <tr><td>Monthly</td><td>{{number_format(($grade1tuition*1.03/10)+($grade1total-$grade1tuition),2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format(($grade1tuition*1.03)+($grade1total-$grade1tuition),2)}}</td></tr>
            <tr><td colspan="12"><b>Grade 2</b></td></tr>
            <tr><td>Annual</td><td>{{number_format($grade2total,2)}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{number_format($grade2total,2)}}</td></tr>
            <tr><td>Semestral</td><td>{{number_format(($grade2tuition*1.01/2)+($grade2total-$grade2tuition),2)}}</td><td></td><td></td><td></td><td>{{number_format(($grade2tuition*1.01/2),2)}}</td><td></td><td></td><td></td><td></td><td></td><td>{{number_format(($grade2tuition*1.01)+($grade2total-$grade2tuition),2)}}</td></tr>
            <tr><td>Quarterly</td><td>{{number_format(($grade2tuition*1.02/4)+($grade2total-$grade2tuition),2)}}</td><td></td><td>{{number_format($grade2tuition*1.02/4,2)}}</td><td></td><td>{{number_format($grade2tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format($grade2tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format(($grade2tuition*1.02)+($grade2total-$grade2tuition),2)}}</td></tr>
            <tr><td>Monthly</td><td>{{number_format(($grade2tuition*1.03/10)+($grade2total-$grade2tuition),2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format(($grade2tuition*1.03)+($grade2total-$grade2tuition),2)}}</td></tr>


            <tr><td colspan="12"><b>Grade 3</b></td></tr>
            <tr><td>Annual</td><td>{{number_format($grade3total,2)}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{number_format($grade3total,2)}}</td></tr>
            <tr><td>Semestral</td><td>{{number_format(($grade3tuition*1.01/2)+($grade3total-$grade3tuition),2)}}</td><td></td><td></td><td></td><td>{{number_format(($grade3tuition*1.01/2),2)}}</td><td></td><td></td><td></td><td></td><td></td><td>{{number_format(($grade3tuition*1.01)+($grade3total-$grade3tuition),2)}}</td></tr>
            <tr><td>Quarterly</td><td>{{number_format(($grade3tuition*1.02/4)+($grade3total-$grade3tuition),2)}}</td><td></td><td>{{number_format($grade3tuition*1.02/4,2)}}</td><td></td><td>{{number_format($grade3tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format($grade3tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format(($grade3tuition*1.02)+($grade3total-$grade3tuition),2)}}</td></tr>
            <tr><td>Monthly</td><td>{{number_format(($grade3tuition*1.03/10)+($grade3total-$grade3tuition),2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format(($grade3tuition*1.03)+($grade3total-$grade3tuition),2)}}</td></tr>


            <tr><td colspan="12"><b>Grade 4</b></td></tr>
            <tr><td>Annual</td><td>{{number_format($grade4total,2)}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{number_format($grade4total,2)}}</td></tr>
            <tr><td>Semestral</td><td>{{number_format(($grade4tuition*1.01/2)+($grade4total-$grade4tuition),2)}}</td><td></td><td></td><td></td><td>{{number_format(($grade4tuition*1.01/2),2)}}</td><td></td><td></td><td></td><td></td><td></td><td>{{number_format(($grade4tuition*1.01)+($grade4total-$grade4tuition),2)}}</td></tr>
            <tr><td>Quarterly</td><td>{{number_format(($grade4tuition*1.02/4)+($grade4total-$grade4tuition),2)}}</td><td></td><td>{{number_format($grade4tuition*1.02/4,2)}}</td><td></td><td>{{number_format($grade4tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format($grade4tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format(($grade4tuition*1.02)+($grade4total-$grade4tuition),2)}}</td></tr>
            <tr><td>Monthly</td><td>{{number_format(($grade4tuition*1.03/10)+($grade4total-$grade4tuition),2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format(($grade4tuition*1.03)+($grade4total-$grade4tuition),2)}}</td></tr>


            <tr><td colspan="12"><b>Grade 5</b></td></tr>
            <tr><td>Annual</td><td>{{number_format($grade5total,2)}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{number_format($grade5total,2)}}</td></tr>
            <tr><td>Semestral</td><td>{{number_format(($grade5tuition*1.01/2)+($grade5total-$grade5tuition),2)}}</td><td></td><td></td><td></td><td>{{number_format(($grade5tuition*1.01/2),2)}}</td><td></td><td></td><td></td><td></td><td></td><td>{{number_format(($grade5tuition*1.01)+($grade5total-$grade5tuition),2)}}</td></tr>
            <tr><td>Quarterly</td><td>{{number_format(($grade5tuition*1.02/4)+($grade5total-$grade5tuition),2)}}</td><td></td><td>{{number_format($grade5tuition*1.02/4,2)}}</td><td></td><td>{{number_format($grade5tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format($grade5tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format(($grade5tuition*1.02)+($grade5total-$grade5tuition),2)}}</td></tr>
            <tr><td>Monthly</td><td>{{number_format(($grade5tuition*1.03/10)+($grade5total-$grade5tuition),2)}}</td><td>{{number_format($grade5tuition*1.03/10,2)}}</td><td>{{number_format($grade5tuition*1.03/10,2)}}</td><td>{{number_format($grade5tuition*1.03/10,2)}}</td><td>{{number_format($grade5tuition*1.03/10,2)}}</td><td>{{number_format($grade5tuition*1.03/10,2)}}</td><td>{{number_format($grade5tuition*1.03/10,2)}}</td><td>{{number_format($grade5tuition*1.03/10,2)}}</td><td>{{number_format($grade5tuition*1.03/10,2)}}</td><td>{{number_format($grade5tuition*1.03/10,2)}}</td><td>{{number_format(($grade5tuition*1.03)+($grade5total-$grade5tuition),2)}}</td></tr>


            <tr><td colspan="12"><b>Grade 6</b></td></tr>
            <tr><td>Annual</td><td>{{number_format($grade6total,2)}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{number_format($grade6total,2)}}</td></tr>
            <tr><td>Semestral</td><td>{{number_format(($grade6tuition*1.01/2)+($grade6total-$grade6tuition),2)}}</td><td></td><td></td><td></td><td>{{number_format(($grade6tuition*1.01/2),2)}}</td><td></td><td></td><td></td><td></td><td></td><td>{{number_format(($grade6tuition*1.01)+($grade6total-$grade6tuition),2)}}</td></tr>
            <tr><td>Quarterly</td><td>{{number_format(($grade6tuition*1.02/4)+($grade6total-$grade6tuition),2)}}</td><td></td><td>{{number_format($grade6tuition*1.02/4,2)}}</td><td></td><td>{{number_format($grade6tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format($grade6tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format(($grade6tuition*1.02)+($grade6total-$grade6tuition),2)}}</td></tr>
            <tr><td>Monthly</td><td>{{number_format(($grade6tuition*1.03/10)+($grade6total-$grade6tuition),2)}}</td><td>{{number_format($grade6tuition*1.03/10,2)}}</td><td>{{number_format($grade6tuition*1.03/10,2)}}</td><td>{{number_format($grade6tuition*1.03/10,2)}}</td><td>{{number_format($grade6tuition*1.03/10,2)}}</td><td>{{number_format($grade6tuition*1.03/10,2)}}</td><td>{{number_format($grade6tuition*1.03/10,2)}}</td><td>{{number_format($grade6tuition*1.03/10,2)}}</td><td>{{number_format($grade6tuition*1.03/10,2)}}</td><td>{{number_format($grade6tuition*1.03/10,2)}}</td><td>{{number_format(($grade6tuition*1.03)+($grade6total-$grade6tuition),2)}}</td></tr>
        </table>    
        @endif

        @if($department == "Junior High School")
        <?php
        $grade1total = 0;
        $grade1tuition = get_plan('Grade 7', 'Tuition Fee');
        $grade1misc = get_plan('Grade 7', 'Miscellaneous Fees');
        $grade1others = get_plan('Grade 7', 'Other Fees');
        $grade1dep = get_plan('Grade 7', 'Depository Fees');
        $grade1total = $grade1tuition + $grade1misc + $grade1others + $grade1dep;

        $grade2total = 0;
        $grade2tuition = get_plan('Grade 8', 'Tuition Fee');
        $grade2misc = get_plan('Grade 8', 'Miscellaneous Fees');
        $grade2others = get_plan('Grade 8', 'Other Fees');
        $grade2dep = get_plan('Grade 8', 'Depository Fees');
        $grade2total = $grade2tuition + $grade2misc + $grade2others + $grade2dep;

        $grade3total = 0;
        $grade3tuition = get_plan('Grade 9', 'Tuition Fee');
        $grade3misc = get_plan('Grade 9', 'Miscellaneous Fees');
        $grade3others = get_plan('Grade 9', 'Other Fees');
        $grade3dep = get_plan('Grade 9', 'Depository Fees');
        $grade3total = $grade3tuition + $grade3misc + $grade3others + $grade3dep;

        $grade4total = 0;
        $grade4tuition = get_plan('Grade 10', 'Tuition Fee');
        $grade4misc = get_plan('Grade 10', 'Miscellaneous Fees');
        $grade4others = get_plan('Grade 10', 'Other Fees');
        $grade4dep = get_plan('Grade 10', 'Depository Fees');
        $grade4total = $grade4tuition + $grade4misc + $grade4others + $grade4dep;
        ?>
        <style>
            table tr td {font-size: 9pt;}
        </style> 
        <div class="col-md-10">
            <table border="1" cellspacing="0" cellpadding="1" width="100%">
                <tr><td>Particular</td><td>Grade 7</td><td>Grade 8</td><td>Grade 9</td><td>Grade 10</td></tr>
                <tr><td>Tuition Fee</td><td align="right">{{number_format($grade1tuition,2)}}</td><td align="right">{{number_format($grade2tuition,2)}}</td><td align="right">{{number_format($grade3tuition,2)}}</td><td align="right">{{number_format($grade4tuition,2)}}</td></tr>
                <tr><td>Miscellaneous Fees</td><td align="right">{{number_format($grade1misc,2)}}</td><td align="right">{{number_format($grade2misc,2)}}</td><td align="right">{{number_format($grade3misc,2)}}</td><td align="right">{{number_format($grade4misc,2)}}</td></tr>
                <tr><td>Other Fees</td><td align="right">{{number_format($grade1others,2)}}</td><td align="right">{{number_format($grade2others,2)}}</td><td align="right">{{number_format($grade3others,2)}}</td><td align="right">{{number_format($grade4others,2)}}</td></tr>
                <tr><td>Depository Fees</td><td align="right">{{number_format($grade1dep,2)}}</td><td align="right">{{number_format($grade2dep,2)}}</td><td align="right">{{number_format($grade3dep,2)}}</td><td align="right">{{number_format($grade4dep,2)}}</td></tr>
                <tr><td>Total</td><td align="right">{{number_format($grade1total,2)}}</td><td align="right">{{number_format($grade2total,2)}}</td><td align="right">{{number_format($grade3total,2)}}</td><td align="right">{{number_format($grade4total,2)}}</td></tr>
            </table>    
        </div>    

        <table border ="1" border="1" cellspacing="0" cellpadding="1" width="100%">
            <tr><td>Mode of Payment</td><td>Upon Enrollment</td><td>Sept</td><td>Oct</td><td>Nov</td><td>Dec</td><td>Jan</td><td>Feb</td><td>Mar</td><td>Apr</td><td>May</td><td>Total</td></tr>
            <tr><td colspan="12"><b>Grade 7</b></td></tr>
            <tr><td>Annual</td><td>{{number_format($grade1total,2)}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{number_format($grade1total,2)}}</td></tr>
            <tr><td>Semestral</td><td>{{number_format(($grade1tuition*1.01/2)+($grade1total-$grade1tuition),2)}}</td><td></td><td></td><td></td><td>{{number_format(($grade1tuition*1.01/2),2)}}</td><td></td><td></td><td></td><td></td><td></td><td>{{number_format(($grade1tuition*1.01)+($grade1total-$grade1tuition),2)}}</td></tr>
            <tr><td>Quarterly</td><td>{{number_format(($grade1tuition*1.02/4)+($grade1total-$grade1tuition),2)}}</td><td></td><td>{{number_format($grade1tuition*1.02/4,2)}}</td><td></td><td>{{number_format($grade1tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format($grade1tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format(($grade1tuition*1.02)+($grade1total-$grade1tuition),2)}}</td></tr>
            <tr><td>Monthly</td><td>{{number_format(($grade1tuition*1.03/10)+($grade1total-$grade1tuition),2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format($grade1tuition*1.03/10,2)}}</td><td>{{number_format(($grade1tuition*1.03)+($grade1total-$grade1tuition),2)}}</td></tr>
            <tr><td colspan="12"><b>Grade 8</b></td></tr>
            <tr><td>Annual</td><td>{{number_format($grade2total,2)}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{number_format($grade2total,2)}}</td></tr>
            <tr><td>Semestral</td><td>{{number_format(($grade2tuition*1.01/2)+($grade2total-$grade2tuition),2)}}</td><td></td><td></td><td></td><td>{{number_format(($grade2tuition*1.01/2),2)}}</td><td></td><td></td><td></td><td></td><td></td><td>{{number_format(($grade2tuition*1.01)+($grade2total-$grade2tuition),2)}}</td></tr>
            <tr><td>Quarterly</td><td>{{number_format(($grade2tuition*1.02/4)+($grade2total-$grade2tuition),2)}}</td><td></td><td>{{number_format($grade2tuition*1.02/4,2)}}</td><td></td><td>{{number_format($grade2tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format($grade2tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format(($grade2tuition*1.02)+($grade2total-$grade2tuition),2)}}</td></tr>
            <tr><td>Monthly</td><td>{{number_format(($grade2tuition*1.03/10)+($grade2total-$grade2tuition),2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format($grade2tuition*1.03/10,2)}}</td><td>{{number_format(($grade2tuition*1.03)+($grade2total-$grade2tuition),2)}}</td></tr>


            <tr><td colspan="12"><b>Grade 9</b></td></tr>
            <tr><td>Annual</td><td>{{number_format($grade3total,2)}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{number_format($grade3total,2)}}</td></tr>
            <tr><td>Semestral</td><td>{{number_format(($grade3tuition*1.01/2)+($grade3total-$grade3tuition),2)}}</td><td></td><td></td><td></td><td>{{number_format(($grade3tuition*1.01/2),2)}}</td><td></td><td></td><td></td><td></td><td></td><td>{{number_format(($grade3tuition*1.01)+($grade3total-$grade3tuition),2)}}</td></tr>
            <tr><td>Quarterly</td><td>{{number_format(($grade3tuition*1.02/4)+($grade3total-$grade3tuition),2)}}</td><td></td><td>{{number_format($grade3tuition*1.02/4,2)}}</td><td></td><td>{{number_format($grade3tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format($grade3tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format(($grade3tuition*1.02)+($grade3total-$grade3tuition),2)}}</td></tr>
            <tr><td>Monthly</td><td>{{number_format(($grade3tuition*1.03/10)+($grade3total-$grade3tuition),2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format($grade3tuition*1.03/10,2)}}</td><td>{{number_format(($grade3tuition*1.03)+($grade3total-$grade3tuition),2)}}</td></tr>


            <tr><td colspan="12"><b>Grade 10</b></td></tr>
            <tr><td>Annual</td><td>{{number_format($grade4total,2)}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>{{number_format($grade4total,2)}}</td></tr>
            <tr><td>Semestral</td><td>{{number_format(($grade4tuition*1.01/2)+($grade4total-$grade4tuition),2)}}</td><td></td><td></td><td></td><td>{{number_format(($grade4tuition*1.01/2),2)}}</td><td></td><td></td><td></td><td></td><td></td><td>{{number_format(($grade4tuition*1.01)+($grade4total-$grade4tuition),2)}}</td></tr>
            <tr><td>Quarterly</td><td>{{number_format(($grade4tuition*1.02/4)+($grade4total-$grade4tuition),2)}}</td><td></td><td>{{number_format($grade4tuition*1.02/4,2)}}</td><td></td><td>{{number_format($grade4tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format($grade4tuition*1.02/4,2)}}</td><td></td><td></td><td>{{number_format(($grade4tuition*1.02)+($grade4total-$grade4tuition),2)}}</td></tr>
            <tr><td>Monthly</td><td>{{number_format(($grade4tuition*1.03/10)+($grade4total-$grade4tuition),2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format($grade4tuition*1.03/10,2)}}</td><td>{{number_format(($grade4tuition*1.03)+($grade4total-$grade4tuition),2)}}</td></tr>

        </table>
        @endif
        @if($department == "Senior High School")
        <?php
        $grade11total = 0;
        $grade11tuition = get_plan('Grade 11', 'Tuition Fee');
        $grade11misc = get_plan('Grade 11', 'Miscellaneous Fees');
        $grade11others = get_plan('Grade 11', 'Other Fees');
        $grade11dep = get_plan('Grade 11', 'Depository Fees');
        $grade11total = $grade11tuition + $grade11misc + $grade11others + $grade11dep;

        $grade12total = 0;
        $grade12tuition = get_plan('Grade 12', 'Tuition Fee');
        $grade12misc = get_plan('Grade 12', 'Miscellaneous Fees');
        $grade12others = get_plan('Grade 12', 'Other Fees');
        $grade12dep = get_plan('Grade 12', 'Depository Fees');
        $grade12total = $grade12tuition + $grade12misc + $grade12others + $grade12dep;
        ?>
        <style>
            table tr td {font-size: 9pt;}
        </style> 
        <div>
            <table border="1" cellspacing="0" cellpadding="1" width="100%">
                <tr><td>Particular</td><td>Grade 11</td><td>Grade 12</td></tr>
                <tr><td>Tuition Fee</td><td align="right">{{number_format($grade11tuition,2)}}</td><td align="right">{{number_format($grade12tuition,2)}}</td></tr>
                <tr><td>Miscellaneous Fees</td><td align="right">{{number_format($grade11misc,2)}}</td><td align="right">{{number_format($grade12misc,2)}}</td></tr>
                <tr><td>Other Fees</td><td align="right">{{number_format($grade11others,2)}}</td><td align="right">{{number_format($grade12others,2)}}</td></tr>
                <tr><td>Depository Fees</td><td align="right">{{number_format($grade11dep,2)}}</td><td align="right">{{number_format($grade12dep,2)}}</td></tr>
                <tr><td>Total</td><td align="right">{{number_format($grade11total,2)}}</td><td align="right">{{number_format($grade12total,2)}}</td></tr>
            </table>  
            &nbsp;
            <table border="1" cellspacing="0" cellpadding="1" width="100%">
                <tr><td>Strand</td><td>Grade 11</td><td>Grade 12</td></tr>
                <tr><td>ABM</td><td align="right">{{get_srf('Grade 11','ABM')}}</td><td align="right">{{get_srf('Grade 12','ABM')}}</td></tr>
                <tr><td>HUMMS</td><td align="right">{{get_srf('Grade 11','HUMSS')}}</td><td align="right">{{get_srf('Grade 12','HUMSS')}}</td></tr>
                <tr><td>STEM</td><td align="right">{{get_srf('Grade 11','STEM')}}</td><td align="right">{{get_srf('Grade 12','STEM')}}</td></tr>
                <tr><td>GA</td><td align="right">{{get_srf('Grade 11','GA')}}</td><td align="right">{{get_srf('Grade 12','GA')}}</td></tr>
                <tr><td>Total SRF</td><td align="right">{{get_total('Grade 11')}}</td><td align="right">{{get_total('Grade 12')}}</td></tr>
            </table> 
        </div>
        <div style="clear:both"></div>
        &nbsp;
        <table border ="1" border="1" cellspacing="0" cellpadding="1" width="100%">
            <tr>
                <td>Mode of Payment</td>
                <td>Upon Enrollment</td>
                <td>Feb</td>
                <td>Mar</td>
                <td>Apr</td>
                <td>May</td>
                <td>Total</td>
            </tr>
            <tr><td colspan="7"><b>Grade 11</b></td></tr>
            <tr>
                <td>Plan A</td>
                <td>{{number_format($grade11total,2)}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{number_format($grade11total,2)}}</td>
            </tr>
            <tr>
                <td>Plan B</td>
                <td>{{number_format(($grade11tuition*1.01/2)+($grade11total-$grade11tuition),2)}}</td>
                <td></td>
                <td>{{number_format(($grade11tuition*1.01/2),2)}}</td>
                <td></td>
                <td></td>
                <td>{{number_format(($grade11tuition*1.01)+($grade11total-$grade11tuition),2)}}</td>
            </tr>
            <tr>
                <td>Plan C</td>
                <td>{{number_format(($grade11tuition*1.02/3)+($grade11total-$grade11tuition),2)}}</td>
                <td>{{number_format($grade11tuition*1.02/3,2)}}</td>
                <td></td>
                <td>{{number_format($grade11tuition*1.02/3,2)}}</td>
                <td></td>
                <td>{{number_format(($grade11tuition*1.02)+($grade11total-$grade11tuition),2)}}</td>
            </tr>
            <tr>
                <td>Plan D</td>
                <td>{{number_format(($grade11tuition*1.03/5)+($grade11total-$grade11tuition),2)}}</td>
                <td>{{number_format($grade11tuition*1.03/5,2)}}</td>
                <td>{{number_format($grade11tuition*1.03/5,2)}}</td>
                <td>{{number_format($grade11tuition*1.03/5,2)}}</td>
                <td>{{number_format($grade11tuition*1.03/5,2)}}</td>
                <td>{{number_format(($grade11tuition*1.03)+($grade11total-$grade11tuition),2)}}</td>
            </tr>
            <tr><td colspan="7"><b>Grade 12</b></td></tr>
            <tr>
                <td>Plan A</td>
                <td>{{number_format($grade12total,2)}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{number_format($grade12total,2)}}</td>
            </tr>
            <tr>
                <td>Plan B</td>
                <td>{{number_format(($grade12tuition*1.01/2)+($grade12total-$grade12tuition),2)}}</td>
                <td></td>
                <td>{{number_format(($grade12tuition*1.01/2),2)}}</td>
                <td></td>
                <td></td>
                <td>{{number_format(($grade12tuition*1.01)+($grade12total-$grade12tuition),2)}}</td>
            </tr>
            <tr>
                <td>Plan C</td>
                <td>{{number_format(($grade12tuition*1.02/3)+($grade12total-$grade12tuition),2)}}</td>
                <td>{{number_format($grade12tuition*1.02/3,2)}}</td>
                <td></td>
                <td>{{number_format($grade12tuition*1.02/3,2)}}</td>
                <td></td>
                <td>{{number_format(($grade12tuition*1.02)+($grade12total-$grade12tuition),2)}}</td>
            </tr>
            <tr>
                <td>Plan D</td>
                <td>{{number_format(($grade12tuition*1.03/5)+($grade12total-$grade12tuition),2)}}</td>
                <td>{{number_format($grade12tuition*1.03/5,2)}}</td>
                <td>{{number_format($grade12tuition*1.03/5,2)}}</td>
                <td>{{number_format($grade12tuition*1.03/5,2)}}</td>
                <td>{{number_format($grade12tuition*1.03/5,2)}}</td>
                <td>{{number_format(($grade12tuition*1.03)+($grade12total-$grade12tuition),2)}}</td>
            </tr>

        </table>

        @endif
        <span id="footer">
            (1)You may pay over the counter at any Bank of the Philippine Islands Branch. <br>
            Please indicate in your deposit slip the following : <br>
            <b>ACCOUNT NAME: ASSUMPTION COLLEGE, INC.<br>
                ACCOUNT NUMBER : CA#1811-0005-54</b><br> 
            (2)Fax to Assumption College Tel # 817-78-93 your deposit slip after payment for recording and confirmation.<br>
            (3)You may also pay using your credit card or EPS card at the Assumption College Treasurer's Office.<br>
            NOTE:FAMILY COUNCIL FEE TO BE ADDED TO THE ABOVE PAYMENT SCHEDULE.

        </span>    
    </body>
</html>
