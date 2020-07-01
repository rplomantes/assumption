@if(count($lists)>0)
<div class='table-responsive'>
<table class="table table-striped table-condensed">
    <tr>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Status</th>
        <th>View Full Information</th>
        <th>View Pre-Registration Form/Enrollment Permit</th>
        <th>Remove Application</th>

    </tr>
    @foreach($lists as $list)
    @if($list->accesslevel == '0')
    <?php $status = \App\Status::where('idno', $list->idno)->first(); ?>
    <tr>
        <td>{{$list->idno}}</td>
        <td>{{$status->getFullNameAttribute()}}</td>
        <td>
            @if($status->status == 3)Enrolled
            @elseif($status->status == 2) Assessed
            @elseif($status->status == 1) Advised
            @elseif($status->status == 4) Withdrawn-{{$status->date_dropped}}
            @elseif($status->status == 20) Pre-Registered
            @elseif($status->status == 21) Not Approved
            @else Not Yet Enrolled/Approved @endif
        </td>
        <td><a href="{{url('/registrar_college/view_info', array('idno'=> $list->idno))}}">View Full Information</a></td>
        <td><a href="{{url('/admission_hed/view_info', array('idno'=> $list->idno))}}">View Pre-Registration Form/Enrollment Permit</a></td>
        <td>
            @if($status->status == 20 || $status->status == 21)
            <a href="{{url('/admission_hed/remove_application', array('idno'=> $list->idno))}}">Remove Application</a>
            @endif
        </td>
        
    </tr>
    @endif
    @endforeach
</table>    
</div>
@else
<h1> Record Not Found</h1>
@endif

