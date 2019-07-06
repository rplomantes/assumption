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
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>STUDENT LIST</b></div>
</div>
<div>
   <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 145px;'>
        <tr>
            <td class='no-border td' width='4%'>A.Y.:</td>
            <td class='underline td' width='20%'>@if($school_years == "all") All Years @else{{$school_years}}-{{$school_years+1}}@endif - @if($periods == "all") All Periods @else{{$periods}} @endif</td>
            <td class='no-border td' width='12%'>Year & Program:</td>
            <td class='underline td'>@if($levels == "all") All Levels @else{{$levels}}@endif - @if($program_codes == "all") All Programs @else <?php $program_name = \App\CtrAcademicProgram::where('program_code', $program_codes)->first()->program_name; ?> {{$program_name}}@endif</td>
        </tr>
    </table>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='3px' style='margin-top: 50px;'>
        <thead>
            <tr>
                <th width='3%' align='center'>#</th>
                <th width='15%'>ID Number</th>
                <th width='30%'>Student Name</th>
                <th>Program</th>
                <th>Level</th>
                @if(Auth::user()->accesslevel == env("ADMISSION_HED"))
                <th>Scholar</th>
                @endif
            </tr>
        </thead>
        <tbody>
            <?php $counter = 0; ?>
            @foreach ($lists as $list)
            <?php $counter = $counter + 1; ?>
            <?php $user = \App\User::where('idno', $list->idno)->first(); ?>
            <?php $student_info = \App\StudentInfo::where('idno', $list->idno)->first(); ?>
            <?php $status = \App\CollegeLevel::where('id', $list->id)->first(); ?>
            <tr>
                <td align='center'>{{$counter}}</td>
                <td>{{$list->idno}}</td>
                <td>{{$user->getFullNameAttribute()}}</td>
                <td>{{$status->program_code}}</td>
                <td>{{$status->level}}</td>
                @if(Auth::user()->accesslevel == env("ADMISSION_HED"))
                <?php $scholar = \App\CollegeScholarship::where('idno', $list->idno)->first(); ?>
                <td>{{$scholar->discount_description}}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>