<?php $ctr = 1;?>
<div class="container-fluid">
<form class="form-horizontal" action="{{url('/admin/re_assess_now')}}" method="POST">
    {{ csrf_field()}}
    <div class="form form-group">
        <div class="col-sm-6">
            <h3>Listed below are the students that had early enrollments.</h3>
        </div>
        <div class="col-sm-6">
            <br>
            <input type="submit" class="col-sm-12 btn btn-success" value="Reassess Early Enrollments">
        </div>
    </div>
    <table id="example" class="table table-bordered table-responsive table-condensed">
        <thead>
            <tr>
                <th></th>
                <th>ID Number</th>
                <th>Name</th>
                <th>Plan</th>
                <th>Level</th>
                <th>Section</th>
                <th>Strand</th>
                <th>Reservation</th>
                <th>Student Deposit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $lists)
<?php            
$check_reservations = \App\Reservation::where('idno', $lists->idno)->where('reservation_type',1)->where('is_consumed', 0)->where('is_reverse', 0)->get();
$check_student_deposits = \App\Reservation::where('idno', $lists->idno)->where('reservation_type',2)->where('is_consumed', 0)->where('is_reverse', 0)->get();
?>            
<?php $reservation = 0; ?>
@if(count($check_reservations)>0)
@foreach ($check_reservations as $check_reservation)
<?php $reservation = $reservation + $check_reservation->amount; ?>
@endforeach
@endif
<?php $student_deposit = 0; ?>
@if(count($check_student_deposits)>0)
@foreach ($check_student_deposits as $check_student_deposit)
<?php $student_deposit = $student_deposit + $check_student_deposit->amount; ?>
@endforeach
@endif
<input type="hidden" name="idno[]" value="{{$lists->idno}}">
            <tr>
                <td>{{$ctr++}}</td>
                <td>{{$lists->idno}}</td>
                <td>{{$lists->getFullNameAttribute()}}</td>
                <td>{{$lists->type_of_plan}}</td>
                <td>{{$lists->level}}</td>
                <td>{{$lists->section}}</td>
                <td>{{$lists->strand}}</td>
                <td>{{number_format($reservation,2)}}</td>
                <td>{{number_format($student_deposit,2)}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>
</div>

