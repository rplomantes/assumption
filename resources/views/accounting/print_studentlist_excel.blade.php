@if(count($lists)>0)
<?php $total=0; $x = 0?>
<table>
    <tr><td><strong colspan="4">Assumption College</strong></td></tr>
    <tr><td colspan="4">{{$department}}</td></tr>
    <tr></tr>
    <tr><td colspan="4">Student List</tr>
    <tr></tr>
    @foreach($heads as $head)
    <?php $x = 0;?>
    <tr><td colspan="6"><h4>{{$head->level}}</h4></td></tr>
    <thead>
        <tr>
            <th style='border-bottom: 1px solid black'>  </th>
            <th style='border-bottom: 1px solid black'>ID No.</th>
            <th style='border-bottom: 1px solid black'>Name</th>
            @if($department == "College Department")
            <th style='border-bottom: 1px solid black'>Course</th>
            @endif
            <th style='border-bottom: 1px solid black' align='center'>Level</th>
            @if($department != "College Department")
            <th style='border-bottom: 1px solid black' align='center'>Section</th>
            @endif
            <th style='border-bottom: 1px solid black' align='center'>Plan</th>
            <th style='border-bottom: 1px solid black' align='right'>Assessment</th>
        </tr>
    </thead>
    <tbody>
            @foreach($lists as $list)
                @if($list->level == $head->level)
                <?php $total += $list->assessment; $x++; ?>
                <tr>
                    <td>{{$x}}  </td>
                    <td align='left'>{{$list->idno}}</td>
                    <td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}} {{$list->extensionname}}</td>
                    @if($department == "College Department")
                    <td>{{$list->program_code}}</th>
                        @endif
                    <td align='center'>{{$list->level}}</td>
                    @if($department != "College Department")
                    <td align='center'>{{$list->section}}</td>
                    @endif
                    <td align='center'>{{$list->type_of_plan}}</td>
                    <td align='right'>{{$list->assessment}}</td>
                </tr>
                @endif
            @endforeach
            <tr><td align="right" colspan="6">SUB TOTAL</td><td align="right"><strong>{{$head->total}}</strong></td></tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6" style='border-top: 1px solid black'>GRAND TOTAL</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{$total}}</strong></td>
        </tr>
    </tfoot>
</table>
@else
@endif