<?php $ctr = 0;?>
<div class="container-fluid">
<form class="form-horizontal" action="{{url('accounting', 'save_charges')}}" method="POST">
    {{ csrf_field()}}
    <input type="hidden" name="date" value="{{$dates}}" id="dates">
    <div class="form form-group">
        <div class="col-sm-6">
            <h3>Listed below are the unpaid students for this month.</h3>
            <h4>Simply <strong>UNCHECK</strong> the student if you <strong>DO NOT WISH</strong> to post their charges. To post late payment charges: Simply hit the [Post Charges] button</h4>
        </div>
        <div class="col-sm-6">
            <br>
            <input type="submit" class="col-sm-12 btn btn-success" value="Post Charges">
        </div>
    </div>
    <table id="example" class="table table-bordered table-responsive table-condensed">
        <thead>
            <tr>
                <th></th>
                <th>Student Number</th>
                <th>Full Name</th>
                <th>Plan</th>
                <th>Level</th>
                <th>Section</th>
                <th>Balance</th>
                <th>Selected</th>
                <th>Posted / Unpaid</th>
            </tr>
        </thead>
        <tbody>
        <?php $x = 0; ?>
        @foreach($unpaid as $un)
        <?php $check = checkLedger($un->idno, $dates);?>
        @if($check >= 0)
        <?php $baql=getBalance($un->idno,$dates);?>
        @if($baql > 0)
        
        <?php $ctr++;?>
        
        <tr>
            <td>{{++$x}}</td>
            <td>{{$un->idno}}</td>
            <td>{{strtoupper($un->lastname)}}, {{$un->firstname}} {{$un->middlename}}</td>
            <td>{{$un->type_of_plan}}</td>
            <td>{{$un->level}}</td>
            <td>{{$un->section}}</td>
            <td><?php echo getBalance($un->idno,$dates)?></td>
            <td>
                @if($check == 1)  
                    <a type="button" id="reverse{{$un->idno}}" value="Reverse" onclick="reversePost('{{$un->idno}}')">Reverse</a> 
                @else 
                    <input type="checkbox" name="post[]" value="{{$un->idno}}" checked/> 
                @endif</td>
            <td>@if($check == 1) Posted and unpaid @else Not yet posted. @endif</td>
        </tr>
        @endif
        @endif
        @endforeach
        @if($ctr == 0)
        <tr><td colspan="7" align="center" style="font-size:15pt"><strong>Nothing to show.</strong></td></tr>
        @endif
        </tbody>
    </table>
</form>
</div>
<?php

function checkLedger($idno, $date) {
    $academic_type = \App\Status::where('idno',$idno)->first();
    $school_year = \App\CtrAcademicSchoolYear::where('academic_type',$academic_type->academic_type)->first();
    if($academic_type->academic_type == 'BED'){
        $mainledgers = \App\Ledger::where('idno', $idno)->where('school_year',$school_year->school_year)->where('category_switch','<=', 6 )->get();
        $duedates = \App\LedgerDueDate::where('idno', $idno)->where('school_year',$school_year->school_year)->get();
    }
    else{
        $mainledgers = \App\Ledger::where('idno', $idno)->where('period',$school_year->period)->where('school_year',$school_year->school_year)->where('category_switch','<=', 6 )->get();
        $duedates = \App\LedgerDueDate::where('idno', $idno)->where('period',$school_year->period)->where('school_year',$school_year->school_year)->get();
    }
    
    $mainpayment = 0;
    $result = 0;
    $due = 0;
    
    $dateToday = Carbon\Carbon::now();
    $years2 = sprintf("%02d",date_format($dateToday,'Y'));
    $is_posted = \App\PostedCharges::where('idno',$idno)->where('due_date',$date)->whereRaw("YEAR(date_posted) = $years2")->where('is_reversed','0')->first();
    
//    $is_posted = DB::select("SELECT * FROM posted_charges WHERE idno = '$idno' AND due_date = '$date' AND is_reversed = 0");
    
    foreach ($mainledgers as $payment) {
        $mainpayment = $mainpayment + $payment->payment + $payment->debit_memo;
    }
    
    $dateToday = Carbon\Carbon::now();
    $dates1 = sprintf("%02d",date_format($dateToday,'m') - 1);
    $dates2 = date_format($dateToday,"Y-$dates1-31");
    if($dates1 == 0 ){
        $dates1 = 12;
        $years = sprintf("%02d", date_format($dateToday, 'Y') - 1);
        $dates2 = date_format($dateToday, "$years-$dates1-31");
    }
    foreach ($duedates as $duedate) {
    $due = $due + $duedate->amount; 
    $monthdate = date_format(date_create($duedate->due_date),'m');
        if($duedate->due_switch == 0){
                if(count($is_posted) > 0){
                        $result = 1;
                    }
                    else{
                        $result = 0;
                    }
            }else{
                if ($duedate->due_date <= $dates2) {
                    
                        if(count($is_posted) > 0){
                            $result = 1;
                        }
                        else{
                            $result = 0;
                        }
                    break;
                }
        }
    } 
    return $result;
}

function getBalance($idno,$date){
//    $mainledgers = \App\Ledger::where('idno', $idno)->where('category_switch','<=','6')->get();
//    $duedates = \App\LedgerDueDate::where('idno', $idno)->get();
//    $mainpayment = 0;
//    $due = 0;
    
    $academic_type = \App\Status::where('idno',$idno)->first();
    $school_year = \App\CtrAcademicSchoolYear::where('academic_type',$academic_type->academic_type)->first();
    if($academic_type->academic_type == 'BED'){
        $mainledgers = \App\Ledger::where('idno', $idno)->where('school_year',$school_year->school_year)->where('category_switch','<=', 6 )->get();
        $duedates = \App\LedgerDueDate::where('idno', $idno)->where('school_year',$school_year->school_year)->get();
    }
    else{
        $mainledgers = \App\Ledger::where('idno', $idno)->where('period',$school_year->period)->where('school_year',$school_year->school_year)->where('category_switch','<=', 6 )->get();
        $duedates = \App\LedgerDueDate::where('idno', $idno)->where('period',$school_year->period)->where('school_year',$school_year->school_year)->get();
    }
    $mainpayment = 0;
    $due = 0;
    
    
    
    foreach ($mainledgers as $payment) {
        $mainpayment = $mainpayment + $payment->payment + $payment->debit_memo;
    }
    
    $bal = 0;
    
    $dateToday = Carbon\Carbon::now();
    $dates1 = sprintf("%02d",date_format($dateToday,'m') - 1);
    $dates2 = date_format($dateToday,"Y-$dates1-31");
    if($dates1 == 0 ){
        $dates1 = 12;
        $years = sprintf("%02d", date_format($dateToday, 'Y') - 1);
        $dates2 = date_format($dateToday, "$years-$dates1-31");
    }
    foreach ($duedates as $duedate) {
    $due = $due + $duedate->amount; 
    $monthdate = date_format(date_create($duedate->due_date),'m');
        if($duedate->due_switch == 0){
            $bal = $due - $mainpayment;
        }else{
            if ($duedate->due_date <= $dates2) {
                $bal = $due - $mainpayment;
            }   
        }
    } 
    return number_format($bal,2);
}
?>

