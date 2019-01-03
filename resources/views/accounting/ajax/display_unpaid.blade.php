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
        <?php $countLedger = countLedger($un->idno, $dates);?>
        @if($check != 2)
        <?php $baql=getBalance($un->idno,$dates);?>
        @if($baql > 0)
        
        <?php $ctr++;?>
        
        <tr>
            <td>{{++$x}}</td>
            <td>{{$un->idno}}</td>
            <td>{{strtoupper($un->lastname)}}, {{$un->firstname}} {{$un->middlename}}</td>
            <td>{{$un->level}}</td>
            <td>{{$un->section}}</td>
            <td><?php echo getBalance($un->idno,$dates)?></td>
            <td>
                @if($check == 1)  
                    <a type="button" id="reverse{{$un->idno}}" value="Reverse" onclick="reversePost('{{$un->idno}}')">Reverse</a> 
                @else 
                    <input type="hidden" name="count[]" value="{{$countLedger}}"/>
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
        $mainledgers = \App\Ledger::where('idno', $idno)->where('school_year',$school_year->school_year)->get();
        $duedates = \App\LedgerDueDate::where('idno', $idno)->where('school_year',$school_year->school_year)->get();
    }
    else{
        $mainledgers = \App\Ledger::where('idno', $idno)->where('period',$school_year->period)->where('school_year',$school_year->school_year)->get();
        $duedates = \App\LedgerDueDate::where('idno', $idno)->where('period',$school_year->period)->where('school_year',$school_year->school_year)->get();
    }
    
    $mainpayment = 0;
    $result = 0;
    $due = 0;
    
    $is_posted = \App\PostedCharges::where('idno',$idno)->where('due_date',$date)->where('is_reversed','0')->first();
    
//    $is_posted = DB::select("SELECT * FROM posted_charges WHERE idno = '$idno' AND due_date = '$date' AND is_reversed = 0");
    
    foreach ($mainledgers as $payment) {
        $mainpayment = $mainpayment + $payment->payment + $payment->debit_memo;
    }
    
    foreach ($duedates as $duedate) {
    $due = $due + $duedate->amount; 
    $monthdate = date_format(date_create($duedate->due_date),'m');
        if ($monthdate == $date) {
            if ($mainpayment >= $due) {
                $result = 2;
            } 
            else {
                if(count($is_posted) > 0){
                    $result = 1;
                }
                else{
                    $result = 0;
                }
            }
            break;
        }
        else{
            $result = 2;
        }
    } 
    return $result;
}
function countLedger($idno, $date) {
    $academic_type = \App\Status::where('idno',$idno)->first();
    $school_year = \App\CtrAcademicSchoolYear::where('academic_type',$academic_type->academic_type)->first();
    if($academic_type->academic_type == 'BED'){
        $mainledgers = \App\Ledger::where('idno', $idno)->where('school_year',$school_year->school_year)->get();
        $duedates = \App\LedgerDueDate::where('idno', $idno)->where('school_year',$school_year->school_year)->get();
    }
    else{
        $mainledgers = \App\Ledger::where('idno', $idno)->where('period',$school_year->period)->where('school_year',$school_year->school_year)->get();
        $duedates = \App\LedgerDueDate::where('idno', $idno)->where('period',$school_year->period)->where('school_year',$school_year->school_year)->get();
    }
    $mainpayment = 0;
    $result = 0;
    $due = 0;
    $count = 0;
    
//    $is_posted = \App\PostedCharges::where('idno',$idno)->where('due_date',$date)->where('is_reversed','0')->first();
    $is_posted = DB::select("SELECT * FROM posted_charges WHERE idno = '$idno' AND due_date = '$date' AND is_reversed = 0");
    
    foreach ($mainledgers as $payment) {
        $mainpayment = $mainpayment + $payment->payment + $payment->debit_memo;
    }
    
    foreach ($duedates as $duedate) {
    $due = $due + $duedate->amount; 
    $monthdate = date_format(date_create($duedate->due_date),'m');
        if ($monthdate == $date) {
            if ($mainpayment >= $due) {
                $result = 2;
            } 
            else {
                if(count($is_posted) > 0){
                    $result = 1;
                }
                else{
                    $result = 0;
                }
            }
                    $count = $count+1;
            break;
        }else{
            if ($mainpayment >= $due) {
                $result = 2;
            } 
            else {
                if(count($is_posted) > 0){
                    $result = 1;
                }
                else{
                    $result = 0;
                    $count = $count+1;
                }
            }
        }
    } 
    return $count;
}

function getBalance($idno,$date){
//    $mainledgers = \App\Ledger::where('idno', $idno)->where('category_switch','<=','6')->get();
//    $duedates = \App\LedgerDueDate::where('idno', $idno)->get();
//    $mainpayment = 0;
//    $due = 0;
    
    $academic_type = \App\Status::where('idno',$idno)->first();
    $school_year = \App\CtrAcademicSchoolYear::where('academic_type',$academic_type->academic_type)->first();
    if($academic_type->academic_type == 'BED'){
        $mainledgers = \App\Ledger::where('idno', $idno)->where('school_year',$school_year->school_year)->get();
        $duedates = \App\LedgerDueDate::where('idno', $idno)->where('school_year',$school_year->school_year)->get();
    }
    else{
        $mainledgers = \App\Ledger::where('idno', $idno)->where('period',$school_year->period)->where('school_year',$school_year->school_year)->get();
        $duedates = \App\LedgerDueDate::where('idno', $idno)->where('period',$school_year->period)->where('school_year',$school_year->school_year)->get();
    }
    $mainpayment = 0;
    $due = 0;
    
    
    
    foreach ($mainledgers as $payment) {
        $mainpayment = $mainpayment + $payment->payment + $payment->debit_memo;
    }
    
    $bal = 0;
    foreach ($duedates as $duedate) {
    $due = $due + $duedate->amount; 
    $monthdate = date_format(date_create($duedate->due_date),'m');
        if ($monthdate == $date) {
            $bal = $due - $mainpayment;
        }
    } 
    return number_format($bal,2);
}
?>

