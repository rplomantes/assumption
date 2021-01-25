<?php
if ($department == "College Department") {
    $records = \App\CollegeLevel::where('school_year', $school_year)->where('period', $period)->orderBy('level')->orderBy('type_of_plan')->get();
} elseif ($department == "Senior High School") {
    $records = \App\BedLevel::where('department', $department)->where('school_year', $school_year)->where('period', $period)->orderBy('level')->orderBy('type_of_plan')->get();
} else {
    $records = \App\BedLevel::where('department', $department)->where('school_year', $school_year)->orderBy('level')->orderBy('type_of_plan')->get();
}
?>
<table> 
    <tr><td><strong colspan="4">Assumption College</strong></td></tr>
    <tr><td colspan="4">{{$department}}</td></tr>
    <tr></tr>
    <tr><td colspan="4">Payment Plans</tr>
    <tr></tr>
</table>
<table class="table  table-condensed">
    <tr>
        <td align='center'>Level</td>
        <td align='center'>No. of Students</td>
        <td align='center'>A</td>
        <td align='center'>B</td>
        <td align='center'>C</td>
        <td align='center'>D</td>
    </tr>
    @foreach($records->groupBy('level') as $conRecord=>$levels)
    <tr>
        <td align='center'>{{$conRecord}}</td>
        <td align='center'>{{count($levels)}}</td>
        @foreach($levels->groupBy('type_of_plan') as $planRecord=>$plan)
        <td align='center'>{{count($plan)}}</td>
        @endforeach
    </tr>
    @endforeach
    <tr>
        <td align='center'><strong>Total</strong></td>
        <td align='center'><strong>{{count($records)}}</strong></td>
        <td align='center'><strong>{{count($records->where('type_of_plan', 'Plan A'))}}</strong></td>
        <td align='center'><strong>{{count($records->where('type_of_plan', 'Plan B'))}}</strong></td>
        <td align='center'><strong>{{count($records->where('type_of_plan', 'Plan C'))}}</strong></td>
        <td align='center'><strong>{{count($records->where('type_of_plan', 'Plan D'))}}</strong></td>
    </tr>
</table>