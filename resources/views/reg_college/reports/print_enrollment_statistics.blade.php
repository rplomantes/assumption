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
    <div style='float: left; margin-left: 150px;'><img src="{{url('/images','assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>ENROLLMENT STATISTICS</b></div>
</div>
<div>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 145px;'>
        <thead>
            <tr>
                <th width="70%">Program</th>
                <th>1st</th>
                <th>2nd</th>
                <th>3rd</th>
                <th>4th</th>
                <th>Total</th>
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
                <td><?php $count1 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 3)->where('level', "1st Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count1)}}</td>
                <td><?php $count2 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 3)->where('level', "2nd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count2)}}</td>
                <td><?php $count3 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 3)->where('level', "3rd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count3)}}</td>
                <td><?php $count4 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 3)->where('level', "4th Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count4)}}</td>
                <td><?php $totalcount = count($count1) + count($count2) + count($count3) + count($count4); ?>{{$totalcount}}</td>
            </tr>
            <?php
            $totalcount1 = $totalcount1 + count($count1);
            $totalcount2 = $totalcount2 + count($count2);
            $totalcount3 = $totalcount3 + count($count3);
            $totalcount4 = $totalcount4 + count($count4);
            ?>
            <?php $unofficial1 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "1st Year")->get(); ?>
            <?php $unofficial2 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "2nd Year")->get(); ?>
            <?php $unofficial3 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "3rd Year")->get(); ?>
            <?php $unofficial4 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "4th Year")->get(); ?>

            <?php
            $totalunofficial1 = $totalunofficial1 + count($unofficial1);
            $totalunofficial2 = $totalunofficial2 + count($unofficial2);
            $totalunofficial3 = $totalunofficial3 + count($unofficial3);
            $totalunofficial4 = $totalunofficial4 + count($unofficial4);
            ?>
            @endforeach
            <tr>
                <td><div align="right">TOTAL ENROLLED</div></td>
                <td>{{$totalcount1}}</td>
                <td>{{$totalcount2}}</td>
                <td>{{$totalcount3}}</td>
                <td>{{$totalcount4}}</td>
                <td><?php $totalenrolled = $totalcount1 + $totalcount2 + $totalcount3 + $totalcount4; ?>{{$totalenrolled}}</td>
            </tr>
            <tr>
                <td><div align="right">TOTAL UNOFFICIALLY ENROLLED</div></td>
                <td>{{$totalunofficial1}}</td>
                <td>{{$totalunofficial2}}</td>
                <td>{{$totalunofficial3}}</td>
                <td>{{$totalunofficial4}}</td>
                <td><?php $totalunofficial = $totalunofficial1 + $totalunofficial2 + $totalunofficial3 + $totalunofficial4; ?>{{$totalunofficial}}</td>
            </tr>
            <tr>
                <td><div align="right">GRAND TOTAL</div></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$totalenrolled + $totalunofficial}}</td>
            </tr>
        </tbody>
    </table>
</div>