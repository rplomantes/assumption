<?php

function get_name($idno) {
    $names = \App\User::where('idno', $idno)->first();
    return $names->lastname . ", " . $names->firstname . " " . $names->middlename;
}

$i = 1;
$y = 1;
?>

<h3>Assumption College</h3>
<h4>Conduct Grade Summary</h4>
<div>Level : {{$level}}</div>
@if($level=="Grade 11" || $level=="Grade 12")
<div>Strand : {{$strand}}</div>
@endif
@if($section=="All")
<table border="1" class="table table-responsive table-striped">
    <tr><th>#</th><th>Student ID</th><th>Student Name</th><th>Section</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td><td>{{$name->section}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif

</table>
@else

<div>Section : {{$section}}</div>

<table border="1" cellpadding="5" cellspacing="0" class="table table-responsive table-striped table-bordered">
    <tr><th>#</th><th>Student ID</th><th>Student Name</th>
        <td align="center"><strong>1st Sem<br>MidTerm</strong></td>
        <td align="center"><strong>1st Sem<br>Finals</strong></td>
        <td align="center"><strong>2nd Sem<br>Midterm</strong></strong></td>
        <td align="center"><strong>2nd Sem<br>Finals</strong></strong></td>
        <td align="center"><strong>Final Average</strong></td></tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td>
        <td align="center">{{$first=getGrades('1stQTR',$name->idno,$schoolyear,$period,$level,'letter')}}</td>
        <td align="center">{{$second=getGrades('2ndQTR',$name->idno,$schoolyear,$period,$level,'letter')}}</td>
        <td align="center">{{$third=getGrades('3rdQTR',$name->idno,$schoolyear,$period,$level,'letter')}}</td>
        <td></td>
        <td align="center">{{$final_letter_grade = \App\CtrAlpha::where('equivalent', round((getAlpha($first)+getAlpha($second)+getAlpha($third))/3))->first()->alpha}}</td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif
</table>
<div class ="form form-group">
    <a href="javascript:void(0)" onclick = "print_now()" class="form btn btn-primary">Print</a>
</div>
@endif

<?php

function getGrades($qtr, $idno, $schoolyear, $period, $level, $type) {
    if ($qtr == "2ndQTR" || $qtr == "1stQTR") {
        $per = "1st Semester";
    } else {
        $per = "2nd Semester";
    }
    $get_grades = \App\GradeBasicEd::where('is_alpha', 1)
                    ->where('period', $per)
                    ->where('idno', $idno)
                    ->where('school_year', $schoolyear)
                    ->where(function ($query) {
                        $query->where('level', "Grade 11")
                        ->orWhere('level', "Grade 12");
                    })->first();

////////////
    if (count($get_grades) == 0) {
        if ($qtr == "2ndQTR" || $qtr == "1stQTR") {
            $per = "2nd Semester";
        } else {
            $per = "1st Semester";
        }
        $get_grades = \App\GradeBasicEd::where('is_alpha', 1)
                        ->where('period', $per)
                        ->where('idno', $idno)
                        ->where('school_year', $schoolyear)
                        ->where(function ($query) {
                            $query->where('level', "Grade 11")
                            ->orWhere('level', "Grade 12");
                        })->first();
    }


/////////////////
    if ($qtr == "1stQTR") {
        if ($type == 'letter') {
            return $get_grades['first_grading_letter'];
        }
    } elseif ($qtr == "2ndQTR") {
        if ($type == 'letter') {
            return $get_grades['second_grading_letter'];
        }
    } else {
        if ($type == 'letter') {
            return $get_grades['third_grading_letter'];
        }
    }
}

function getAlpha($grade) {
    if ($grade == NULL or $grade == "") {
        return 0;
    } else {
        $getgrade = \App\CtrAlpha::where('alpha', $grade)->first();
        return $getgrade->equivalent;
    }
}
?>