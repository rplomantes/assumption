<?php $control=1;
function getBalance($idno){
    $totalAmount = \App\Ledger::where('idno',$idno)->sum('amount');
    $totalPayment = \App\Ledger::where('idno',$idno)->sum('payment');
    $totalDebit = \App\Ledger::where('idno',$idno)->sum('debit_memo');
    $totalDiscount = \App\Ledger::where('idno',$idno)->sum('discount');
    $totalECS = \App\Ledger::where('idno',$idno)->sum('esc');
    
    return $totalAmount-($totalPayment+$totalDebit+$totalDiscount+$totalECS);
}
?>
<table>
    <tr>
        <td colspan="6">List of Students that are on hold of viewing Grades</td>
    </tr>
    <tr>
        <td align="right"><strong>#</strong></td>
        <th>ID Number</th>
        <th>Name</th>
        <th>Level</th>
        <th>Section</th>
        <th>Balance</th>
        <th>Status</th>
    </tr>
    @foreach($hold_students as $hold)
    <tr>
        <td align="right">{{$control++}}.</td>
        <td>{{$hold->idno}}</td>
        <td>{{$hold->lastname}}, {{$hold->firstname}} {{$hold->middlename}}</td>
        <td>{{$hold->level}}</td>
        <td>{{$hold->section}}</td>
        <td>{{number_format(getBalance($hold->idno),2)}}</td>
        <td>
            @if($hold->status == 3)Enrolled
            @elseif($hold->status == 2) Assessed
            @elseif($hold->status == 10) Pre-Registered
            @elseif($hold->status == 11) For Approval
            @elseif($hold->status == 4) Withdrawn-{{$status->date_dropped}}
            @else Not Yet Enrolled @endif
        </td>
    </tr>
    @endforeach
</table>
