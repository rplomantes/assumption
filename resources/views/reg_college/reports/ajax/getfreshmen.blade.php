<div class='form-group'> 
    <div class="col-sm-12">
@if(count($lists)>0)
<table id="example2" class="table table-bordered table-striped">
    <thead>
        <tr>    
            <th><strong>ID Number</strong></th>
            <th><strong>Name</strong></th>
            <th><strong>Program</strong></th>
            <th><strong>Period</strong></th>
            <th><strong>Last School Attended</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($lists as $list)
        <?php $user = \App\User::where('idno', $list->idno)->first(); ?>
        <?php $student_info = \App\StudentInfo::where('idno', $list->idno)->first(); ?>
        <?php $period = \App\CollegeLevel::where('idno', $list->idno)->first(); ?>
        
        <tr>
            <td>{{$list->idno}}</td>
            <td>{{$user->lastname}}, {{$user->firstname}}</td>
            <td>{{$list->program_code}}</td>
            <td>{{$period->period}}</td>
            <td>{{$student_info->last_school_attended}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<h1>Record Not Found!!!</h1>
@endif
    </div>
</div>
        <div class='form form-group'>
            <div class='col-sm-12'>
                <a target='_blank' href='{{url('registrar_college', array('reports', 'ajax', 'printfreshmen', $school_year))}}'><button class='col-sm-12 btn btn-success'><span></span>GENERATE REPORT</button></a>
            </div>
        </div>
</form>
