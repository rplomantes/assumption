<div class="box-body">
    @if (count($courses)>0)
    <div class='table-responsive'>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Units</th>
                    <th>Schedule</th>
                    <th>Room</th>
                    <th>Instructor</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalunits = 0; ?>
                @foreach($courses as $course)
                <tr>
                    <td>{{$course->course_code}}</td>
                    <td>
                        <?php
                        $schedules = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->get();
                        ?>
                        {{$course->course_name}}

                    </td>
                    <td>{{$unit = $course->lec + $course->lab}}
                    <?php $totalunits = $totalunits + $unit?></td>
                    <td>
                        <?php
                        $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $course->schedule_id)->get(['time_start', 'time_end', 'room']);
                        ?>
                        @foreach ($schedule2s as $schedule2)
                        <?php
                        $days = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                        ?>
                        <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                        @foreach ($days as $day){{$day->day}}@endforeach 
                        <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->first()->is_tba; ?>
                        @if ($is_tba == 0)
                        {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                        @else

                        @endif
                        @endforeach
                    </td>
                    <td>
                        <?php
                        $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $course->schedule_id)->get(['time_start', 'time_end', 'room']);
                        ?>
                        @foreach ($schedule3s as $schedule3)
                        {{$schedule3->room}}<br>
                        @endforeach
                    </td>
                    <td>
                        <?php
                        $offering_id = \App\CourseOffering::find($course->id);
                        $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_id->schedule_id)->get(['instructor_id']);

                        foreach ($schedule_instructor as $get) {
                            if ($get->instructor_id != NULL) {
                                $instructor = \App\User::where('idno', $get->instructor_id)->first();
                                echo "$instructor->firstname $instructor->lastname $instructor->extensionname";
                            } else {
                                echo "";
                            }
                        }
                        ?>
                    </td>
                </tr>
                @endforeach
        <tr>
            <td align="left"><b>TOTAL UNITS:</b></td>
            <td><b></b></td>
            <td><b>{{$totalunits}}</b></td>
            <td><b></b></td>
            <td><b></b></td>
            <td><b></b></td>
        </tr>                       
            </tbody>
        </table>
        <form method='post' action='{{url('registrar_college',array('curriculum_management','ajax','printshowoffering'))}}'>
              {{csrf_field()}}  
              <input type='hidden' name='school_year' value='{{$school_year}}'>
                <input type='hidden' name='period' value='{{$period}}'>
                <input type='hidden' name='level' value='{{$level}}'>
                <input type='hidden' name='program_code' value='{{$program_code}}'>
                <input type='hidden' name='section' value='{{$section}}'>
            <input type='submit' class='btn btn-primary col-sm-12' value='Print Schedule'>
        </form>
    </div>
    @else
    <div class="alert alert-info alert-dismissible">
        <h4><i class="icon fa fa-info"></i> Alert!</h4>
        No Courses Offered for this section!!!
    </div>
    @endif
</div>