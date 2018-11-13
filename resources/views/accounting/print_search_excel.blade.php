<table>
    <tr><td colspan="4"><strong>Assumption College</strong></td></tr>
    <tr><td colspan="4">Student List {{$school_years}}</td></tr>
    <tr><td colspan="4"><h4>A.Y.: @if($school_years == "all") All Years @else{{$school_years}}-{{$school_years+1}}@endif - @if($periods == "all") All Periods @else{{$periods}} @endif</h4></td></tr>
    <tr><td colspan="4">Level: @if($levels == "all") All Levels @else{{$levels}}@endif</td></tr>
</table>
    
<table>
    <thead>
        <tr>
            <th width='4%' align='center'>#</th>
            <th width='20%'>ID Number</th>
            <th width='50%'>Student Name</th>
            <th>Plan</th>
        </tr>
    </thead>
        <tbody>
            <?php $counter = 0; ?>
            @foreach ($lists as $list)
            <?php $counter = $counter + 1; ?>
            <?php $user = \App\User::where('idno', $list->idno)->first(); ?>
            <?php $student_info = \App\StudentInfo::where('idno', $list->idno)->first(); ?>
            <?php $status = \App\Status::where('id', $list->id)->first(); ?>
            <tr>
                <td align='center'>{{$counter}}</td>
                <td>{{$list->idno}}</td>
                <td>{{$user->lastname}}, {{$user->firstname}}</td>
                <td>{{$status->type_of_plan}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
<br><br>

Prepared by:<br><br>
<strong>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</strong>
</div>