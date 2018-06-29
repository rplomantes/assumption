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
    .table, .th, .td {
        border: 1px solid black;
        border-collapse: collapse;
        font: 9pt;
    }
    .table2 {
        border: 1px solid black transparent;
        border-collapse: collapse;
        font: 9pt;
    }
    .underline {
        border-top: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
    }
    .top-line {
        border-bottom: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
        text-align: center;
    }
    .no-border {
        border-top: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
        border-bottom: 1px solid transparent;
    }

</style>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>DAILY ENROLLMENT STATISTICS</b><br><b>{{$date_start}}  - {{$date_end}}</b></div>
</div>
<div>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 155px;'>
        <thead>
            <tr>
                <th width="70%" style="text-align: center;">Program</th>
                <th style="text-align: center;">1st</th>
                <th style="text-align: center;">2nd</th>
                <th style="text-align: center;">3rd</th>
                <th style="text-align: center;">4th</th>
                <th style="text-align: center;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalcount1 = 0;
            $totalcount2 = 0;
            $totalcount3 = 0;
            $totalcount4 = 0;
            $totalunofficial1 = 0;
            $totalunofficial2 = 0;
            $totalunofficial3 = 0;
            $totalunofficial4 = 0;
            ?>
            @foreach ($academic_programs as $academic_program)
            <tr>
                <td>{{$academic_program->program_name}}</td>
                <td><?php $count1 = \App\CollegeLevel::where('program_code', $academic_program->program_code)->where('is_audit', 0)->where('status', 3)->where('level', "1st Year")->where('date_enrolled', '>=', $date_start)->where('date_enrolled', '<=', $date_end)->get(); ?>{{count($count1)}}</td>
                <td><?php $count2 = \App\CollegeLevel::where('program_code', $academic_program->program_code)->where('is_audit', 0)->where('status', 3)->where('level', "2nd Year")->where('date_enrolled', '>=', $date_start)->where('date_enrolled', '<=', $date_end)->get(); ?>{{count($count2)}}</td>
                <td><?php $count3 = \App\CollegeLevel::where('program_code', $academic_program->program_code)->where('is_audit', 0)->where('status', 3)->where('level', "3rd Year")->where('date_enrolled', '>=', $date_start)->where('date_enrolled', '<=', $date_end)->get(); ?>{{count($count3)}}</td>
                <td><?php $count4 = \App\CollegeLevel::where('program_code', $academic_program->program_code)->where('is_audit', 0)->where('status', 3)->where('level', "4th Year")->where('date_enrolled', '>=', $date_start)->where('date_enrolled', '<=', $date_end)->get(); ?>{{count($count4)}}</td>
                <td><?php $totalcount = count($count1) + count($count2) + count($count3) + count($count4); ?>{{$totalcount}}</td>
            </tr>
            <?php
            $totalcount1 = $totalcount1 + count($count1);
            $totalcount2 = $totalcount2 + count($count2);
            $totalcount3 = $totalcount3 + count($count3);
            $totalcount4 = $totalcount4 + count($count4);
            ?>
            <?php $unofficial1 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "1st Year")->where('date_registered', '>=', $date_start)->where('date_registered', '<=', $date_end)->get(); ?>
            <?php $unofficial2 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "2nd Year")->where('date_registered', '>=', $date_start)->where('date_registered', '<=', $date_end)->get(); ?>
            <?php $unofficial3 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "3rd Year")->where('date_registered', '>=', $date_start)->where('date_registered', '<=', $date_end)->get(); ?>
            <?php $unofficial4 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "4th Year")->where('date_registered', '>=', $date_start)->where('date_registered', '<=', $date_end)->get(); ?>

            <?php
            $totalunofficial1 = $totalunofficial1 + count($unofficial1);
            $totalunofficial2 = $totalunofficial2 + count($unofficial2);
            $totalunofficial3 = $totalunofficial3 + count($unofficial3);
            $totalunofficial4 = $totalunofficial4 + count($unofficial4);
            ?>
            @endforeach
            <tr>
                <td>AUDIT</td>
                <td><?php $aud1 = \App\CollegeLevel::where('is_audit', 1)->where('status', 3)->where('level', "1st Year")->where('date_enrolled', '>=', $date_start)->where('date_enrolled', '<=', $date_end)->get(); ?>{{count($aud1)}}</td>
                <td><?php $aud2 = \App\CollegeLevel::where('is_audit', 1)->where('status', 3)->where('level', "2nd Year")->where('date_enrolled', '>=', $date_start)->where('date_enrolled', '<=', $date_end)->get(); ?>{{count($aud2)}}</td>
                <td><?php $aud3 = \App\CollegeLevel::where('is_audit', 1)->where('status', 3)->where('level', "3rd Year")->where('date_enrolled', '>=', $date_start)->where('date_enrolled', '<=', $date_end)->get(); ?>{{count($aud3)}}</td>
                <td><?php $aud4 = \App\CollegeLevel::where('is_audit', 1)->where('status', 3)->where('level', "4th Year")->where('date_enrolled', '>=', $date_start)->where('date_enrolled', '<=', $date_end)->get(); ?>{{count($aud4)}}</td>
                <td><?php $totalaud = count($aud1) + count($aud2) + count($aud3) + count($aud4); ?>{{$totalaud}}</td>
            </tr>
            <tr>
                <td><div align="right">TOTAL ENROLLED</div></td>
                <td style="text-align: center;">{{$totalcount1}}</td>
                <td style="text-align: center;">{{$totalcount2}}</td>
                <td style="text-align: center;">{{$totalcount3}}</td>
                <td style="text-align: center;">{{$totalcount4}}</td>
                <td style="text-align: center;"><?php $totalenrolled = $totalcount1 + $totalcount2 + $totalcount3 + $totalcount4; ?>{{$totalenrolled}}</td>
            </tr>
            <tr>
                <td><div align="right">TOTAL UNOFFICIALLY ENROLLED</div></td>
                <td style="text-align: center;">{{$totalunofficial1}}</td>
                <td style="text-align: center;">{{$totalunofficial2}}</td>
                <td style="text-align: center;">{{$totalunofficial3}}</td>
                <td style="text-align: center;">{{$totalunofficial4}}</td>
                <td style="text-align: center;"><?php $totalunofficial = $totalunofficial1 + $totalunofficial2 + $totalunofficial3 + $totalunofficial4; ?>{{$totalunofficial}}</td>
            </tr>
            <tr>
                <td><div align="right">GRAND TOTAL</div></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: center;">{{$totalenrolled + $totalunofficial + $totalaud}}</td>
            </tr>
        </tbody>
    </table>
</div>