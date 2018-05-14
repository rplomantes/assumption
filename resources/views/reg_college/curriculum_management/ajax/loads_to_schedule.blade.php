<?php
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
$available_classes = \App\ScheduleCollege::distinct()->where('course_code', $course_code)
        ->where('school_year', $school_year->school_year)
        ->where('period', $school_year->period)
        ->where(function($q) use ($instructor_id) {
            $q->where('instructor_id', NULL)
            ->orWhere('instructor_id', "NOT LIKE", $instructor_id);
        })->get(['schedule_id']);
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Available Classes</h4>
        </div>
        <form method="post" action="{{url ('registrar_college', array('curriculum_management','add_faculty_loading'))}}">
        <div class="modal-body">
            <div class="form-group">
                <label>Available Classes</label>
                {{ csrf_field() }}
                <input type="hidden" value="{{$instructor_id}}" name="instructor_id">
                <select name="schedule_id" id="schedule_id" class="form-control select2" style="width: 100%;" required="required">
                    <option value=" ">Select a Class</option>
                    @foreach ($available_classes as $available_class)
                    <option value="{{$available_class->schedule_id}}">
                        
                                    <?php
                                    $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $available_class->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule2s as $schedule2)
                                    <?php
                                    $days = \App\ScheduleCollege::where('schedule_id', $available_class->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                    ?>
                                    <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                                    @foreach ($days as $day){{$day->day}}@endforeach
                                    <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $available_class->schedule_id)->first()->is_tba; ?>
                                        @if ($is_tba == 0)
                                        {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                                        @else
                                        
                                        @endif
                                    @endforeach
                                    <?php
                                    $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $available_class->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule3s as $schedule3)
                                    {{$schedule3->room}}<br>
                                    @endforeach
                        
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
            <input type="submit" class="btn btn-primary" value="Add"></input>
        </div>
    </form>
    </div>
</div>
<script>
    $(function () {
        $('.select2').select2();
    });
</script>