<h4>Student List Report</h4>
<h4>S.Y. {{$school_year}}-{{$school_year+1}}</h4>

@foreach ($levels as $level)
<table width='100%' cellpadding='0' cellspacing='0'>
    <tr>
        <th width="5"></th>
        <th colspan="2">{{$level}}</th>
    </tr>
    @if($level == "Grade 11" or $level == "Grade 12")
    <?php $list_levels = \App\BedLevel::distinct()->join('users', 'users.idno', '=', 'bed_levels.idno')->where('level', $level)->where('bed_levels.status', env('ENROLLED'))->where('school_year', $school_year)->where('period', $period)->where('department', 'Senior High School')->orderBy('users.lastname', 'asc')->get(); ?>
    @elseif($level == "1st Year" or $level == "2nd Year" or $level == "3rd Year" or $level == "4th Year")
    <?php $list_levels = \App\CollegeLevel::join('users', 'users.idno', '=', 'college_levels.idno')->where('level', $level)->where('college_levels.status', env('ENROLLED'))->where('school_year', $school_year)->where('period', $period)->orderBy('users.lastname', 'asc')->get(); ?>                    
    @else
    <?php $list_levels = \App\BedLevel::join('users', 'users.idno', '=', 'bed_levels.idno')->where('level', $level)->where('bed_levels.status', env('ENROLLED'))->where('school_year', $school_year)->where('department', '!=', 'SHS')->orderBy('users.lastname', 'asc')->get(); ?>
    @endif
    <?php $counter = 1; ?>
    @foreach($list_levels as $list_level)
    <tr>
        <td>{{$counter++}}.</td>
        <td>{{$list_level->idno}}</td>
        <td>{{$list_level->getFullNameAttribute()}}</td>
    </tr>
    @endforeach
</table>
@endforeach
<table width='100%' cellpadding='0' cellspacing='0'>
    <tr><th colspan="2">Summary per Level</th></tr>
    @foreach ($levels as $level)
        <tr>
            <th width="5"></th>
                @if($level == "Grade 11" or $level == "Grade 12")
                <?php $list_levels = \App\BedLevel::join('users', 'users.idno', '=', 'bed_levels.idno')->where('level', $level)->where('bed_levels.status', env('ENROLLED'))->where('school_year', $school_year)->where('period', $period)->where('department', 'Senior High School')->orderBy('users.lastname', 'asc')->get(); ?>
                @elseif($level == "1st Year" or $level == "2nd Year" or $level == "3rd Year" or $level == "4th Year")
                <?php $list_levels = \App\CollegeLevel::join('users', 'users.idno', '=', 'college_levels.idno')->where('level', $level)->where('college_levels.status', env('ENROLLED'))->where('school_year', $school_year)->where('period', $period)->orderBy('users.lastname', 'asc')->get(); ?>                    
                @elseif($level == "Pre-Kinder" or $level == "Kinder" or $level == "Grade 1" or $level == "Grade 2" or $level == "Grade 3" or $level == "Grade 4" or $level == "Grade 5" or $level == "Grade 6" or $level == "Grade 7" or $level == "Grade 8" or $level == "Grade 9" or $level == "Grade 10")
                <?php $list_levels = \App\BedLevel::join('users', 'users.idno', '=', 'bed_levels.idno')->where('level', $level)->where('bed_levels.status', env('ENROLLED'))->where('school_year', $school_year)->where('department', '!=', 'SHS')->orderBy('users.lastname', 'asc')->get(); ?>
                @endif
            <td>{{$level}}</td>
            <td align="right">{{count($list_levels)}}</td>
        </tr>
    @endforeach
<?php 
$count_pre_school = \App\BedLevel::where('bed_levels.status', env('ENROLLED'))->where('school_year', $school_year)->where('department', 'Pre School')->get();
$count_elementary = \App\BedLevel::where('bed_levels.status', env('ENROLLED'))->where('school_year', $school_year)->where('department', 'Elementary')->get();
$count_junior = \App\BedLevel::where('bed_levels.status', env('ENROLLED'))->where('school_year', $school_year)->where('department', 'Junior High School')->get();
$count_senior = \App\BedLevel::where('bed_levels.status', env('ENROLLED'))->where('school_year', $school_year)->where('period', $period)->where('department', 'Senior High School')->get();
$count_college = \App\CollegeLevel::where('college_levels.status', env('ENROLLED'))->where('school_year', $school_year)->where('period', $period)->get();
?>
    <tr><th colspan="2">Summary per Dapartment</th></tr>
    <tr>
            <th width="5"></th>
        <td>Pre-School</td><td align="right">{{count($count_pre_school)}}</td>
    </tr>
    <tr>
            <th width="5"></th>
        <td>Elementary</td><td align="right">{{count($count_elementary)}}</td>
    </tr>
    <tr>
            <th width="5"></th>
        <td>Junior High School</td><td align="right">{{count($count_junior)}}</td>
    </tr>
    <tr>
            <th width="5"></th>
        <td>Senior High School</td><td align="right">{{count($count_senior)}}</td>
    </tr>
    <tr>
            <th width="5"></th>
        <td>College Department</td><td align="right">{{count($count_college)}}</td>
    </tr>
    <tr>
            <th width="5"></th>
        <th>Grand Total</th><td align="right"><strong>{{count($count_pre_school)+count($count_elementary)+count($count_junior)+count($count_senior)+count($count_college)}}</strong></td>
    </tr>
</table>