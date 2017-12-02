
<div class="box-header">
    <h3 class="box-title">Courses Offered</h3>
    <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
</div>
<div class="box-body">
    @if (count($courses)>0)
    <table class="table table-striped">
        <thead>
        <th>Course Code</th>
        <th>Course Name</th>
        <th>Schedule</th>
        <th>Room</th>
        <th></th>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr>
                <td>{{$course->course_code}}</td>
                <td>
                    <?php
                    $schedules = \App\ScheduleCollege::where('course_offering_id', $course->id)->get();
                    ?>
                    {{$course->course_name}}

                </td>
                <td>
                    <?php
                    $schedule2s = \App\ScheduleCollege::distinct()->where('course_offering_id', $course->id)->get(['time_start', 'time_end', 'room']);
                    ?>
                    @foreach ($schedule2s as $schedule2)
                    <?php
                    $days = \App\ScheduleCollege::where('course_offering_id', $course->id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                    ?>
                    <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                    @foreach ($days as $day){{$day->day}}@endforeach {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                    @endforeach
                </td>
                <td>
                    <?php
                    $schedule3s = \App\ScheduleCollege::distinct()->where('course_offering_id', $course->id)->get(['time_start', 'time_end', 'room']);
                    ?>
                    @foreach ($schedule3s as $schedule3)
                    {{$schedule3->room}}<br>
                    @endforeach
                </td>
                <td><a href="{{url('registrar_college', array('curriculum_management','edit_course_schedule',$course->id))}}" target="_blank"><button class="btn btn-info"><span class="fa fa-pencil"></span></button></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Alert!</h4>
        No Courses Offered for this section!!!
    </div>
    @endif
</div>