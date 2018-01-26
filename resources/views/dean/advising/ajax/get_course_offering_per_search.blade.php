
                    <div class='table-responsive'>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Code</th>
            <th>Course Name</th>
            <th>Schedule</th>
            <th>Room</th>
            <th>Section</th>
            <th>Instructor</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($courses_offering as $course_offering)
        <tr>
            <td>{{$course_offering->course_code}}</td>
            <td>{{$course_offering->course_name}}</td>
            <?php
            $schedules = \App\ScheduleCollege::where('schedule_id', $course_offering->schedule_id)->get();
            ?>
            <td>
                <?php
                $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $course_offering->schedule_id)->get(['time_start', 'time_end', 'room']);
                ?>
                @foreach ($schedule2s as $schedule2)
                <?php
                $days = \App\ScheduleCollege::where('schedule_id', $course_offering->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                ?>
                <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                @foreach ($days as $day){{$day->day}}@endforeach {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                @endforeach
            </td>
            <td>
                <?php
                $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $course_offering->schedule_id)->get(['time_start', 'time_end', 'room']);
                ?>
                @foreach ($schedule3s as $schedule3)
                {{$schedule3->room}}<br>
                @endforeach
            </td>
            <td>{{$course_offering->section_name}}</td>
            <td>
                <?php
                $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $course_offering->schedule_id)->get(['instructor_id']);
                ?>
                @foreach($schedule_instructor as $get)
                    @if ($get->instructor_id != NULL)
                    <?php $instructor = \App\User::where('idno', $get->instructor_id)->first(); ?>
                    {{$instructor->firstname}} {{$instructor->lastname}} {{$instructor->extensionname}}
                    @endif
                @endforeach
            </td>
            <td><button class="btn btn-primary" onclick="add_to_course_offered('{{$course_offering->id}}')"><span class="fa fa-plus-circle"></span></button></td>
        </tr>
        @endforeach
    </tbody>
</table>
                    </div>