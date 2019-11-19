<div class="box-body">
    @if (count($courses)>0)
    <div class='table-responsive'>
        <h3>Number of Students: <strong>{{count($number_of_students)}}</strong></h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Schedule</th>
                    <th>Room</th>
                    <th>Instructor</th>
                    <th>No. of Students</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalunits = 0;?>
                @foreach($courses as $course)
                <?php $number_of_students_per_course = \App\ScheduleCollege::distinct()->where('schedule_colleges.course_code', $course->course_code)->where('schedule_colleges.school_year', $school_year)->where('schedule_colleges.period', $period)->join('course_offerings', 'course_offerings.schedule_id','schedule_colleges.schedule_id')->join('grade_colleges','grade_colleges.course_offering_id', 'course_offerings.id')->join('statuses', 'statuses.idno', 'grade_colleges.idno')->where('statuses.status', env("ENROLLED"))->get(['grade_colleges.idno']); ?>
                <?php $course_name = \App\Curriculum::where('course_code', $course->course_code)->first(); ?>
                @if(count($course_name)==0)
                <?php $course_name = \App\CtrElective::where('course_code', $course->course_code)->first(); ?>
                @endif
                <tr>
                    <td>{{$course->course_code}}</td>
                    <td>
                        {{$course_name->course_name}}
                    </td>      
                    <td>
                        <?php
                        $schedules = \App\ScheduleCollege::distinct()->where('schedule_id', $course->schedule_id)->get(['time_start', 'time_end', 'room']);
                        ?>
                        @foreach ($schedules as $schedule)
                        <?php
                        $days = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->where('time_start', $schedule->time_start)->where('time_end', $schedule->time_end)->where('room', $schedule->room)->get(['day']);?>
                        @foreach ($days as $day){{$day->day}}@endforeach 
                        <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->first()->is_tba; ?>
                        @if ($is_tba == 0)
                        {{date('g:i A', strtotime($schedule->time_start))}} - {{date('g:i A', strtotime($schedule->time_end))}}<br>
                        @else

                        @endif
                        @endforeach
                    </td>
                    <td>
                        <?php
                        $schedules2 = \App\ScheduleCollege::distinct()->where('schedule_id', $course->schedule_id)->get(['time_start', 'time_end', 'room']);?>
                        @foreach ($schedules2 as $schedule2)
                        {{$schedule2->room}}<br>
                        @endforeach
                    </td>
                    <td>
                        <?php
                        $offering_id = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->first();
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
                    <td>{{count($number_of_students_per_course)}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <form method='post' action='{{url('registrar_college',array('curriculum_management','ajax','print_show_offerings_per_day'))}}'>
              {{csrf_field()}}  
              <input type='hidden' name='school_year' value='{{$school_year}}'>
                <input type='hidden' name='period' value='{{$period}}'>
                <input type='hidden' name='day' value='{{$day1}}'>
            <input type='submit' formtarget='_blank' class='btn btn-primary col-sm-12' value='Print Schedule'>
        </form>
    </div>
    @else
    <div class="alert alert-info alert-dismissible">
        <h4><i class="icon fa fa-info"></i> Alert!</h4>
        No Schedule for this Course!!!
    </div>
    @endif
</div>