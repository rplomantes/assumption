<?php

function get_name($idno) {
    $names = \App\User::where('idno', $idno)->first();
    return $names->lastname . ", " . $names->firstname . " " . $names->middlename;
}

$i = 1;
$y = 1;
?>

<h3>Assumption College</h3>
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

<table border="1" class="table table-responsive table-striped table-bordered">
    <tr><th>#</th><th>Student ID</th><th>Student Name</th><td colspan="2" align="center"><strong>1st Sem</strong></td><td colspan="2" align="center"><strong>2nd Sem</strong></td><td colspan="2" align="center"><strong>Final Average</strong></td></tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td>
        <td align="center">{{getGrades('2ndQTR',$name->idno,$schoolyear,$period,$level,'letter')}}</td>
        <td align="center">{{$first=getGrades('2ndQTR',$name->idno,$schoolyear,$period,$level,'number')}}</td>
        <td align="center">{{getGrades('3rdQTR',$name->idno,$schoolyear,$period,$level,'letter')}}</td>
        <td align="center">{{$second=getGrades('3rdQTR',$name->idno,$schoolyear,$period,$level,'number')}}</td>
        <td align="center">{{getTransmu(($first+$second)/2)}}</td>
        <td align="center">{{round(($first+$second)/2,2)}}</td>
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
    if ($qtr == "2ndQTR") {
        $per = "1st Semester";
    } else {
        $per = "2nd Semester";
    }
    $get_grades = \App\GradeBasicEd::where('subject_name', 'like', 'Student Activities%')
                    ->where('period', $per)
                    ->where('idno', $idno)
                    ->where('school_year', $schoolyear)
                    ->where(function ($query) {
                        $query->where('level', "Grade 11")
                        ->orWhere('level', "Grade 12");
                    })->first();

////////////
    if (count($get_grades) == 0) {
        if ($qtr == "2ndQTR") {
            $per = "2nd Semester";
        } else {
            $per = "1st Semester";
        }
        $get_grades = \App\GradeBasicEd::where('subject_name', 'like', 'Student Activities%')
                        ->where('period', $per)
                        ->where('idno', $idno)
                        ->where('school_year', $schoolyear)
                        ->where(function ($query) {
                            $query->where('level', "Grade 11")
                            ->orWhere('level', "Grade 12");
                        })->first();
    }


/////////////////
    if ($qtr == "2ndQTR") {
        if ($type == 'number') {
            return $get_grades['second_grading'];
        } else {
            return getTransmu($get_grades['second_grading']);
        }
    } else {
        if ($type == 'number') {
            return $get_grades['third_grading'];
        } else {
            return getTransmu($get_grades['third_grading']);
        }
    }
}

function getTransmu($getAverage) {
    $letter_grade = \App\CtrTransmuLetter::where('grade', round($getAverage))->where('letter_grade_type', 'SAC')->first();
    return $letter_grade['letter_grade'];
}
?>