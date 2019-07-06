<label>Select Schedule</label>
<select name='schedule_id' id='schedule_id' class='form-control select2' style='width: 100%;' required="required">
    <option value="all">all</option>
    @foreach ($lists as $sched)
    <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $sched->schedule_id)->first()->is_tba; ?>
    <option value="{{$sched->schedule_id}}">
        <a href="{{url('college_instructor', array('grades', $sched->schedule_id))}}">
            <i class="fa fa-circle-o"></i> 
            @if($is_tba == 0)
            <span>
                <?php
                $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $sched->schedule_id)->get(['time_start', 'time_end', 'room']);
                ?>   
                @foreach ($schedule3s as $schedule3)
                {{$schedule3->room}}
                @endforeach
                <?php
                $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $sched->schedule_id)->get(['time_start', 'time_end', 'room']);
                ?>
                @foreach ($schedule2s as $schedule2)
                <?php
                $days = \App\ScheduleCollege::where('schedule_id', $sched->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                ?>
                <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                [@foreach ($days as $day){{$day->day}}@endforeach {{date('g:iA', strtotime($schedule2->time_start))}}-{{date('g:iA', strtotime($schedule2->time_end))}}]<br>
                <?php
                        $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $sched->schedule_id)->get(['instructor_id']);

                        foreach ($schedule_instructor as $get) {
                            if ($get->instructor_id != NULL) {
                                $instructor = \App\User::where('idno', $get->instructor_id)->first();
                                echo "$instructor->firstname $instructor->lastname $instructor->extensionname";
                            } else {
                                echo "";
                            }
                        }
                        ?>
                @endforeach
            </span>
            @else
            <span>TBA</span>
            @endif
        </a>
    </option>
    @endforeach
</select>
<script>
    $(function () {
        $('.select2').select2();
    });
</script>