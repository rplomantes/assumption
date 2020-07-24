<?php
$ledger = \App\Ledger::SelectRaw('category,category_switch, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('school_year', $school_year)->where('period', $period)->where('idno',$user->idno)->groupBy('category_switch','category')->orderBy('category_switch')->get();
$totaldm=0;
?>
@foreach ($ledger as $main)
<?php
$totaldm=$totaldm+$main->debit_memo;
?>
@endforeach
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
    .bottomline {
        border-bottom: 1px solid black;
    }
    .bottomline-right {
        text-align: right;
    }
    #registration {
        border-collapse: collapse;
    }
    #registration, #reg {
        border: 1px solid black;
        text-align: center;
    }
    #pt {
        font-size: 9pt;
    }
    .page_break { 
        page-break-before: always;
    }
</style>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small>
        
        <div class='pull-right'><i>Student's Copy</i></div>
        <?php 
        $checkcollegelevels = \App\CollegeLevel::where('idno', $user->idno)->where('school_year', $school_year)->where('period', $period)->first();
            if (count($checkcollegelevels) > 0) {
                $status = \App\CollegeLevel::where('idno', $user->idno)->where('school_year', $school_year)->where('period', $period)->first();
            } else {
                $status = \App\Status::where('idno', $user->idno)->first();
            }
        ?>
        @if($status->status == 3)
        <b>SCHEDULE</b>
        @else
        <b>SCHEDULE</b>
        @endif        
        <br><small>A.Y. {{$school_year}} - {{$school_year+1}} {{$period}}</small>
    </div>
</div>
<br>
<table width="100%" style='margin-top: 145px; font-size: 9pt'>
    <tr>
        <td width="13%">Student No:</td>
        <td width="60%" class="bottomline"><b>{{strtoupper($user->idno)}}</b></td>
        <td width="7%">
            <div align='left'>Date:</div>
        </td>
        <td width="20%" class="bottomline">{{$status->date_registered}}</td>
    </tr>
    <tr>
        <td>Name:</td>
        <td class="bottomline">{{mb_strtoupper($user->firstname, 'UTF-8')}} {{mb_strtoupper($user->middlename, 'UTF-8')}} {{mb_strtoupper($user->lastname, 'UTF-8')}} {{mb_strtoupper($user->extensionname, 'UTF-8')}}</td>
        <td>
            <div align='left'>Nationality:</div>
        </td>
        <td class="bottomline">{{$student_info->nationality}}</td>
    </tr>
    <tr>
        <td>Course/Year:</td>
        <td class="bottomline" colspan="3">{{$status->program_name}} - {{$status->level}}</td>
    </tr>
    <tr>
        <td>Home Address:</td>
        <td class="bottomline" colspan="3">{{$student_info->street}} {{$student_info->barangay}} {{$student_info->municipality}} {{$student_info->province}} {{$student_info->zip}}</td>
    </tr>
</table>
<table id='registration' width="100%" style='margin-top: 5px; font-size: 9pt'>
    <tr>
        <th id='reg'>Course</th>
        <th id='reg'>Schedule</th>
        <th id="reg">Room</th>
        <th id='reg'>Lec</th>
        <th id='reg'>Lab</th>
    </tr>
    <?php
    $totallec = 0;
    $totallab = 0;
    ?>
    @foreach ($grades as $grade)
    <tr>
        <td id='reg'><small>@if($status->academic_type!='Senior High School'){{$grade->course_code}}@endif {{$grade->course_name}} @if($grade->is_drop == 1) [DROPPED] @endif </small></td>

                                    @if($grade->course_offering_id!=NULL)
        @if ($status->academic_type!='Senior High School')
        <?php
        $offering_ids = \App\CourseOffering::find($grade->course_offering_id);
        ?>
        <td id='reg'>
            <?php
            $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->get(['time_start', 'time_end', 'room']);
            ?>
            @foreach ($schedule2s as $schedule2)
            <?php
            $days = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
            ?>
            @foreach ($days as $day)
            <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $offering_ids->schedule_id)->first()->is_tba; ?>
                                        @if ($is_tba == 0)
            {{$day->day}}
                                        @else
                                        
                                        @endif
            @endforeach
            <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $offering_ids->schedule_id)->first()->is_tba; ?>
                                        @if ($is_tba == 0)
                                        {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                                        @else
                                        
                                        @endif
            <!--{{$schedule2->day}} {{$schedule2->time_start}} - {{$schedule2->time_end}}<br>-->
            @endforeach
        </td>
<!--        <td id='reg'>
            <?php
            $offering_id = \App\CourseOffering::find($grade->course_offering_id);
            $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_id->schedule_id)->get(['instructor_id']);

            foreach ($schedule_instructor as $get) {
                if ($get->instructor_id != NULL) {
                    $instructor = \App\User::where('idno', $get->instructor_id)->first();
                   // echo "$instructor->firstname $instructor->lastname $instructor->extensionname";
                } else {
                    //echo "";
                }
            }
            ?>
        </td>-->
        <td id="reg">
            <?php
            $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->get(['time_start', 'time_end', 'room']);
            ?>
            @foreach ($schedule3s as $schedule3)
            
            <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $offering_ids->schedule_id)->first()->is_tba; ?>
                                        @if ($is_tba == 0)
            {{$schedule3->room}}<br>
                                        @else
                                        
                                        @endif
            
            
            @endforeach
        </td>
        @else
        <td id='reg'></td>
        <td id='reg'></td>
        @endif
        @else
        <td id='reg'></td>
        <td id='reg'></td>
        @endif
        <td id='reg'>
            <?php
            if ($grade->is_drop == 1) {
                $totallec = $totallec;
            } else {
                $totallec = $totallec + $grade->lec;
                if ($grade->lec == 0) {
                    echo "0";
                } else {
                    echo "$grade->lec";
                }
            }
            ?>
        </td>
        <td id='reg'>
            <?php
            if ($grade->is_drop == 1) {
                $totallab = $totallab;
            } else {
                $totallab = $totallab + $grade->lab;
                if ($grade->lab == 0) {
                    echo "0";
                } else {
                    echo "$grade->lab";
                }
            }
            ?>
        </td>
    </tr>
    @endforeach
    <tr>
        <th id='reg' colspan="3"></th>
        <th id='reg'>{{$totallec}}</th>
        <th id='reg'>{{$totallab}}</th>
    </tr>
</table>
<table width="100%" style="clear:both; font-size: 9pt">
    <tr>
        <td width="50%">Processed by:<br><br>____________________________<br><strong>{{Auth::user()->firstname}} {{Auth::user()->lastname}}</strong></td>
    </tr>
</table>

<hr>
    <div>    
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small>
        <div class='pull-right'><i>Registrar's Copy</i></div>
        @if($status->status == 3)
        <b>SCHEDULE</b>
        @else
        <b>SCHEDULE</b>
        @endif
        
        <br><small>A.Y. {{$school_year}} - {{$school_year+1}} {{$period}}</small>
    </div>
</div>
<br>
<table width="100%" style='margin-top: 145px; font-size: 9pt'>
    <tr>
        <td width="13%">Student No:</td>
        <td width="60%" class="bottomline"><b>{{strtoupper($user->idno)}}</b></td>
        <td width="7%">
            <div align='left'>Date:</div>
        </td>
        <td width="20%" class="bottomline">{{$status->date_registered}}</td>
    </tr>
    <tr>
        <td>Name:</td>
        <td class="bottomline">{{mb_strtoupper($user->firstname, 'UTF-8')}} {{mb_strtoupper($user->middlename, 'UTF-8')}} {{mb_strtoupper($user->lastname, 'UTF-8')}} {{mb_strtoupper($user->extensionname, 'UTF-8')}}</td>
        <td>
            <div align='left'>Nationality:</div>
        </td>
        <td class="bottomline">{{$student_info->nationality}}</td>
    </tr>
    <tr>
        <td>Course/Year:</td>
        <td class="bottomline" colspan="3">{{$status->program_name}} - {{$status->level}}</td>
    </tr>
    <tr>
        <td>Home Address:</td>
        <td class="bottomline" colspan="3">{{$student_info->street}} {{$student_info->barangay}} {{$student_info->municipality}} {{$student_info->province}} {{$student_info->zip}}</td>
    </tr>
</table>
<table id='registration' width="100%" style='margin-top: 5px; font-size: 9pt'>
    <tr>
        <th id='reg'>Course</th>
        <th id='reg'>Schedule</th>
        <th id="reg">Room</th>
        <th id='reg'>Lec</th>
        <th id='reg'>Lab</th>
    </tr>
    <?php
    $totallec = 0;
    $totallab = 0;
    ?>
    @foreach ($grades as $grade)
    <tr>
        <td id='reg'><small>@if($status->academic_type!='Senior High School'){{$grade->course_code}}@endif {{$grade->course_name}} @if($grade->is_drop == 1) [DROPPED] @endif </small></td>

                                    @if($grade->course_offering_id!=NULL)
        @if ($status->academic_type!='Senior High School')
        <?php
        $offering_ids = \App\CourseOffering::find($grade->course_offering_id);
        ?>
        <td id='reg'>
            <?php
            $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->get(['time_start', 'time_end', 'room']);
            ?>
            @foreach ($schedule2s as $schedule2)
            <?php
            $days = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
            ?>
            @foreach ($days as $day)
            <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $offering_ids->schedule_id)->first()->is_tba; ?>
                                        @if ($is_tba == 0)
            {{$day->day}}
                                        @else
                                        
                                        @endif
            @endforeach
            <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $offering_ids->schedule_id)->first()->is_tba; ?>
                                        @if ($is_tba == 0)
                                        {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                                        @else
                                        
                                        @endif
            <!--{{$schedule2->day}} {{$schedule2->time_start}} - {{$schedule2->time_end}}<br>-->
            @endforeach
        </td>
<!--        <td id='reg'>
            <?php
            $offering_id = \App\CourseOffering::find($grade->course_offering_id);
            $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_id->schedule_id)->get(['instructor_id']);

            foreach ($schedule_instructor as $get) {
                if ($get->instructor_id != NULL) {
                    $instructor = \App\User::where('idno', $get->instructor_id)->first();
                   // echo "$instructor->firstname $instructor->lastname $instructor->extensionname";
                } else {
                    //echo "";
                }
            }
            ?>
        </td>-->
        <td id="reg">
            <?php
            $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->get(['time_start', 'time_end', 'room']);
            ?>
            @foreach ($schedule3s as $schedule3)
            
            <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $offering_ids->schedule_id)->first()->is_tba; ?>
                                        @if ($is_tba == 0)
            {{$schedule3->room}}<br>
                                        @else
                                        
                                        @endif
            
            
            @endforeach
        </td>
        @else
        <td id='reg'></td>
        <td id='reg'></td>
        @endif
        @else
        <td id='reg'></td>
        <td id='reg'></td>
        @endif
        <td id='reg'>
            <?php
            if ($grade->is_drop == 1) {
                $totallec = $totallec;
            } else {
                $totallec = $totallec + $grade->lec;
                if ($grade->lec == 0) {
                    echo "0";
                } else {
                    echo "$grade->lec";
                }
            }
            ?>
        </td>
        <td id='reg'>
            <?php
            if ($grade->is_drop == 1) {
                $totallab = $totallab;
            } else {
                $totallab = $totallab + $grade->lab;
                if ($grade->lab == 0) {
                    echo "0";
                } else {
                    echo "$grade->lab";
                }
            }
            ?>
        </td>
    </tr>
    @endforeach
    <tr>
        <th id='reg' colspan="3"></th>
        <th id='reg'>{{$totallec}}</th>
        <th id='reg'>{{$totallab}}</th>
    </tr>
</table>
<table width="100%" style="clear:both; font-size: 9pt">
    <tr>
        <td width="50%">Processed by:<br><br>____________________________<br><strong>{{Auth::user()->firstname}} {{Auth::user()->lastname}}</strong></td>
    </tr>
</table>

    