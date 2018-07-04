<div class="form-group">
    <label>Select Schedule</label>
    <select class="form form-control select2" id="schedule_id">
        <option value="">Select Schedule</option>
        @foreach ($schedules as $sched)
        <option value="{{$sched->schedule_id}}">
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
        @endforeach
        </option>
        @endforeach
    </select>
</div>

<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
$(function () {
    $('.select2').select2();
});
</script>