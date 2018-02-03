<style>
    img {
        display: block;
        max-width:230px;
        max-height:95px;
        width: auto;
        height: auto;
    }
    #schoolname{
        font-size: 18pt; 
        font-weight: bolder;
    }
</style>
<style>    
    .thd, .tdd {
        border-collapse: collapse;
        border: 1px solid black;
    } 

    .tables, .tds, .ths {
        border-collapse: collapse;
        border: 1px solid black;

    }
    .page_break { 
        page-break-before: always;
    }
</style>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{url('/images','assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>REGISTRATION FORM</b><br><small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small></div>
</div>
<br>
<table class='table' width="100%"style='margin-top: 145px;'>
    <tr>
        <td width='15%'>Student No:</td>
        <td width="55%" style="border-bottom: 1pt solid black;"><b>{{strtoupper($user->idno)}}</b></td>
        <td><div align='left'>Date:</div></td>
        <td colspan="2" style="border-bottom: 1pt solid black;">{{$status->date_assessed}}</td>
    </tr>
    <tr>
        <td>Name:</td>
        <td width="55%" style="border-bottom: 1pt solid black;">{{mb_strtoupper($user->firstname, 'UTF-8')}} {{mb_strtoupper($user->middlename, 'UTF-8')}} {{mb_strtoupper($user->lastname, 'UTF-8')}} {{mb_strtoupper($user->extensionname, 'UTF-8')}}</td>
        <td><div align='left'>Status:</div></td>
        <td colspan="2" style="border-bottom: 1pt solid black;">@if ($status->status == 4) Enrolled @endif</td>
    </tr>
    <tr>
        <td>Course/Level:</td>
        <td colspan="4" style="border-bottom: 1pt solid black;">{{$status->program_code}} - {{$status->level}}</td>
    </tr>
</table>
<br><b>REGISTRATION</b><br>
<table class="tables"width="100%">
    <tr>
        <th class='ths' width="40%">Course</th>
        <th class='ths' width="30%">Schedule/Room</th>
        <th class='ths' width="20%">Instructor</th>
        <th class='ths' width="10%">Units/Hrs</th>
    </tr>
    <?php
    $total = 0;
    ?>
    @foreach ($grades as $grade)
    <tr  style='font-size:12px'>
        <td class='tds' style='font-size:12px' ><small>@if($status->academic_type!='Senior High School'){{$grade->course_code}}@endif {{$grade->course_name}} @if($grade->is_drop == 1) [DROPPED] @endif </small></td>

        @if ($status->academic_type!='Senior High School')
        <?php 
        $offering_ids = \App\CourseOffering::find($grade->course_offering_id);
        ?>
        <td class='tds' style='font-size:12px'>
            <?php
            $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->get(['time_start', 'time_end', 'room']);
            ?>
            @foreach ($schedule2s as $schedule2)
            <?php
            $days = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
            ?>
            @foreach ($days as $day){{$day->day}}@endforeach {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}} [{{$schedule2->room}}]<br>
            <!--{{$schedule2->day}} {{$schedule2->time_start}} - {{$schedule2->time_end}}<br>-->
            @endforeach
        </td>
        <td class='tds' style='font-size:12px'>
            <?php
                $offering_id = \App\CourseOffering::find($grade->course_offering_id);
                    $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_id->schedule_id)->get(['instructor_id']);

                    foreach($schedule_instructor as $get){
                        if ($get->instructor_id != NULL){
                            $instructor = \App\User::where('idno', $get->instructor_id)->first();
                            echo "$instructor->firstname $instructor->lastname $instructor->extensionname";
                        } else {
                        echo "";
                        }
                    }
                ?>
        </td>
        @else
        <td class='tds' style='font-size:12px'></td>
        <td class='tds' style='font-size:12px'></td>
        @endif

        <td class='tds' align='center'>
            @if($status->academic_type!='Senior High School')
                <?php if($grade->is_drop == 1){ $total = $total; } else { $total = $total + $grade->lec + $grade->lab; $sum = $grade->lec + $grade->lab; echo "$sum"; }?>
            @else
                <?php if($grade->is_drop == 1){ $total = $total; } else { $total = $total + $grade->hours; echo "$grade->hours"; }?>
            @endif
        </td>
    </tr>
    @endforeach
    <tr>
        <th class='tds' colspan="3">Total Units/Hrs</th>
        <th class='ths'align='center'>{{$total}}</th>
    </tr>
</table>
<br>
<?php
$tfee = 0;
$ofee = 0;
$dfee = 0;
$srffee = 0;
$esc = 0;
$oaccounts = \App\Ledger::where('idno', $status->idno)->where('school_year', $y->school_year)->where('period', $y->period)->where('category_switch', 7)->get();
$tfs = \App\Ledger::where('idno', $status->idno)->where('school_year', $y->school_year)->where('period', $y->period)->where('category_switch', 5)->get();
foreach ($tfs as $tf) {
    $tfee = $tfee + $tf->amount;
}
$ofs = \App\Ledger::where('idno', $status->idno)->where('school_year', $y->school_year)->where('period', $y->period)->where('category_switch', '>=',1)->where('category_switch', '<=',3)->get();
foreach ($ofs as $of) {
    $ofee = $ofee + $of->amount;
}
$srfs = \App\Ledger::where('idno', $status->idno)->where('school_year', $y->school_year)->where('period', $y->period)->where('category_switch', 4)->get();
foreach ($srfs as $srf) {
    $srffee = $srffee + $srf->amount;
}
$discounts = \App\Ledger::where('idno', $status->idno)->where('school_year', $y->school_year)->where('period', $y->period)->get();
foreach ($discounts as $discount) {
    $dfee = $dfee + ($discount->discount);
    $esc = $esc + $discount->esc;
}
?>
<div>
    <table width = "100%" style="float:left">
        <tr>
            <td colspan="3"><b>TUITION FEE</b></td>
        </tr>
        <tr>
            <td>Tuition Fee</td>
            <td>:</td>
            <td style="border-bottom: 1pt solid black;">Php {{number_format($tfee,2)}}</td>
        </tr>
        <tr>
            <td>Other Fee (Other Fees, Miscellaneous Fees, Depository Fees )</td>
            <td>:</td>
            <td style="border-bottom: 1pt solid black;">Php {{number_format($ofee,2)}}</td>
        </tr>
        <tr>
            <td>Subject Related Fee</td>
            <td>:</td>
            <td style="border-bottom: 1pt solid black;">Php {{number_format($srffee,2)}}</td>
        </tr>
        <tr>
            <td>Discounts</td>
            <td>:</td>
            <td style="border-bottom: 1pt solid black;">(Php {{number_format($dfee,2)}})</td>
        </tr>
        @if($status->academic_type=="Senior High School")
        <tr>
            <td>Voucher</td>
            <td>:</td>
            <td style="border-bottom: 1pt solid black;">(Php {{number_format($esc,2)}})</td>
        </tr>
        @endif
        <tr>
            <td>Total Tuition Fee</td>
            <td>:</td>
            <td style="border-bottom: 1pt solid black;">Php {{number_format((($srffee+$tfee+$ofee)-$dfee)-$esc,2)}}</td>
        </tr>
        @if (count($ledger_due_dates)>0)
        <tr>
            <td><strong>Upon Enrollment</strong></td>
            <td><strong>:</strong></td>
            <td style="border-bottom: 1pt solid black;"><strong>Php {{number_format($downpayment->amount,2)}}</strong></td>
        </tr>
        @endif
    </table>
    
        @if(count($oaccounts)>0)
    <table width="50%" style="float:left">
        <tr>
            <td colspan="3"><b>OTHER PAYMENTS</b></td>
        </tr>
        <?php $totalotherpayments = 0; ?>
        @foreach ($oaccounts as $oaccount)
        <?php $totalotherpayments = $oaccount->amount + $totalotherpayments; ?>
        <tr>
            <td>{{$oaccount->description}}</td>
            <td>:</td>
            <td style="border-bottom: 1pt solid black;">Php {{number_format($oaccount->amount,2)}}</td>
        </tr>
        @endforeach
        <tr><td><strong>Total Other Payments</strong></td><td><strong>:</strong></td><td style="border-bottom: 1pt solid black;"><strong>Php {{number_format($totalotherpayments,2)}}</strong></td></tr>
    </table>
        @endif
</div>

@if (count($ledger_due_dates)>0)
<table class='tables' width='100%' style="clear:both">
    <tr>
        <th class='ths'>Due Date</th>
        <th class='ths'>Amount</th>
        <td width="50%" rowspan="{{count($ledger_due_dates)+1}}">
            <i><small><center>*For installment basis, please pay the appropriate amounts on or before the stated due dates to avoid late payment charges. Thank you!</center></small></i>
        </td>
    </tr>
    @foreach ($ledger_due_dates as $ledger_due_date)
    <tr>
        <td class='tds'>{{ date ('D, M d, Y', strtotime($ledger_due_date->due_date))}}</td>
        <td class='tds'>Php {{number_format($ledger_due_date->amount,2)}}</td>
    </tr>
    @endforeach
</table>
@else
<table class="tables" width="100%" style="clear:both">
    <tr>
        <th class="ths">Date</th>
        <th class="ths">Description</th>
        <th class="ths">Amount</th>
    </tr>
    <tr>
        <td class='tds'>{{ date ('D, M d, Y', strtotime($downpayment->due_date))}}</td>
        <td class="tds">Full Payment</td>
        <td class='tds'>Php {{number_format($downpayment->amount,2)}}</td>
    </tr>
</table>
@endif
<br>
<table width="100%">
    <tr>
        <td rowspan="2" class="tdd" width="50%">
    <center>I have read and fully understood the STUDENT PLEDGE AND DECLARATION set forth on the other side of this registration form.</center><br>
    <center>____________________________<br><strong>{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}} {{$user->extensionname}}</strong></center>
</td>
<td class="tdd" width="50%">
<center>Processed by:<br><br>____________________________<br><strong>{{Auth::user()->firstname}} {{Auth::user()->lastname}}</strong></center></td>
</tr>
<tr>
    <td class="tdd">
<center>Approved by:<br><br>
    ____________________________<br><strong>NAME</strong><br><small>School Registrar</small>
</center>
</td>
</tr>
</table>
<small><i>NOTE: This form is not valid until payment has been made in the cashier.
        <br>Validity period: 10 days from the date of assessment.</i></small>

<div class="page_break">
    <div style="text-align: justify">
        <div align="center"><strong>STUDENT'S PLEDGE AND DECLARATION</strong></div>
        <ol>
            <li>In consideration of my admission to the Assumption College, I hereby promise and pledge to abide by and comply with all the rules and regulations laid down by competent authority in the School in which I am enrolled.</li>
            <li>I am fully aware of the School policy to expel, exclude or suspend indefinitely, after summary investigation, any student found to have committed major offenses as specified in the Assumption College Student Handbook as well as those issued from time to time by the competent authority in this School.</li>
            <li>I am fully aware that the assessment of fees stated in this registration form are still subject to audit and will be adjusted accordingly.</li>
            <li>I am fully aware that in order to avail cash basis of my tuition fees, total amount for the year must be already paid in advance. (Due date stipulated in the front page)</li>
            <li>I am fully aware that my enrollment is on a semestral basis only. And when this enrollment application is withdrawn before the start of classes, a 5% of the total amount due for the school terms is to be charged. Moreover, for a student who withdrawn or transfers after enrollment period the following refund and charges shall apply:</li>
        </ol>

        <div align='center'><i>For Higher Education Programs</i></div>
        <ol type='A'>
            <li>10% of the total amount due for the school term shall not be refundable if the student officially drops within the first week of classes whether or not he has actually attended classes.</li>
            <li>20% of the total amount due for the school term shall not be refundable if the student officially drops within the second week of classes whether or not he has actually attended classes.</li>
        </ol>
        <div align='center'>****************************************************</div>
    </div>
</div>
