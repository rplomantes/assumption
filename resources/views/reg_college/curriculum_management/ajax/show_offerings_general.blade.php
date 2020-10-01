<div class="box-body">
    @if (count($courses)>0)
    <div class='table-responsive'>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="10%">Course Code</th>
                    <th>Section</th>                    
                    <th>Course Name</th>
                    <th>Unit</th>
                    <th>Enrolled</th>
                    <th>Assessed</th>
                    <th>Schedule</th>
                    <th>Room</th>
                    <th>Instructor</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalunits = 0;?>
                @foreach($courses as $course)
                
                <?php $get_student=0; ?>
                <?php $cofferings = \App\CourseOffering::where('schedule_id', $course->schedule_id)->get(); ?>
                @foreach ($cofferings as $coffering)
                <?php $get_number = \App\GradeCollege::where('course_offering_id', $coffering->id)->get(); ?>
                <?php $get_student = $get_student + count($get_number); ?>
                @endforeach
                
                <tr>
                    <td>{{$course->course_code}}</td>
                    <td>
                        {{$course->section_name}}
                    </td>                    
                    <td>
                        <?php
                        $schedules = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->get();
                        ?>
                        {{$course->course_name}}

                    </td>
                    <td>
                        {{$units = $course->lab + $course->lec}}
                    </td>
                    <td>
                        <?php $no = \App\CourseOffering::where('schedule_id', $course->schedule_id)->leftJoin('grade_colleges', 'grade_colleges.course_offering_id','=','course_offerings.id')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period',$period)->get(['grade_colleges.id']); ?>
                        {{count($no)}}
                    </td>
                    <td>{{$get_student}}</td>
                        <?php $totalunits = $totalunits + $units?>
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
            <td><b></b></td>
            <td><b></b></td>
            <td align="right">TOTAL UNITS:</td>
            <td><b>{{$totalunits}}</b></td>
            <td><b></b></td>
            <td><b></b></td>
            <td><b></b></td>
        </tr>                 
            </tbody>
        </table>
        <form method='post' action='{{url('registrar_college',array('curriculum_management','print_get_general'))}}'>
              {{csrf_field()}}  
                <input type='hidden' name='school_year' value='{{$school_year}}'>
                <input type='hidden' name='period' value='{{$period}}'>
            <input type='submit' class='btn btn-primary col-sm-12' value='Print Schedule'>
        </form>
    </div>
    @else
    <div class="alert alert-info alert-dismissible">
        <h4><i class="icon fa fa-info"></i> Alert!</h4>
        No Courses Offered !!!
    </div>
    @endif
</div>